<?php

class FrameworkConnection {

    private $db;
    private $object;

    private $key;
    private $host;
    private $port;
    private $user;
    private $pswd;
    private $ssl;
    private $options;

    public function __construct($db, $conf)
    {
        $this->db = $db;
        $this->key = $conf['key'];
        $this->host = $conf['host'];
        $this->port = $conf['port'];
        $this->user = $conf['user'];
        $this->pswd = $conf['pswd'];
        $this->ssl = $conf['ssl'];
        $this->options = $conf['options'];

        $this->object = null;
    }

    public function get()
    {
        // return mysqli connection
        if (!$this->object) {
            switch ($this->db->type) {
            case 'mysql/mariadb':
                $this->mysqli_connect();
                break;
            }
        }
        return $this->object;
    }

    public function migrate($dir)
    {
        switch ($this->db->type) {
        case 'mysql/mariadb':
            $this->mysqli_migrate($dir);
            break;
        }
    }

    private function mysqli_migrate($dir)
    {
        $conn = $this->get();
        # set up table to store migrations
        $str = file_get_contents(realpath(
            'system/data-sql/migration-setup.sql'));
        $str = str_replace('{Framework4{DB_ENGINE}}',
            $this->db->engine, $str);
        $str = str_replace('{Framework4{DB_CHARSET}}',
            $this->db->charset, $str);
        $str = str_replace('{Framework4{DB_COLLATION}}',
            $this->db->collate, $str);
        mysqli_query($conn, $str);

        # collect migration files
        $migrations = array();
        $files = array();
        foreach (new FilesystemIterator($dir) as $i) {
            if (is_file($i))
                array_push($files, $i);
        }

        # sort
        $sorted = false;
        while (!$sorted) {
            $swapped = false;
            for ($i = 0; $i < count($files); $i++) {
                if (($i+1) < count($files)) {
                    $id = explode('_', $files[$i]->getFilename())[0];
                    $id_next = explode('_', $files[$i+1]->getFilename())[0];
                    if ($id_next < $id) {
                        $tmp = $files[$i];
                        $files[$i] = $files[$i+1];
                        $files[$i+1] = $tmp;
                        $swapped = true;
                    }
                } else {
                    if (!$swapped)
                        $sorted = true;
                }
            }
        }

        # filter files already migrated
        foreach ($files as $file) {
            $path = $file->getPathname();
            $sql = file_get_contents(
                realpath('system/data-sql/migration-check.sql'));
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, "s", $path);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            if (!mysqli_num_rows($res)) {
                # if not found in framework_migration
                array_push($migrations, $path);
            }
        }

        # execute migrations
        foreach ($migrations as $f) {
            # perform sql
            $str = file_get_contents(realpath($f));
            $str = str_replace('{Framework4{DB_ENGINE}}',
                $this->db->engine, $str);
            $str = str_replace('{Framework4{DB_CHARSET}}',
                $this->db->charset, $str);
            $str = str_replace('{Framework4{DB_COLLATION}}',
                $this->db->collate, $str);
            mysqli_query($conn, $str);

            # add to framework_migration table
            $stmt = mysqli_stmt_init($conn);
            $sql = file_get_contents(
                realpath('system/data-sql/migration-insert.sql'));
            mysqli_stmt_prepare($stmt, $sql);
            $v = AppConf::get('version');
            mysqli_stmt_bind_param($stmt, "ss", $f, $v);
            mysqli_stmt_execute($stmt);
        }
    }

    private function mysqli_connect()
    {
        $this->object = mysqli_init();
        if ($this->ssl['active']) {
            mysqli_ssl_set($this->object, $this->ssl['key'],
                $this->ssl['cert'], $this->ssl['cacert'], $this->ssl['capath'],
                $this->ssl['cipheralgos']);
        }
        if ($this->options) {
            foreach ($this->options as $k => $v) {
                mysqli_options($this->object, $k, $v);
            }
        }
        mysqli_real_connect(
            $this->object,
            $this->host,
            $this->user,
            $this->pswd,
            $this->db->name,
            $this->port
        );
        if ($this->object) {
            if (!mysqli_set_charset($this->object, $this->db->charset)) {
                throw new Exception("Failed to set mysql charset.");
            }
        }
    }

}

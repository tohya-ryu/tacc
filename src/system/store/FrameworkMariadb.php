<?php

class FrameworkMariadb implements FrameworkStore {
    use FrameworkMagicGet;
    private static $magic_get_attr = array(
        'type', 'name', 'charset', 'engine', 'collate'
    );

    private $name;
    private $key;
    private $type;
    private $engine;
    private $charset;
    private $collate;

    private $transaction_f;

    private $connections;
    private $default_conn_key;

    private $statement;
    private $result;

    # query builder
    private $query_type;
    private $table;
    private $table_alias;
    private $values;
    private $typeident;
    private $columns;
    private $conditions;

    public function __construct($config)
    {
        $this->name = $config['name'];
        $this->key = $config['key'];
        $this->type = $config['type'];
        $this->engine = $config['engine'];
        $this->charset = $config['charset'];
        $this->collate = $config['collate'];
        $this->transaction_f = false;
        $this->statement = null;
        $this->result =  null;

        $this->columns = array();
        $this->values = array();
        $this->typeident = "";
        $this->conditions = array();

        $this->connections = array();

        $this->init();

        foreach ($config['connections'] as $conn_conf) {
            $this->connections[$conn_conf['key']] = new FrameworkConnection(
                $this, $conn_conf);
            if ($conn_conf['default'])
                $this->default_conn_key = $conn_conf['key'];
        }
    }

    public function connection($key = false)
    {
        if ($key) {
            return $this->connections[$key];
        } else {
            return $this->connections[$this->default_conn_key];
        }
    }

    private function init()
    {
        switch ($this->type) {
        case 'mysql/mariadb':
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            break;
        }
    }

    public function migrate($dir)
    {
        foreach ($this->connections as $conn) {
            $conn->migrate($dir);
        }
    }

    public function begin_transaction()
    {
        $this->transaction_f = true;
        // Whenever a query during a transaction fails
        // ROLLBACK needs to be executed
    }

    public function commit_transaction()
    {
        $this->transaction_f = false;
        // execute commit
    }

    public function rollback()
    {
    }

    public function pquery($sql, $types, ...$params)
    {
        try {
            $conn = $this->connections[$this->default_conn_key]->get();
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $this->result = $stmt->get_result();
            $stmt->close();
            return $this->result;
        } catch (Exception $e) {
            if ($this->transaction_f) {
                $this->rollback();
            }
            $this->handle_exception($e);
        }

    }

    public function prepare($sql)
    {
        try {
            $conn = $this->connections[$this->default_conn_key]->get();
            $this->statement = $conn->prepare($sql);
        } catch (Exception $e) {
            if ($this->transaction_f) {
                $this->rollback();
            }
            $this->handle_exception($e);
        }
    }

    public function bind($types, &...$params)
    {
        try {
            $this->statement->bind_param($types, ...$params);
        } catch (Exception $e) {
            if ($this->transaction_f) {
                $this->rollback();
            }
            $this->handle_exception($e);
        }
    }

    public function exec()
    {
        try {
            $this->statement->execute();
        } catch (Exception $e) {
            if ($this->transaction_f) {
                $this->rollback();
            }
            $this->handle_exception($e);
        }
    }

    public function get_result()
    {
        try {
            return $this->statement->get_result();
        } catch (Exception $e) {
            $this->handle_exception($e);
        }
    }

    public function close_statement()
    {
        try {
            $this->statement->close();
        } catch (Exception $e) {
            $this->handle_exception($e);
        }
    }

    public function query($sql)
    {
        try {
            $conn = $this->connections[$this->default_conn_key]->get();
            $this->result = $conn->query($sql);
            return $this->result;
        } catch (Exception $e) {
            if ($this->transaction_f) {
                $this->rollback();
            }
            $this->handle_exception($e);
        }
    }

    public function delete($sql, $types = null, $params = null)
    {
    }

    public function handle_exception($e)
    {
        echo "DB error: $e";
        exit();
    }

    public function escape($str)
    {
        try {
            $conn = $this->connections[$this->default_conn_key]->get();
            return $conn->real_escape_string($str);
        } catch (Exception $e) {
            if ($this->transaction_f) {
                $this->rollback();
            }
            $this->handle_exception($e);
        }
    }

    public function set($column, $typeident, $value)
    {
        array_push($this->columns, $this->escape($column));
        array_push($this->values, $value);
        $this->typeident .= $typeident;
    }

    public function insert_into($table)
    {
        $this->query_type = 'insert';
        $this->table = $table;
    }

    public function update($table)
    {
        $this->query_type = 'update';
        $this->table = $table;
    }

    public function run()
    {
        $ret = null;
        switch ($this->query_type) {
        case 'insert':
            $ret = $this->insert();
            break;
        case 'update':
            $ret = $this->update_table();
            break;
        }
        $this->values      = array();
        $this->columns     = array();
        $this->conditions  = array();
        $this->typeident   = "";
        $this->table       = null;
        $this->table_alias = false;
        return $ret;
    }

    public function select($column, $alias = false)
    {
    }

    public function count($column)
    {
    }

    public function from($table, $alias = false)
    {
        $this->table = $table;
        $this->table_alias = $alias;
    }

    public function where($column, $op, $typeident, $value)
    {
        $ar = array(
            'column' => $column,
            'op' => $op,
            'typeident' => $typeident,
            'value' => $value,
            'or' => false
        );
        array_push($this->conditions, $ar);
        array_push($this->values, $value);
        $this->typeident .= $typeident;
    }

    public function or_where($column, $op, $typeident, $value)
    {
    }

    public function where_between($column, $typeidents, $value1, $value2)
    {
    }

    public function or_where_between($column, $typeidents, $value1, $value2)
    {
    }

    private function update_table()
    {
        $setters = "";
        $values = "";
        $last_key = ArrayUtil::last_key($this->columns);
        foreach ($this->columns as $k => $v) {
            $setters .= "`$v` = ?";
            if (!($k === $last_key)) {
                $setters .= ",";
            }
        }
        $conditions = $this->collapse_conditions();
        $sql = "UPDATE `{$this->table}` SET $setters $conditions;";
        /*
        var_dump($sql);
        var_dump($this->typeident);
        var_dump($this->values);
         */
        try {
            $conn = $this->connections[$this->default_conn_key]->get();
            $this->pquery($sql, $this->typeident, ...($this->values));
            return true;
        } catch (Exception $e) {
            if ($this->transaction_f) {
                $this->rollback();
            }
            $this->handle_exception($e);
        }
        return false;
    }

    private function insert()
    {
        $columns = "";
        $values = "";
        $last_key = ArrayUtil::last_key($this->columns);
        foreach ($this->columns as $k => $v) {
            $columns .= "`$v`";
            $values .= "?";
            if (!($k === $last_key)) {
                $columns .= ",";
                $values  .= ",";
            }
        }
        $sql = "INSERT INTO `{$this->table}` ($columns) VALUES ($values);";
        try {
            $conn = $this->connections[$this->default_conn_key]->get();
            $this->pquery($sql, $this->typeident, ...($this->values));
            return $conn->insert_id;
        } catch (Exception $e) {
            if ($this->transaction_f) {
                $this->rollback();
            }
            $this->handle_exception($e);
        }
    }

    private function collapse_conditions()
    {
        $sql = "";
        $i = 0;
        $handling_or = false;
        foreach ($this->conditions as $cond) {
            if ($i == 0) {
                $sql = "WHERE ";
            } else {
                if ($cond['or'] && $handling_or) {
                    $sql .= " OR ";
                } else {
                    $sql .= " AND ";
                }
            }
            if ($i+1 < count($this->conditions) &&
                $this->conditions[$i+1]['or'] &&
                !$handling_or)
            {
                $sql .= "( ";
                $handling_or = true;
            }
            $sql .= $cond['column'] . " ";
            $sql .= $cond['op'] . " ? ";

            $i++;
            if ($handling_or && ($i == count($this->conditions)) ||
                ($i+1 < count($this->conditions) && 
                !$this->conditions[$i+1]['or']))
            {
                $sql .= ") ";
            }
        }
        return $sql;
    }


}

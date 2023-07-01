<?php

interface FrameworkStore {

    public function __construct($config);

    public function connection($key = false);

    public function migrate($dir);

    public function begin_transaction();
    public function commit_transaction();
    public function rollback();
    public function handle_exception($e);
    public function query($sql);
    public function pquery($sql, $types, ...$params);
    public function delete($sql, $types = null, $params = null);

    public function insert_into($table);
    public function update($table);
    public function set($column, $typeident, $value);
    public function select($column, $alias = false);
    public function count($column);
    public function where($column, $op, $typeident, $value);
    public function or_where($column, $op, $typeident, $value);
    public function where_between($column, $typeidents, $value1, $value2);
    public function or_where_between($column, $typeidents, $value1, $value2);
    public function from($table, $alias = false);
    public function run();

    public function prepare($sql);
    public function bind($types, &...$params);
    public function exec();
    public function close_statement();

}

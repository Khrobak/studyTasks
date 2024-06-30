<?php

namespace core;

use Database;

require_once 'Database.php';

abstract class Model
{
    private $data;
    protected $db;
    protected $table;
    protected $fields;

    public function __construct()
    {
        $this->db = Database::getDBO();
        $this->defineModel();
    }

    abstract protected function defineModel();

    abstract public function get($data);

    abstract public function update($newData, $oldData);

    abstract public function store($data): bool;

}
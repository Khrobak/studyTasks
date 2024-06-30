<?php

namespace models;

use core\Model;

class User extends Model
{
    protected function defineModel()
    {
        $this->fields = ['name', 'email', 'password'];
        $this->table = 'mvc_users';
    }

    public function get($data)
    {
        $where = ' `email`=? AND `password`=? ';
        return $this->db->getRowByWhere($this->table, $where, $data);
    }

    public function update($newData, $oldData)
    {
        $response = $this->get($oldData);
        if (is_array($response)) {
            $newData[] = $oldData[0];
            return $this->db->updateByEmail($this->table, $this->fields, $newData);
        } else {
            $_SESSION['errors'][] = $response;
            return false;
        }
    }

    public function store($data): bool
    {
        return $this->db->insert($this->table, $this->fields, $data);
    }
}
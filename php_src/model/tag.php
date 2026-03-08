<?php

class TagModel extends Model
{
    protected $table = 'tags';
    protected $fields = ['name', 'color', 'description'];

    public function findByName($name)
    {
        $result = $this->db->selectAll($this->table, ['name' => $name], null, 1);
        return !empty($result) ? $result[0] : null;
    }

    public function getAllOrdered()
    {
        return $this->db->selectAll($this->table, [], 'name ASC');
    }
}


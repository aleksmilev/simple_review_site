<?php

class Model
{
    protected $db = null;

    protected $fields = [];
    protected $table = '';

    public function __construct()
    {
        $this->db = new Database();
    }

    protected function validateData($data)
    {
        $validatedData = [];
        foreach ($this->fields as $field) {
            if (isset($data[$field])) {
                $validatedData[$field] = $data[$field];
            }
        }

        return $validatedData;
    }

    public function add($data)
    {
        $data = $this->validateData($data);
        if (empty($data)) {
            throw new Exception('Invalid data');
        }

        $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        $data = $this->validateData($data);
        if (empty($data)) {
            throw new Exception('Invalid data');
        }

        $this->db->update($this->table, $id, $data);
    }

    public function get($id)
    {
        return $this->db->select($this->table, $id);
    }

    public function getAll($conditions = [], $orderBy = null, $limit = null)
    {
        return $this->db->selectAll($this->table, $conditions, $orderBy, $limit);
    }

    public function delete($id)
    {
        $this->db->delete($this->table, $id);
    }
}
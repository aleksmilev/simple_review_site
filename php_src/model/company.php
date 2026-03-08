<?php

class CompanyModel extends Model
{
    protected $table = 'companies';
    protected $fields = ['name', 'description', 'slug', 'website', 'created_by'];

    public function findBySlug($slug)
    {
        $result = $this->db->selectAll($this->table, ['slug' => $slug], null, 1);
        return !empty($result) ? $result[0] : null;
    }

    public function getByCreator($userId)
    {
        return $this->db->selectAll($this->table, ['created_by' => $userId], 'created_at DESC');
    }

    public function search($query)
    {
        $sql = "SELECT * FROM {$this->table} WHERE name LIKE :query1 OR description LIKE :query2 ORDER BY name ASC";
        $searchTerm = "%{$query}%";
        $stmt = $this->db->query($sql, ['query1' => $searchTerm, 'query2' => $searchTerm]);
        return $stmt->fetchAll();
    }
}


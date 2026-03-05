<?php

class ReviewModel extends Model
{
    protected $table = 'reviews';
    protected $fields = ['company_id', 'user_id', 'rating', 'title', 'content'];

    public function getByCompany($companyId, $limit = null)
    {
        return $this->db->selectAll($this->table, ['company_id' => $companyId], 'created_at DESC', $limit);
    }

    public function getByUser($userId, $limit = null)
    {
        return $this->db->selectAll($this->table, ['user_id' => $userId], 'created_at DESC', $limit);
    }

    public function getAnonymous($limit = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id IS NULL ORDER BY created_at DESC";
        if ($limit != null) {
            $sql .= " LIMIT {$limit}";
        }
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getAverageRating($companyId)
    {
        $sql = "SELECT AVG(rating) as average_rating, COUNT(*) as total_reviews FROM {$this->table} WHERE company_id = :company_id";
        $stmt = $this->db->query($sql, ['company_id' => $companyId]);
        return $stmt->fetch();
    }
}


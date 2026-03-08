<?php

class FeedbackModel extends Model
{
    protected $table = 'feedback';
    protected $fields = ['name', 'email', 'subject', 'message'];

    public function getRecent($limit = 10)
    {
        return $this->db->selectAll($this->table, [], 'created_at DESC', $limit);
    }

    public function getByEmail($email)
    {
        return $this->db->selectAll($this->table, ['email' => $email], 'created_at DESC');
    }
}


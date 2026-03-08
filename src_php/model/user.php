<?php

class UserModel extends Model
{
    protected $table = 'users';
    protected $fields = ['username', 'email', 'password_hash', 'role'];

    public function findByUsername($username)
    {
        $result = $this->db->selectAll($this->table, ['username' => $username], null, 1);
        return !empty($result) ? $result[0] : null;
    }

    public function findByEmail($email)
    {
        $result = $this->db->selectAll($this->table, ['email' => $email], null, 1);
        return !empty($result) ? $result[0] : null;
    }

    public function getAdmins()
    {
        return $this->db->selectAll($this->table, ['role' => 'admin']);
    }
}


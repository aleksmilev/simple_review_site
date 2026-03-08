<?php

class CompanyTagModel extends Model
{
    protected $table = 'company_tags';
    protected $fields = ['company_id', 'tag_id'];

    public function getTagsByCompany($companyId)
    {
        $sql = "SELECT t.* FROM tags t 
                INNER JOIN {$this->table} ct ON t.id = ct.tag_id 
                WHERE ct.company_id = :company_id 
                ORDER BY t.name ASC";
        $stmt = $this->db->query($sql, ['company_id' => $companyId]);
        return $stmt->fetchAll();
    }

    public function getCompaniesByTag($tagId)
    {
        $sql = "SELECT c.* FROM companies c 
                INNER JOIN {$this->table} ct ON c.id = ct.company_id 
                WHERE ct.tag_id = :tag_id 
                ORDER BY c.name ASC";
        $stmt = $this->db->query($sql, ['tag_id' => $tagId]);
        return $stmt->fetchAll();
    }

    public function addTagToCompany($companyId, $tagId)
    {
        $result = $this->db->selectAll($this->table, ['company_id' => $companyId, 'tag_id' => $tagId], null, 1);
        if (empty($result)) {
            $this->add(['company_id' => $companyId, 'tag_id' => $tagId]);
            return true;
        }
        return false;
    }

    public function removeTagFromCompany($companyId, $tagId)
    {
        $sql = "DELETE FROM {$this->table} WHERE company_id = :company_id AND tag_id = :tag_id";
        $stmt = $this->db->query($sql, ['company_id' => $companyId, 'tag_id' => $tagId]);
        return $stmt->rowCount();
    }
}


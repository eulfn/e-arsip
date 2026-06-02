<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Document_model extends CI_Model
{

    public function push($data, $category_id = null)
    {
        // * Insert into documents table
        $this->db->insert('documents', $data);
        $doc_id = $this->db->insert_id();

        // ! Handle single category
        if ($category_id) {
            $this->db->insert('documents_categories', ['document_id' => $doc_id, 'category_id' => $category_id]);
        }
        return $doc_id;
    }

    public function get_all()
    {
        // * Active Record for documents with a single category join
        $this->db->select("documents.*, user.username, GROUP_CONCAT(categories.name SEPARATOR ', ') as category_name");
        $this->db->from('documents');
        $this->db->join('user', 'documents.uploaded_by = user.id');
        $this->db->join('documents_categories', 'documents.id = documents_categories.document_id', 'left');
        $this->db->join('categories', 'documents_categories.category_id = categories.id', 'left');
        $this->db->group_by('documents.id');
        return $this->db->get()->result();
    }

    public function update($id, $data, $category_id = null)
    {
        // ! Update documents table
        $this->db->where('id', $id);
        $this->db->update('documents', $data);

        // ! Sync single category
        $this->db->where('document_id', $id);
        $this->db->delete('documents_categories');

        if ($category_id) {
            $this->db->insert('documents_categories', ['document_id' => $id, 'category_id' => $category_id]);
        }
        return true;
    }

    public function get_by_id($id)
    {
        // * Get document with its specific categories
        $this->db->select('documents.*, (SELECT GROUP_CONCAT(category_id) FROM documents_categories WHERE document_id = documents.id) as category_ids');
        $this->db->from('documents');
        $this->db->where('id', $id);
        return $this->db->get()->row();
    }

    public function toggle($id, $status)
    {
        // ! Update document status
        $this->db->where('id', $id);
        return $this->db->update('documents', ['status' => $status]);
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category_model extends CI_Model
{

    public function push($data)
    {
        $this->db->insert('categories', $data);
        return $this->db->insert_id();
    }

    public function get_all()
    {
        return $this->db->get('categories')->result();
    }

    public function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('categories')->row();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('categories', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('categories');
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permission_model extends CI_Model
{

    public function get_permissions($user_id)
    {
        // * Get permissions for a specific user
        return $this->db->get_where('user_permission', ['user_id' => $user_id])->row();
    }

    public function get_all()
    {
        // * Active Record joining user and user_permission
        $this->db->select('user.username, user.id as user_id, user_permission.*');
        $this->db->from('user');
        $this->db->join('user_permission', 'user.id = user_permission.user_id', 'left');
        return $this->db->get()->result();
    }

    public function update_permission($user_id, $data)
    {
        // ! Check if permission record exists
        $exists = $this->db->get_where('user_permission', ['user_id' => $user_id])->row();

        if ($exists) {
            $this->db->where('user_id', $user_id);
            return $this->db->update('user_permission', $data);
        } else {
            $data['user_id'] = $user_id;
            return $this->db->insert('user_permission', $data);
        }
    }
}

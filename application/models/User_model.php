<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{

    public function push($data)
    {
        // * Hash password with bcrypt., slightly different with PASSWORD_DEFAULT < PASSWORD_DEFAULT provides what php recommend based on the current algorithm
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        // ! Ensure role is magang for registration by default if not set
        if (! isset($data['role'])) {
            $data['role'] = 'magang';
        }
        // ! Take id from mysql > AUTO_INCREMENT
        $this->db->insert('user', $data);
        $new_id = $this->db->insert_id();

        // ! Create a row for that user id in user_permission with default permission > all permissions were managed by administrator
        $this->db->insert('user_permission', ['user_id' => $new_id]);

        return $new_id;
    }

    public function pull($data)
    {
        // * Get username only, in one row, password will have it's own hash checking.
        $query = $this->db->get_where('user', ['username' => $data['username']]);
        $user  = $query->row();

        // ! Verify password, compare plain text password to hashed password in db, if suitable. return the user object, if not, return as wrong/false
        if ($user && password_verify($data['password'], $user->password)) {
            return $user;
        }
        return false;
    }

    public function get_all()
    {
        // ! get all 'user' in db as array., for showing all user in manage user table
        return $this->db->get('user')->result();
    }

    public function get_by_id($id)
    {
        // ! take on user id in one row
        return $this->db->get_where('user', ['id' => $id])->row();
    }

    public function update($id, $data)
    {
        // ! update whatever is it inside $data for that id, data inside are depends on what's controller put inside it. e.g: $data = username, password., etc
        $this->db->where('id', $id);
        return $this->db->update('user', $data);
    }

    public function toggle($id, $status)
    {
        $this->db->where('id', $id);
        return $this->db->update('user', ['status' => $status]);
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Permission_model');
        $this->load->model('Audit_log_model');
        
        if ($this->session->userdata('role') !== 'administrator') {
            show_error('Access Denied', 403);
        }
    }

    public function index() {
        $data['users'] = $this->User_model->get_all();
        $this->load->view('admin/users', $data);
    }

    public function create() {
        $this->load->view('admin/user_create');
    }

    public function edit($id = null) {
        $id = require_uri_param($id);

        if ($id == $this->session->userdata('id')) {
            show_error('You cannot edit yourself.', 403);
        }
        $data['user'] = require_user_exists($id);
        $this->load->view('admin/user_edit', $data);
    }

    public function save() {
        $id = $this->input->post('id');
        if ($id && $id == $this->session->userdata('id')) {
            show_error('You cannot edit yourself.', 403);
        }
        $username = trim((string)$this->input->post('username'));
        $email    = trim((string)$this->input->post('email'));
        $role     = trim((string)$this->input->post('role'));
        $password = $this->input->post('password');

        if (empty($username) || empty($email) || empty($role)) {
            $this->session->set_flashdata('error', 'Please fill out all required fields.');
            if ($id) {
                redirect('admin/users/edit/' . $id);
            } else {
                redirect('admin/users/create');
            }
            return;
        }

        if ($id) {
            $current_user = require_user_exists($id);
            $data = [];
            $changed = false;

            if ($username !== $current_user->username) {
                $data['username'] = $username;
                $changed = true;
            }
            if ($email !== $current_user->email) {
                $data['email'] = $email;
                $changed = true;
            }
            if ($role !== $current_user->role) {
                $data['role'] = $role;
                $changed = true;
            }

            if (!empty($password)) {
                $data['password'] = password_hash($password, PASSWORD_BCRYPT);
                $changed = true;
            }

            // Handle Profile Picture Upload
            if (!empty($_FILES['profile_pic']['name'])) {
                $this->load->library('upload');
                $config['upload_path'] = './uploads/profile/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 2048;
                $config['file_name'] = 'profile_' . $id . '_' . time();

                $this->upload->initialize($config);

                if ($this->upload->do_upload('profile_pic')) {
                    $upload_data = $this->upload->data();
                    $data['profile_pic'] = $upload_data['file_name'];
                    $changed = true;

                    // Delete old profile pic
                    if ($current_user->profile_pic && file_exists('./uploads/profile/' . $current_user->profile_pic)) {
                        unlink('./uploads/profile/' . $current_user->profile_pic);
                    }
                }
            }

            if (!$changed) {
                $this->session->set_flashdata('error', 'No changes were made to the user.');
                redirect('admin/users/edit/' . $id);
                return;
            }

            $this->User_model->update($id, $data);
            $this->Audit_log_model->log($this->session->userdata('id'), $id, 'edit user', 'user');
            $this->session->set_flashdata('success', 'User updated successfully.');
        } else {
            $data = [
                'username' => $username,
                'email'    => $email,
                'role'     => $role,
                'password' => $password
            ];
            $new_id = $this->User_model->push($data);

            // Handle Profile Picture Upload for new user
            if (!empty($_FILES['profile_pic']['name'])) {
                $this->load->library('upload');
                $config['upload_path'] = './uploads/profile/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 2048;
                $config['file_name'] = 'profile_' . $new_id . '_' . time();

                $this->upload->initialize($config);

                if ($this->upload->do_upload('profile_pic')) {
                    $upload_data = $this->upload->data();
                    $this->User_model->update($new_id, ['profile_pic' => $upload_data['file_name']]);
                }
            }

            $this->Audit_log_model->log($this->session->userdata('id'), $new_id, 'create user', 'user');
            $this->session->set_flashdata('success', 'User created successfully.');
        }

        redirect('admin/users');
    }

    public function toggle($id = null) {
        $id = require_uri_param($id);

        if ($id == $this->session->userdata('id')) {
            show_error('You cannot disable yourself.', 403);
        }
        $user = require_user_exists($id);
        $new_status = ($user->status == 'active') ? 'nonactive' : 'active';
        $this->User_model->toggle($id, $new_status);
        $this->Audit_log_model->log($this->session->userdata('id'), $id, 'toggle status to ' . $new_status, 'user');
        $this->session->set_flashdata('success', 'User status updated to ' . $new_status . '.');
        redirect('admin/users');
    }
}

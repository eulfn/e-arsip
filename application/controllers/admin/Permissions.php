<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permissions extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Permission_model');
        $this->load->model('Audit_log_model');

        if (! in_array($this->session->userdata('role'), ['administrator', 'staff'])) {
            show_error('Access Denied', 403);
        }
    }

    public function index()
    {
        // * get all users with their permissions, excluding current user to prevent self-editing
        $this->db->select('user.id as user_id, user.username, user.role, user_permission.can_upload, user_permission.can_edit, user_permission.can_delete, user_permission.can_view_audit');
        $this->db->from('user');
        $this->db->join('user_permission', 'user.id = user_permission.user_id', 'left');

        // * exclude current user to prevent self-editing
        $this->db->where('user.id !=', $this->session->userdata('id'));

        // * result will be an array of user permissions
        $data['permissions'] = $this->db->get()->result();
        $this->load->view('admin/permissions', $data);
    }

    public function edit($user_id = null) // * User id here is null as for initializing it
    {
        $user_id = require_uri_param($user_id);

        // ! Prevent self edit (permission)
        if ($user_id == $this->session->userdata('id')) {
            show_error('You cannot edit your own permissions.', 403);
        }

        // ! Prevent self edit
        $data['user'] = require_user_exists($user_id);

        // * Get user permission and initiliaze in $data
        $data['perm'] = $this->Permission_model->get_permissions($user_id);

        // * Load to view with data
        $this->load->view('admin/permission_edit', $data);
    }

    public function save($user_id = null) // * User id here is null as for initializing it
    {
        $user_id = require_uri_param($user_id);

        if ($user_id == $this->session->userdata('id')) {
            show_error('You cannot edit your own permissions.', 403);
        }

        // * Initialize permissions inside data
        // ? Why use ":" ?: To declare each permissions here that has two condition, 1 for true, 0 for false
        $data = [   
            'can_upload'     => $this->input->post('can_upload') ? 1 : 0,
            'can_edit'       => $this->input->post('can_edit') ? 1 : 0,
            'can_delete'     => $this->input->post('can_delete') ? 1 : 0,
            'can_view_audit' => $this->input->post('can_view_audit') ? 1 : 0,
        ];

        // * Initialize current permission on this user selected
        $current_perm = $this->Permission_model->get_permissions($user_id);

        // * IF current permissions has some permission that it got changed from previous state, count as changed
        if ($current_perm) {
            $changed = false;
            if ($data['can_upload'] != $current_perm->can_upload) $changed = true;
            if ($data['can_edit'] != $current_perm->can_edit) $changed = true;
            if ($data['can_delete'] != $current_perm->can_delete) $changed = true;
            if ($data['can_view_audit'] != $current_perm->can_view_audit) $changed = true;

            // * IF there's no changes, make no changes and redirect back to the same page with flashdata
            if (!$changed) {
                $this->session->set_flashdata('error', 'No changes were made to the permissions.');
                redirect('admin/permissions/edit/' . $user_id);
                return;
            }
        }

        // * Update the permission towards tied user and all the data that initialized
        $this->Permission_model->update_permission($user_id, $data);

        
        $target_user = require_user_exists($user_id);
        $target_name = $target_user->username;
        $this->Audit_log_model->log($this->session->userdata('id'), $user_id, 'Permissions updated for user: ' . $target_name, 'permission');
        $this->session->set_flashdata('success', 'Permissions updated for ' . $target_name . '.');

        redirect('admin/permissions');
    }
}

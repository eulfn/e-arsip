<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Audit_logs extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        // ! Check session
        if (! $this->session->userdata('username')) {
            redirect('auth/login');
        }
        // ! Block non-admin/staff
        $role = $this->session->userdata('role');
        if (! in_array($role, ['administrator', 'staff'])) {
            show_error('Access denied. Administrator or Staff only.', 403);
        }
        // * Load model
        $this->load->model('Audit_log_model');
    }

    public function index()
    {
        // * Get all logs
        $data['logs'] = $this->Audit_log_model->get_all();
        $this->load->view('admin/audit_logs', $data);
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Index extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        // ! Redirect to login if no session
        if (! $this->session->userdata('username')) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        // * Load homepage view
        $this->load->model('Permission_model');
        $data['perm'] = $this->Permission_model->get_permissions($this->session->userdata('id'));
        $this->load->view('homepage', $data);
    }
}

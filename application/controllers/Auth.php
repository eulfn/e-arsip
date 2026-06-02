<?php
defined('BASEPATH') or exit('No direct script access allowed');

// ! Uses MY_Controller > Session checking before running anything here
class Auth extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        // ! User_model been auto loaded via @autoload.php (application/config)
    }

    public function index()
    {
        // * Check if already logged in
        if ($this->session->userdata('username')) {
            redirect('homepage');
        }
        $this->load->view('auth/login');
    }

    public function login()
    {
        // * Redirect if already logged in
        if ($this->session->userdata('username')) {
            redirect('homepage');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        // ! Any invalid validation will ask user to correct their form
        if ($this->form_validation->run() == false) {
            if ($this->input->server('REQUEST_METHOD') === 'POST') {
                $this->session->set_flashdata('error', 'Please fill out all required fields.');
                redirect('auth/login');
                return;
            }
            $this->load->view('auth/login');

        // * if validation pass, get username and password from input to check
        } else {
            $data = [
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password'),
            ];

            // * Call Model
            $user = $this->User_model->pull($data);

            if ($user) {
                // ! Check if account is active
                if ($user->status !== 'active') {
                    $this->session->set_flashdata('error', 'Your account has been disabled.');
                    redirect('auth/login');
                }

                // ! Set Session data with the data included below
                $session_data = [
                    'username' => $user->username,
                    'email'    => $user->email,
                    'role'     => $user->role,
                    'id'       => $user->id,
                    'profile_pic' => $user->profile_pic,
                ];
                // ! update session and redirect to homepage
                $this->session->set_userdata($session_data);
                $this->session->set_flashdata('success', 'Welcome back, ' . $user->username . '!');
                redirect('homepage');
            } else {
                // ! data invalid, reload the page
                $this->session->set_flashdata('error', 'Invalid username or password');
                redirect('auth/login');
            }
        }
    }

    public function register()
    {
        // * Redirect if already logged in
        if ($this->session->userdata('username')) {
            redirect('homepage');
        }

        // ! check if the username was used or not
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[user.username]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('password', 'Password', 'required');

        // ! if there's any invalid in validation, reload with warning
        if ($this->form_validation->run() == false) {
            if ($this->input->server('REQUEST_METHOD') === 'POST') {
                $this->session->set_flashdata('error', validation_errors(' ', ' '));
                redirect('auth/register');
                return;
            }
            $this->load->view('auth/register');
        } else {
            // ! prepare the data
            $data = [
                'username' => $this->input->post('username'),
                'email'    => $this->input->post('email'),
                'password' => $this->input->post('password'),
                'role'     => 'magang',
            ];

            // * Call Model
            // ! upload the user account data
            $new_id = $this->User_model->push($data);
            if ($new_id) {
                // Handle Profile Picture Upload
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

                $this->session->set_flashdata('success', 'Registration successful! You are now logged in.');
                // ! Auto login after register
                $user         = $this->db->get_where('user', ['username' => $data['username']])->row();
                $session_data = [
                    'username' => $user->username,
                    'email'    => $user->email,
                    'role'     => $user->role,
                    'id'       => $user->id,
                    'profile_pic' => $user->profile_pic,
                ];
                $this->session->set_userdata($session_data);
                redirect('homepage');
            } else {
                // ! invalid name, email, password, etc,. reloads the page with flashdata
                redirect('auth/register');
            }
        }
    }

    public function logout()
    {
        // ! Destroy Session
        $username = $this->session->userdata('username');
        // ! Delete provided items and redirect to login
        $this->session->unset_userdata(['username', 'email', 'role', 'id']);
        $this->session->set_flashdata('success', 'You have been logged out. Goodbye, ' . $username . '!');
        redirect('auth/login');
    }
}

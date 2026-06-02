<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    // * MY_Controller is a part of CI3 built in controller, so it loads model automatically
    public function __construct()
    {
        parent::__construct();

        // ! Refresh session data if logged in
        if ($this->session->userdata('id')) {
            $user_id = $this->session->userdata('id'); // get the current user id

            // * Get latest data from database
            $user = $this->db->get_where('user', ['id' => $user_id])->row(); // get the user data row using the provided id

            if ($user) {
                // ! Update session with latest role by refreshing it
                $this->session->set_userdata('role', $user->role);
                $this->session->set_userdata('username', $user->username);
                $this->session->set_userdata('email', $user->email);
                $this->session->set_userdata('profile_pic', $user->profile_pic);
            } else {
                // User no longer exists, logout
                $this->session->sess_destroy();
                redirect('auth/login');
            }
        }
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // * Load user model & Upload library
        // ? Might be confusing but, some of module were inside User_model , better check's every week
        $this->load->model('User_model');
        $this->load->library('upload');


        // * if this current user doesn't have a "id" in their session, redirect to login
        if (!$this->session->userdata('id')) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        // * Define $user_id as user id 
        $user_id = $this->session->userdata('id');

        // * Get one user row using get_by_id module in user model
        $data['user'] = $this->User_model->get_by_id($user_id);
        
        // * Rename 'title' as 'My profile' then load profile index page with 2 of those data's
        $data['title'] = 'My Profile';
        $this->load->view('admin/profile', $data);
    }

    public function update()
    {
        // * Define $user_id as user id
        $user_id = $this->session->userdata('id');

        // * Get username, email, password from user's input and update it with the new username
        // ? 'true' here refers to a text filtering to prevent suspicious script to be executed in the input
        // ? 'true' doesn't block the input, it filter's it
        $username = $this->input->post('username', true);
        $email = $this->input->post('email', true);
        $password = $this->input->post('password', true);

        // * Get current user row using get_by_id module in user model
        // ? why we used 'id' instead of username? cause, username it self can be changed and id can't be changed, it's a primary key, a FIX number in SQL database.
        $current_user = $this->User_model->get_by_id($user_id);
        
        // * Define update data as an empty array
        $update_data = [];

        // * Initialize change value as false bool
        $changed = false;

        // * if current user username was not the same, then update ONLY username, changing "changed" value to true
        if ($username !== $current_user->username) {
            $update_data['username'] = $username;
            $changed = true;
        }

        // * if current user email was not the same, then update ONLY email, changing "changed" value to true
        if ($email !== $current_user->email) {
            $update_data['email'] = $email;
            $changed = true;
        }

        // * if not empty, update password by first hashing password using the bcrypt algorithm, changing "changed" value to true
        // ? what's bcrypt? bcrypt is a hash algorithm compatible with crypt
        // ? what's salting? when different user has the same password, this where salting comes in, by adding "salt" to the hashed password so it would prevent same hashed password in different user's
        if (!empty($password)) {
            $update_data['password'] = password_hash($password, PASSWORD_BCRYPT);
            $changed = true;
        }

        // * Handler for profile picture
        // * if wasn't empty, update profile picture with config below, specifically in profile_pic fields
        if (!empty($_FILES['profile_pic']['name'])) {
            // ! where its gonna be saved
            $config['upload_path'] = './uploads/profile/';
            // ! allowed type
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            // ! max size
            $config['max_size'] = 2048; // 2MB
            // ! naming of the file
            $config['file_name'] = 'profile_' . $user_id . '_' . time();

            // * initialize configs using config that is provided
            $this->upload->initialize($config);

            // * if upload successful, do upload on table user, specifically in profile_pic fields
            if ($this->upload->do_upload('profile_pic')) {
                // * Define upload_data as current upload data
                $upload_data = $this->upload->data();

                // * Define update_data inside 'profile_pic' fields as uploaded 'file_name' data
                $update_data['profile_pic'] = $upload_data['file_name'];
                $changed = true;

                // * Delete old profile pic
                // * if current user profile picture the file it self exists AND current user has profile picture, then, unlink the file itself and current user profile pict
                if ($current_user->profile_pic && file_exists('./uploads/profile/' . $current_user->profile_pic)) {
                    unlink('./uploads/profile/' . $current_user->profile_pic);
                }
            } else {
                // * All fields were needed in oder to update, if not, redirect to profile and shows what's error, return.
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('admin/profile');
                return;
            }
        }

        // * IF there's no change
        if (!$changed) {
            $this->session->set_flashdata('error', 'No changes were made to your profile.');
            redirect('admin/profile');
            return;
        }

        // * Trace all the activity in log
        $this->load->model('Audit_log_model');
        // * If there's a user data got updated., add to log as 'profile' for it's module
        if ($this->User_model->update($user_id, $update_data)) {
            // * Log to audit logs for editing our own profile
            $this->Audit_log_model->log($user_id, $user_id, 'updated own profile', 'profile');
            // * Alert to show
            $this->session->set_flashdata('success', 'Profile updated successfully.');
        } else {
            // * if fail
            $this->session->set_flashdata('error', 'Failed to update profile.');
        }
        
        // * Redirect to the same page
        redirect('admin/profile');
    }
}

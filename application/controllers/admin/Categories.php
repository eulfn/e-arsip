<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Categories extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        // * Loads 2 important models, audit model to tracks the activity and put in logs
        $this->load->model('Category_model');
        $this->load->model('Audit_log_model');

        // * Check if current user logged in or not
        if (! $this->session->userdata('id')) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        // * Get all categories stored in database, load it into view
        // ? Where is this linked: Category_model
        $data['categories'] = $this->Category_model->get_all();
        $this->load->view('admin/categories', $data);
    }

    public function create()
    {
        // * Load child page from category page > create page
        $this->load->view('admin/category_create');
    }

    public function edit($id = null)
    {
        $id = require_uri_param($id);

        // * Get one category row using it's id, load it to view
        // ? Where is this linked: Category_model
        $data['category'] = $this->Category_model->get_by_id($id);

        // ! category_edit IS a view file name.
        $this->load->view('admin/category_edit', $data);
    }

    // ? Save or aka Update from edit, different naming on this cuz..
    public function save()
    {
        // * Get id from user input
        $id   = $this->input->post('id');

        // * Trim Spaces from beginning to end
        $name = trim((string)$this->input->post('name'));

        // * IF name is empty
        if (empty($name)) {
            // ! Alert
            $this->session->set_flashdata('error', 'Please fill out the Category Name field.');
            if ($id) {
                // ! url's below are managed in route.php
                // * redirect to main page of editing categories
                   redirect('admin/categories/edit/' . $id);
            } else {
                // * redirect to main page of create categories
                redirect('admin/categories/create');
            }
            return;
        }

        // * Define category name data to name variable
        $data = ['name' => $name];

        // ! Keep in mind the thing that can be edited inside category itself is the name of that category it self
        if ($id) {
            // * Define current category by get the current category row by id using get_by_id
            // ? Where to find get_by_id: Category_model
            $current_cat = $this->Category_model->get_by_id($id);

            // * IF current name was the same as same stored name in database, then count as no changes made
            if ($name === $current_cat->name) {
                $this->session->set_flashdata('error', 'No changes were made to the category.');

                // * Redirect to the same edit page with user's provided id, then return
                redirect('admin/categories/edit/' . $id);
                return;
            }

            // * Update the provided category based on id and data, data here was defined as name
            $this->Category_model->update($id, $data);

            // * Update log by providing user id that do an action, id of the category, action for editing category, module of category 
            $this->Audit_log_model->log($this->session->userdata('id'), $id, 'edit category', 'category');
            
            // ! Alert
            $this->session->set_flashdata('success', 'Category updated successfully.');
        } else {
            // * Define new_id as uploaded data
            $new_id = $this->Category_model->push($data);

            // * Log the action providing user id that create the category, id of the new category, action for creating category, module of category
            $this->Audit_log_model->log($this->session->userdata('id'), $new_id, 'create category', 'category');

            // ! Alert
            $this->session->set_flashdata('success', 'Category created successfully.');
        }

        // * Redirect to all categories listed
        redirect('admin/categories');
    }

    public function delete($id = null)
    {
        $id = require_uri_param($id);

        // * Delete provided category via id
        $this->Category_model->delete($id);

        // * Log action to audit log providing the current user id, the deleted category id, action for deleting category, module of category
        $this->Audit_log_model->log($this->session->userdata('id'), $id, 'delete category', 'category');

        // ! Alert
        $this->session->set_flashdata('success', 'Category deleted successfully.');

        // * Redirect to all categories listed
        redirect('admin/categories');
    }
}

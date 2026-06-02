<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Documents extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        // * Load Document & Permission model with audit log model
        // ? Why? They're tied to each other, some documents were customizable
        $this->load->model('Document_model');
        $this->load->model('Permission_model');
        $this->load->model('Audit_log_model');

        // * check current session if they had id, if not, go to login
        if (! $this->session->userdata('id')) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        // * Defining role with current session role
        $role = $this->session->userdata('role');

        // * Defining all_documents by calling get_all method in document_model
        $all_documents = $this->Document_model->get_all();

        if ($role === 'magang') {
            // * Intern only sees active documents
            // * Filters out document to only selects and shows 'active' documents
            // * First define all_document inside documents, filters it using 'array_filter' and calls all_document as array, make a callable function called 'doc'
            // * Second, return 'doc' (filtered all documents) status online
            // ? What's array filter? a function used to filter an array inside of an data
            $documents = array_filter($all_documents, function($doc) {
                return $doc->status === 'active';
            });
            // * Return all the 'active' document only
            // ? Why don't type return here?: 'array_values' it self already returning the re-indexed arrays
            // ? What's the indexed array?: All the active document itself
            $data['documents'] = array_values($documents);
        } else {
            // ! Administrator and Staff see all documents (active & nonactive)
            $data['documents'] = $all_documents;
        }

        // * Initialize 'perm' data to strictly set permission on user current session
        // * This also going to filters out if they can edit those documents or not
        $data['perm']      = $this->Permission_model->get_permissions($this->session->userdata('id'));

        // * Load documents view with data permissions loaded
        $this->load->view('admin/documents', $data);
    }

    public function upload()
    {
        // * keep in mind, using 'result' here is to select all categories to later selected in upload/edit page, not just selecting one row
        // * Initialize categories as data, get categories in db and show all later in the category selection
        // ? Why use result instead of row?: result are showing all array, not just showing one row, or. in this scenario, 'categories' itself
        $data['categories'] = $this->db->get('categories')->result();

        // * load the result in document_upload page with the initialized categories data
        $this->load->view('admin/document_upload', $data);
    }

    public function edit($id = null)
    {
        $id = require_uri_param($id);

        // * Current document by id
        $data['document']   = $this->Document_model->get_by_id($id);
        
        // * All categories From db
        $data['categories'] = $this->db->get('categories')->result();

        // * Load document edit page view comes with initialized data
        $this->load->view('admin/document_edit', $data);
    }

    public function save()
    {
        // * Define 'id' & 'user_id' by user input
        $id      = $this->input->post('id');
        $user_id = $this->session->userdata('id');

        // * Trim whatever comes at the start of it and the end of it
        // ? what's and why is 'trim' ?: to trim out kind of 'space' or empty string at the start or at the end of the title it self, making it look cleaner
        $title = trim((string)$this->input->post('title'));

        // * IF title was empty, Alert user to fill the title field
        if (empty($title)) {
            $this->session->set_flashdata('error', 'Please fill out the Title field.');
            if ($id) {
                // * redirect to the same page with the same id in it
                redirect('admin/documents/edit/' . $id);
            } else {
                // * Upload the provided Documents
                redirect('admin/documents/upload');
            }
            // ! stop, nothing to do
            return;
        }

        // * IF there's no id and empty document & name
        if (!$id && empty($_FILES['document']['name'])) {

            // ! Alert
            $this->session->set_flashdata('error', 'Please select a Document File to upload.');

            // * Redirect to the same page & return
            redirect('admin/documents/upload');
            return;
        }

        // * Initialize title & description from user input as data
        $data = [
            'title'       => $title,
            'description' => $this->input->post('description'),
        ];

        // * Define category id from user input
        $category_id = $this->input->post('category_id');

        // * IF wasn't empty, initialize 'document' and 'name' as for the new name on document
        if (! empty($_FILES['document']['name'])) {
            // ! Path
            $config['upload_path']   = './uploads/';

            // ! Allowed type
            $config['allowed_types'] = 'pdf|doc|docx|zip';

            // ! File extension
            $ext                     = pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION);

            // ! Initialize new file name
            $new_name                = 'arsip_' . date('Y') . '_' . substr(uniqid(), -7) . '.' . $ext;

            // ! New file name
            $config['file_name']     = $new_name;

            // * Load upload library
            $this->load->library('upload', $config);

            // * IF user saves, do upload in document fields
            if ($this->upload->do_upload('document')) {
                $data['filename'] = $new_name; // * Saves with new name
                $data['path']     = 'uploads/' . $new_name; // * Saves on path with new name
            }
        }

        if ($id) {
            
            // * Get current doc by 'get_by_id' in Document_model
            $current_doc = $this->Document_model->get_by_id($id);
            // * Initialize 'changed' value state to false as default
            $changed = false;

            // * IF current title isn't the same as the title, count as changes
            if ($title !== $current_doc->title) $changed = true;

            // * IF current description ins't the same as description, count as changes
            if ($this->input->post('description') !== $current_doc->description) $changed = true;

            // * IF current category isn't the same as category, count as changes
            if ($category_id !== $current_doc->category_id) $changed = true;

            // * IF 'document' & 'name' inside $_FILES isn't empty, count as changes
            if (!empty($_FILES['document']['name'])) $changed = true;

            // * IF no changes, count as no changes were made, then redirect to the same page with alert
            if (!$changed) {
                $this->session->set_flashdata('error', 'No changes were made to the document.');
                redirect('admin/documents/edit/' . $id);

                // ! Stop, nothing to do
                return;
            }

            // * Update 'id', 'data', 'category_id' as argument / to update
            $this->Document_model->update($id, $data, $category_id);

            // * Tracks Logs on 'user_id', 'id', 'edit', 'document' as argument / to track in logs
            $this->Audit_log_model->log($user_id, $id, 'edit', 'document');

            // ! Alert
            $this->session->set_flashdata('success', 'Document updated successfully.');
        } else {
            // * Initialize 'uploaded_by' as data using 'user_id'
            $data['uploaded_by'] = $user_id;

            // * Upload's the document with category id as data inside
            $doc_id              = $this->Document_model->push($data, $category_id);

            // * Tracks Log by user_id, doc_id, upload as action, document as for module
            $this->Audit_log_model->log($user_id, $doc_id, 'upload', 'document');

            // ! Alert
            $this->session->set_flashdata('success', 'Document uploaded successfully.');
        }

        // * Redirect to document list page
        redirect('admin/documents');
    }

    public function toggle($id = null)
    {
        $id = require_uri_param($id);

        // * Get the selected row by
        $doc        = $this->Document_model->get_by_id($id);

        // * Changes document status as for active & nonactive
        $new_status = ($doc->status == 'active') ? 'nonactive' : 'active';

        // * Toggle new status of the provided document., with 'id' & 'new_status'
        $this->Document_model->toggle($id, $new_status);

        // * Track logs in the current session id, tracking the id who's toggling the status of document with the new status it made
        $this->Audit_log_model->log($this->session->userdata('id'), $id, 'toggle status to ' . $new_status, 'document');

        // ! Alert tied with new status & redirect to document list
        $this->session->set_flashdata('success', 'Document status updated to ' . $new_status . '.');
        redirect('admin/documents');
    }

    public function download($id = null) // ? originally named download, modified some logic to just show the file and separate download & view
    {
        $id = require_uri_param($id);

        // * Get that document by id as row, not all document
        $doc = $this->Document_model->get_by_id($id);

        // * IF it wasn't exist, 404
        if (!$doc) {
            show_404();
        }

        // * Define role as current user session as role
        $role = $this->session->userdata('role');

        // * RBAC Check: If document is nonactive, only administrator and staff can access/download it
        // * Magang isn't allowed to download
        if ($doc->status === 'nonactive' && $role === 'magang') {
            show_error('Access Denied: You do not have permission to access this document.', 403);
        }

        // ? what's FCPATH?: an absolute path for project root
        // ? e.,g:  $data['path']     = 'uploads/' . $file_name; // * Saves on path with new name
        $filepath = FCPATH . $doc->path;
        if (!file_exists($filepath)) {
            show_error('File not found.', 404);
        }

        // * Log download event in Audit Logs for a traceable audit logs
        $user_id = $this->session->userdata('id');
        $this->Audit_log_model->log($user_id, $doc->id, 'view', 'document');

        // * Serve file inline (browser will display PDFs instead of downloading)
        $mime = mime_content_type($filepath); // ? to figure out what kind of file is being used
        header('Content-Type: ' . $mime); // ? let the browser handle the file
        header('Content-Disposition: inline; filename="' . basename($filepath) . '"'); // ? display the pdf and its file name, not downloading it
        header('Content-Transfer-Encoding: binary'); // ? sent as raw binary data
        header('Accept-Ranges: bytes'); // ? let the browser jumps between pages, e.g: page/6/
        header('Content-Length: ' . filesize($filepath)); // ? tell the browser how big is the file size so it could show progress bar., etc.
        readfile($filepath); // ? reads/open the file instead of downloading it
        exit;
    }

    public function force_download($id = null) // ? download file
    {
        $id = require_uri_param($id);

        // ! if the doc was not valid
        $doc = $this->Document_model->get_by_id($id);
        if (!$doc) {
            show_404();
        }

        // * Initialize user role
        $role = $this->session->userdata('role');

        // * RBAC Check: If document is nonactive, only administrator and staff can access/download it
        if ($doc->status === 'nonactive' && $role === 'magang') {
            show_error('Access Denied: You do not have permission to access this document.', 403);
        }

        $filepath = FCPATH . $doc->path;
        if (!file_exists($filepath)) {
            show_error('File not found.', 404);
        }

        // * Log download event in Audit Logs for traceability
        $user_id = $this->session->userdata('id');
        $this->Audit_log_model->log($user_id, $doc->id, 'download', 'document');

        $this->load->helper('download');
        force_download($filepath, NULL);
    }
}

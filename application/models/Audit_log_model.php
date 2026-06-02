<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Audit_log_model extends CI_Model
{

    public function log($user_id, $reference_id, $action, $module)
    {
        // * Insert log entry
        $data = [
            'user_id' => $user_id,
            'action'  => $action,
            'module'  => $module,
        ];

        // ! Only set documents_id if the module is actually a document, otherwise leave NULL
        if ($module === 'document' && $reference_id) {
            $data['documents_id'] = $reference_id;
        } else {
            $data['documents_id'] = null;
            // * Preserve the reference ID in the action description
            if ($reference_id) {
                $data['action'] .= " (ID: $reference_id)";
            }
        }

        return $this->db->insert('audit_logs', $data);
    }

    public function get_all()
    {
        // * Active Record joining audit_logs and user
        $this->db->select('audit_logs.*, user.username');
        $this->db->from('audit_logs');
        $this->db->join('user', 'audit_logs.user_id = user.id');
        $this->db->order_by('audit_logs.created_at', 'DESC');
        return $this->db->get()->result();
    }
}

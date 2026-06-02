<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('require_user_exists')) {
    // * Checks if a user exists by ID
    // ! Throws a 404 error and halts execution if not found
    // ? Why?: To prevent downstream errors and unify manual checks
    function require_user_exists($user_id) {
        // * Get CI instance
        $CI =& get_instance();
        
        // * Get user by id
        $user = $CI->db->get_where('user', ['id' => $user_id])->row();
        
        // * IF user not found
        if (!$user) {
            // ! Alert user not found
            show_error('User not found.', 404);
        }
        
        // * Return the user object
        return $user;
    }
}

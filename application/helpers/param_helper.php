<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('require_uri_param')) {
    // * Checks if a parameter exists in the URI (is not null or empty)
    // ! If missing, throws a 400 error to prevent ArgumentCountError in controllers
    // ? Why?: To gracefully handle URL without ID parameters
    function require_uri_param($param) {
        // * IF param is null or empty string
        if ($param === null || trim((string)$param) === '') {
            // ! Alert missing parameter
            show_error('Missing required parameter in the URL.', 400);
        }
        
        // * Return the original parameter
        return $param;
    }
}

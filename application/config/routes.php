<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Auth/login';
$route['homepage'] = 'index';
$route['admin'] = 'index';
$route['login'] = 'Auth/login';
$route['register'] = 'Auth/register';
$route['logout'] = 'Auth/logout';

// ! (:num) represent to a wildcard.
// * all the directed routes here were used for shorten the url - valid url goes here and shortened

// Documents Routes
$route['admin/documents/upload'] = 'admin/documents/upload';
$route['admin/documents/index'] = 'admin/documents/index';
$route['admin/documents/toggle/(:num)'] = 'admin/documents/toggle/$1';
$route['admin/documents/edit/(:num)'] = 'admin/documents/edit/$1';
$route['admin/documents/download/(:num)'] = 'admin/documents/download/$1';

$route['admin/logs'] = 'admin/audit_logs/index';

// Permissions Routes
$route['admin/permissions/index'] = 'admin/permissions/index';
$route['admin/permissions/edit/(:num)'] = 'admin/permissions/edit/$1';
$route['admin/permissions/update/(:num)'] = 'admin/permissions/update/$1';

// Categories Routes
$route['admin/categories/index'] = 'admin/categories/index';
$route['admin/categories/create'] = 'admin/categories/create';
$route['admin/categories/save'] = 'admin/categories/save';
$route['admin/categories/edit/(:num)'] = 'admin/categories/edit/$1';
$route['admin/categories/delete/(:num)'] = 'admin/categories/delete/$1';

// Users Routes
$route['admin/users/index'] = 'admin/users/index';
$route['admin/users/create'] = 'admin/users/create';
$route['admin/users/save'] = 'admin/users/save';
$route['admin/users/edit/(:num)'] = 'admin/users/edit/$1';
$route['admin/users/toggle/(:num)'] = 'admin/users/toggle/$1';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

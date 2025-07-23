<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
// $route['default_controller'] = 'admin/login';
$route['default_controller'] = 'DashboardController';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// DashboardController
$route['admin/dashboard'] = 'DashboardController';

//AdminLogin
$route['admin/login'] = 'AdminController/login';
$route['admin/login-admin'] = 'AdminController/login_admin';
$route['admin/logout'] = 'AdminController/logout';

// AdminProfile
$route['admin/profile'] = 'AdminController/user_profile';
$route['admin/admin-edit'] = 'AdminController/admin_edit';
$route['admin/change'] = 'AdminController/change_password';

// Users
$route['admin/user'] = 'AdminController/users';
$route['admin/user-edit/(:any)'] = 'AdminController/user_edit/$1';
$route['admin/update-user'] = 'AdminController/update_user';
$route['admin/delete-user/(:any)'] = 'AdminController/delete_user/$1';

// All Post
$route['admin/all-post'] = 'AdminController/all_post';
$route['admin-all-post/(:any)'] = 'AdminController/all_post/$1';
$route['admin-all-post'] = 'AdminController/all_post';
$route['admin/delete-post/(:any)'] = 'AdminController/delete_post/$1';


// Trending Post
$route['admin/trending-post'] = 'AdminController/trending_post';

// All Comment
$route['admin/comment'] = 'AdminController/comment';
$route['admin/comment-edit/(:any)'] = 'AdminController/comment_edit/$1';
$route['admin/update-comment'] = 'AdminController/update_comment';
$route['admin/delete-comment/(:any)'] = 'AdminController/delete_comment/$1';

// Like
$route['admin/like'] = 'AdminController/like';
$route['admin/delete-like/(:any)'] = 'AdminController/delete_like/$1';

// Followers/Following
$route['admin/followers-following'] = 'AdminController/followers_following';

// Comment Report
$route['admin/comment-report'] = 'AdminController/comment_report';

// Interests
$route['admin/interests'] = 'AdminController/interests';
$route['admin/create-interest'] = 'AdminController/create_interest';
$route['admin/add-interest'] = 'AdminController/add_interest';
$route['admin/interest-edit/(:any)'] = 'AdminController/interest_edit/$1';
$route['admin/update-interest'] = 'AdminController/update_interest';

//Notifications
$route['admin/push-notifications'] = 'AdminController/push_notifications';
$route['admin/admin-notifications'] = 'AdminController/admin_notifications';
// $route['admin/user-notifications'] = 'AdminController/user_notifications';
$route['admin/send-user-notifications'] = 'AdminController/send_user_notifications';
$route['admin/delete-notification/(:any)'] = 'AdminController/delete_notification/$1';

// Report Post
$route['admin/report-post'] = 'AdminController/report_post';

// Report User
$route['admin/report-users'] = 'AdminController/report_users';

//settings
$route['admin/settings'] = 'AdminController/settings';
$route['admin/chenge-settings'] = 'AdminController/chenge_settings';

//story
$route['admin/newstorys'] = 'AdminController/newstorys';
$route['admin/paststorys'] = 'AdminController/paststorys';
$route['admin/delete-story/(:any)'] = 'AdminController/delete_story/$1';

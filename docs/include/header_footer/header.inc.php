<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2004 by Greg Gay, Joel Kronenberg & Heidi Hazelton*/
/* Adaptive Technology Resource Centre / University of Toronto			*/
/* http://atutor.ca														*/
/*																		*/
/* This program is free software. You can redistribute it and/or		*/
/* modify it under the terms of the GNU General Public License			*/
/* as published by the Free Software Foundation.						*/
/************************************************************************/
if (!defined('AT_INCLUDE_PATH')) { exit; }

global $available_languages;
global $_rtl_languages;
global $page;
global $savant;
global $errors, $onload;
global $_base_href;
global $_user_location;

$savant->assign('tmpl_lang',	$available_languages[$_SESSION['lang']][2]);
$savant->assign('tmpl_title',	stripslashes(SITE_NAME));
$savant->assign('tmpl_charset', $available_languages[$_SESSION['lang']][1]);
$savant->assign('tmpl_base_href', $_base_href);


if (in_array($_SESSION['lang'], $_rtl_languages)) {
	$savant->assign('tmpl_rtl_css', '<link rel="stylesheet" href="'.$_base_path.'rtl.css" type="text/css" />');
} else {
	$savant->assign('tmpl_rtl_css', '');
}

if (!isset($errors) && $onload) {
	$savant->assign('tmpl_onload', $onload);
}

$savant->assign('tmpl_page', $page);

header('Content-Type: text/html; charset='.$available_languages[$_SESSION['lang']][1]);

if ($_user_location == 'public') {
	/* the public section */
	if (defined('HOME_URL') && HOME_URL) {
		$nav[] = array('name' => _AT('home'),  'url' => HOME_URL);
	}

	$nav[] = array('name' => _AT('register'),          'url' => 'registration.php',     'page' => 'register');
	$nav[] = array('name' => _AT('browse_courses'),    'url' => 'browse.php',           'page' => 'browse');
	$nav[] = array('name' => _AT('login'),             'url' => 'login.php',            'page' => 'login');
	$nav[] = array('name' => _AT('password_reminder'), 'url' => 'password_reminder.php','page' => 'password_reminder');

	$savant->assign('tmpl_nav', $nav);
	$savant->assign('tmpl_section', '[not sure of the section name?]');

} else if ($_user_location == 'users') {
	/* the /users/ section */

	

	$nav[] = array('name' => _AT('home'),           'url' => 'users/index.php',           'page' => 'home');
	$nav[] = array('name' => _AT('profile'),        'url' => 'users/edit.php',            'page' => 'profile');
	$nav[] = array('name' => _AT('browse_courses'), 'url' => 'users/browse.php',          'page' => 'browse_courses');
	$nav[] = array('name' => _AT('inbox'),          'url' => 'users/inbox.php',           'page' => 'inbox');
	if (get_instructor_status($_SESSION['member_id'])) {
		$nav[] = array('name' => _AT('create_course'), 'url' => 'users/create_course.php', 'page' => 'create_course');
	}
	$nav[] = array('name' => _AT('logout'), 'url' => 'logout.php', 'page' => 'logout');

	$savant->assign('tmpl_nav', $nav);
	$savant->assign('tmpl_section', _AT('control_centre'));

} else if ($_user_location == 'admin') {
	/* the /admin/ section */

	$nav[] = array('name' => _AT('home'),                   'url' => 'admin/index.php',            'page' => 'home');
	$nav[] = array('name' => _AT('users'),                  'url' => 'admin/users.php',            'page' => 'users');
	$nav[] = array('name' => _AT('courses'),                'url' => 'admin/courses.php',          'page' => 'courses');
	$nav[] = array('name' => _AT('cats_course_categories'), 'url' => 'admin/course_categories.php','page' => 'course_cats');
	$nav[] = array('name' => _AT('language'),               'url' => 'admin/language.php',         'page' => 'language');
	$nav[] = array('name' => _AT('server_configuration'),   'url' => 'admin/config_info.php',      'page' => 'server_config');
	$nav[] = array('name' => _AT('logout'),                 'url' => 'logout.php',                 'page' => 'logout');

	$savant->assign('tmpl_nav', $nav);
	$savant->assign('tmpl_section', _AT('administration'));
} else {

	$nav[] = array('name' => _AT('home'),                 'url' => $_base_path . 'index.php',             'page' => 'home',        'id' => 'home-nav');
	$nav[] = array('name' => _AT('tools'),                'url' => $_base_path . 'tools/index.php',       'page' => 'tools',       'id' => 'tools-nav');
	$nav[] = array('name' => _AT('resources'),            'url' => $_base_path . 'resources/index.php',   'page' => 'resources',   'id' => 'resources-nav');
	$nav[] = array('name' => _AT('discussions'),          'url' => $_base_path . 'discussions/index.php', 'page' => 'discussions', 'id' => 'discussions-nav');
	$nav[] = array('name' => _AT('help'),                 'url' => $_base_path . 'help/index.php',        'page' => 'help',        'id' => 'help-nav');
	$nav[] = array('name' => 'jump_menu');
	$nav[] = array('name' => _AT('logout'),                 'url' => 'logout.php',        'page' => 'logout');

	//$user_nav[] = array('name' => _AT('logout'),        'url' => $_base_path . 'logout.php',             'page' => 'home',        'id' => 'logout-user-nav');
	$user_nav[] = array('name' => _AT('sitemap'),       'url' => $_base_path . 'tools/sitemap/index.php',       'page' => 'tools',       'id' => 'sitemap-user-nav');
	$user_nav[] = array('name' => _AT('preferences'),   'url' => $_base_path . 'tools/preferences.php',   'page' => 'preferences',   'id' => 'preferences-user-nav');
	$user_nav[] = array('name' => _AT('inbox'),         'url' => $_base_path . 'inbox.php', 'page' => 'inbox', 'id' => 'inbox-user-nav');

	if (show_pen()) {
		if ($_SESSION['prefs']['PREF_EDIT'] == 0) {
			$user_nav[] = array('name' => _AT('enable_editor'),'url' =>  $_my_uri.'enable='.PREF_EDIT,        'page' => '',        'id' => 'enable-editor-user-nav');
		} else {
			$user_nav[] = array('name' => _AT('disable_editor'),'url' => $_my_uri.'disable='.PREF_EDIT,        'page' => '',        'id' => 'disable-editor-user-nav');
		}
	}
	$savant->assign('tmpl_nav', $nav);
	$savant->assign('tmpl_user_nav', $user_nav);
	$savant->assign('tmpl_section', '');
}

$savant->display('include/header_footer/header.tmpl.php');

?>
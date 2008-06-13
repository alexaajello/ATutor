<?php
/****************************************************************************/
/* ATutor																	*/
/****************************************************************************/
/* Copyright (c) 2002-2008 by Greg Gay, Joel Kronenberg & Heidi Hazelton	*/
/* Adaptive Technology Resource Centre / University of Toronto				*/
/* http://atutor.ca															*/
/*																			*/
/* This program is free software. You can redistribute it and/or			*/
/* modify it under the terms of the GNU General Public License				*/
/* as published by the Free Software Foundation.							*/
/****************************************************************************/
// $Id: certificate_delete.php 7208 2008-02-20 16:07:24Z cindy $

$page = 'gradebook';

define('AT_INCLUDE_PATH', '../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
authenticate(AT_PRIV_GRADEBOOK);

if (isset($_POST['submit_no'])) {
	$msg->addFeedback('CANCELLED');
	header('Location: gradebook_tests.php');
	exit;
} else if (isset($_POST['submit_yes'])) {
	/* delete has been confirmed, delete this category */
	$gradebook_test_id	= intval($_POST['gradebook_test_id']);

	$sql = "DELETE FROM ".TABLE_PREFIX."gradebook_tests WHERE gradebook_test_id=$gradebook_test_id";
	$result = mysql_query($sql, $db) or die(mysql_error());

	$sql = "DELETE FROM ".TABLE_PREFIX."gradebook_detail WHERE gradebook_test_id=$gradebook_test_id";
	$result = mysql_query($sql, $db) or die(mysql_error());

	$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
	header('Location: gradebook_tests.php');
	exit;
}

require(AT_INCLUDE_PATH.'header.inc.php');

$_GET['gradebook_test_id'] = intval($_GET['gradebook_test_id']); 

$sql = "SELECT g.*, t.title from ".TABLE_PREFIX."gradebook_tests g LEFT JOIN ".TABLE_PREFIX."tests t ON (g.test_id = t.test_id) WHERE g.gradebook_test_id = ".$_GET['gradebook_test_id'];
$result = mysql_query($sql,$db) or die(mysql_error());

if (mysql_num_rows($result) == 0) {
	$msg->printErrors('ITEM_NOT_FOUND');
} else {
	$row = mysql_fetch_assoc($result);
	
	if ($row["test_id"]<>0) $title = $row["title"];
	else $title = $row["title"];
	
	$hidden_vars['title']= $title;
	$hidden_vars['gradebook_test_id']	= $row['gradebook_test_id'];

	$confirm = array('DELETE_TEST_FROM_GRADEBOOK', $title);
	$msg->addConfirm($confirm, $hidden_vars);
	
	$msg->printConfirm();
}

require(AT_INCLUDE_PATH.'footer.inc.php');

?>
<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2003 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
if (!defined('AT_INCLUDE_PATH')) { exit; }

if (!$_SESSION['valid_user']) {
	$infos[]=AT_INFOS_MSG_SEND_LOGIN;
	print_infos($infos);
	return;
}


if ($_GET['delete']) {
	$_GET['delete'] = intval($_GET['delete']);

	if($result = mysql_query("DELETE FROM ".TABLE_PREFIX."messages WHERE to_member_id=$_SESSION[member_id] AND message_id=$_GET[delete]",$db)){
		$feedback[] = AT_FEEDBACK_MSG_DELETED;
	}

	$_GET['delete'] = '';
}

print_feedback($feedback);
	if($_SESSION['course_id']){
		echo '<h2>';
		if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 2) {
			echo '<img src="images/icons/default/square-large-discussions.gif" width="42" height="38" border="0" alt="" class="menuimageh2" /> ';
		}

		if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 1) {
			echo '<a href="discussions/index.php?g=11">'._AT('discussions').'</a>';
		}
		echo '</h2>';
	}

if ($_SESSION['course_id'] != 0) {
	echo'<h3>';
	if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 2) {
			echo '<img src="images/icons/default/inbox-large.gif" width="42" height="38" border="0" alt="" class="menuimageh3" />';
	}
	echo _AT('inbox');
	echo '</h3>';
} else {
	echo '<a name="content"></a>';
}

?>
<p>
<span class="bigspacer">( <a href="<?php echo $current_path; ?>send_message.php"><?php echo _AT('new_message'); ?></a> )</span>
<br /><br />
<p>
<?php

if (isset($_GET['s'])) {
	$feedback[] = AT_FEEDBACK_MSG_SENT;
	print_feedback($feedback);
}

if (isset($_GET['view'])) {
	$sql	= "SELECT * FROM ".TABLE_PREFIX."messages WHERE message_id=$_GET[view] AND to_member_id=$_SESSION[member_id]";
	$result = mysql_query($sql, $db);

	if ($row = mysql_fetch_assoc($result)) {
?>
	<table border="0" cellpadding="2" cellspacing="1" width="98%" class="bodyline" summary="">
	<tr>
		<td valign="top" class="cyan"><?php
			echo AT_print($row['subject'], 'messages.subject');
		?></td>
	</tr>
	<tr>
		<td><?php
			$from = get_login($row['from_member_id']);

			echo '<span class="bigspacer">'._AT('from').' <b>'.AT_print($from, 'members.logins').'</b> '._AT('posted_on').' ';
			echo AT_date(_AT('inbox_date_format'), $row['date_sent'], AT_DATE_MYSQL_DATETIME);
			echo '</span>';
			echo '<p>';
			echo AT_print($row['body'], 'messages.body');
			echo '</p>';

		?></td>
	</tr>
	</table>
	<span class="bigspacer">( <a href="<?php echo $current_path; ?>send_message.php?reply=<?php echo $_GET['view']; ?>" accesskey="r" title="<?php echo _AT('reply'); ?>: Alt-r"><b> <?php echo _AT('reply'); ?> [Alt-r]</b></a> |  <a href="<?php echo $_SERVER['PHP_SELF']; ?>?delete=<?php echo $_GET['view']; ?>" accesskey="x" title="<?php echo _AT('delete'); ?>: Alt-x"><b><?php echo _AT('delete'); ?> [Alt-x]</b></a> )</span>
	<?php
	}
	echo '<hr width="98%"></p>';
}

$sql	= "SELECT * FROM ".TABLE_PREFIX."messages WHERE to_member_id=$_SESSION[member_id] ORDER BY date_sent DESC";
$result = mysql_query($sql,$db);

if ($row = mysql_fetch_assoc($result)) {
	echo '<table border="0" cellspacing="1" cellpadding="0" width="98%" class="bodyline" summary="">';
if ($_SESSION['course_id'] == 0) {
	echo '<tr><th colspan="4" class="cyan">'._AT('inbox').'</th></tr>';
}
	echo '<tr>
		<th class="cat"><img src="images/clr.gif" alt="" width="40" height="1"></th>
		<th width="100" class="cat">'._AT('from').'</th>
		<th width="327" class="cat">'._AT('subject').'</font></th>
		<th width="150" class="cat">'._AT('date').'</font></th>
	</tr>';
	$count = 0;
	$total = mysql_num_rows($result);
	$view = $_GET['view'];
	do {
		$count ++;

		echo '<tr>';	
		echo '<td valign="middle" width="10" align="center" class="row1">';
		if ($row['new'] == 1)	{
			echo '<small>'._AT('new').'&nbsp;</small>';
		} else if ($row['replied'] == 1) {
			echo '<small>'._AT('replied').'</small>';
		}
		echo '</td>';

		$name = AT_print(get_login($row['from_member_id']), 'members.logins');

		echo '<td align="left" class="row1">';

		if ($view != $row['message_id']) {
			echo $name.'&nbsp;</td>';
		} else {
			echo '<b>'.$name.'</b>&nbsp;</td>';
		}

		echo '<td valign="middle" class="row1">';
		if ($view != $row['message_id']) {
			echo '<a href="'.$_SERVER['PHP_SELF'].'?view='.$row['message_id'].'">'.AT_print($row['subject'], 'messages.subject').'</a></td>';
		} else {
			echo '<b>'.AT_print($row['subject'], 'messages.subject').'</b></td>';
		}
	
		echo '<td valign="middle" align="left" class="row1"><small>';
		echo AT_date(_AT('inbox_date_format'),
					 $row['date_sent'],
					 AT_DATE_MYSQL_DATETIME);
		echo '</small></td>';
		echo '</tr>';

		if ($count < $total) {
			echo '<tr><td height="1" class="row2" colspan="4"></td></tr>';
		}

	} while ($row = mysql_fetch_assoc($result));
	echo '</tr>
		</table>';
} else {
	$infos[] = AT_INFOS_INBOX_EMPTY;
	print_infos($infos);
}
?>
<br /><br />
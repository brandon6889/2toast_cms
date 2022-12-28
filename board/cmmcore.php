<?php

if (!defined('IN_PHPBB'))
{
	exit;
}

$managementTypeId = substr($managementType,0,-1);

$deleteTarget = $mcdb->escape_string($_GET['delete']);
$selectTarget = $mcdb->escape_string($_GET['select']);
if($deleteTarget != '' && $deleteTarget != '0') {
	$getSkin = $mcdb->query('SELECT * FROM `2toastMinecraft`.`'.$managementType.'` WHERE `id`=\''.$deleteTarget.'\'');
	if($getSkin) {
		$deleteSkin = $getSkin->fetch_array(MYSQLI_ASSOC);
		if ( $deleteSkin['owner'] == $user->data['user_id'] ) {
			if($deleteSkin['status'] == 1) // make sure nobody uses a deleted item...
				$mcdb->query('UPDATE `2toastMinecraft`.`clientConfig` SET `'.$managementTypeId.'Id` = \'0\' WHERE `'.$managementTypeId.'Id` = \''.$deleteTarget.'\'');
			$mcdb->query('UPDATE `2toastMinecraft`.`'.$managementType.'` SET `status` = \'2\' WHERE `id` = \''.$deleteTarget.'\'');
		}
	}
}
if(sizeof($_FILES) > 0 && file_exists($_FILES['file']['tmp_name'])) {
	$imageInfo = getimagesize($_FILES['file']['tmp_name']);
	$width = $imageInfo[0];
	$height = $imageInfo[1];
	$type = $imageInfo['mime'];
	if($type == 'image/png' && $width == $managementTypeWidth && $height == $managementTypeHeight) {
		
		$mcdb->query('INSERT INTO `2toastMinecraft`.`'.$managementType.'` (`owner`) VALUES (\''.$user->data['user_id'].'\')');
		
		move_uploaded_file($_FILES['file']['tmp_name'], '../minecraft/'.$managementType.'/'.$mcdb->insert_id.'.png');
	} else {
		trigger_error('Uploaded file not recognized as '.$managementTypeId.'.');
	}
}
if($selectTarget != '') {
	if($selectTarget == 0) {
		$mcdb->query('INSERT INTO `2toastMinecraft`.`clientConfig` (`playerId`, `playerName`, `'.$managementTypeId.'Id`) VALUES (\''.$user->data['user_id'].'\', \''.$user->data['username'].'\', \'0\') ON DUPLICATE KEY UPDATE `'.$managementTypeId.'Id` = \'0\'');
	} else {
		$getSkin = $mcdb->query('SELECT * FROM `2toastMinecraft`.`'.$managementType.'` WHERE `id` = \''.$selectTarget.'\'');
		if($getSkin) {
			$newSkin = $getSkin->fetch_array(MYSQLI_ASSOC);
			if($newSkin['status'] != 2 && ( $newSkin['owner'] == $user->data['user_id']) ) {
				$mcdb->query('INSERT INTO `2toastMinecraft`.`clientConfig` (`playerId`, `playerName`, `'.$managementTypeId.'Id`) VALUES (\''.$user->data['user_id'].'\', \''.$user->data['username'].'\', \''.$newSkin['id'].'\') ON DUPLICATE KEY UPDATE `'.$managementTypeId.'Id` = \''.$newSkin['id'].'\'');
				$mcdb->query('UPDATE `2toastMinecraft`.`'.$managementType.'` SET `status` = \'1\' WHERE `id` = \''.$newSkin['id'].'\'');
			}
		}
	}
}

$selectedId = 0;
$skinStmt = $mcdb->prepare('SELECT `'.$managementTypeId.'Id'.'` FROM `2toastMinecraft`.`clientConfig` WHERE `playerId` = ?');
$skinStmt->bind_param('s', $user->data['user_id']);
$skinStmt->execute() or die('Database Failure');
$skinStmt->bind_result($selectedId);
$skinStmt->fetch();
$skinStmt->close();

$template->assign_block_vars('selectable', array(
	'ID' => '0',
	'SELECTED' => ($selectedId == 0),
));

$selectableStmt = $mcdb->prepare('SELECT `id`,`status` from `2toastMinecraft`.`'.$managementType.'` WHERE `owner` = ?');
$selectableStmt->bind_param('s', $user->data['user_id']);
$selectableStmt->execute();
$selectableStmt->bind_result($selectable, $status);
while ($selectableStmt->fetch())
{
	if ($status != 2)
	$template->assign_block_vars('selectable', array(
		'ID' => $selectable,
		'SELECTED' => ($selectedId == $selectable),
	));
}
$selectableStmt->close();
?>

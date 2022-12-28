<?php
$skinCost = 2;

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
$user->session_begin();
$auth->acl($user->data);
$user->setup();

if ($user->data['user_id'] == ANONYMOUS || $user->data['is_bot'])
{
        die(login_box());
}

page_header('2Toast Skins');

//include_once($phpbb_root_path . 'includes/functions_profile_fields.' . $phpEx);
//$user->get_profile_fields( $user->data['user_id'] );

$managementType = 'skins';
$managementCount = 3;// + floor($user->profile_fields['pf_donations'] / $skinCost);
$managementTypeWidth = 64;
$managementTypeHeight = 32;

require_once('../minecraft/config.php');

$mcdb = db_connect($authConfig);

require_once('cmmcore.php');

$template->assign_vars(array(
	'PAGE_HEADING' => 'Skin Management',
	'MANAGEMENT_ITEM' => 'Skin',
        'MANAGEMENT_COUNT' => $managementCount,
	'UPLOAD_DIR' => $managementType,
	'PAGE_NAME' => 'skin',
));

$template->set_filenames(array(
    'body' => 'custom_management.html',
));

page_footer();
?>

<?php
$capeCost = 2;

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

page_header('2Toast Capes');

//include_once($phpbb_root_path . 'includes/functions_profile_fields.' . $phpEx);
//$user->get_profile_fields( $user->data['user_id'] );

$managementType = 'capes';
$managementCount = 3;//floor($user->profile_fields['pf_donations'] / $capeCost);
$managementTypeWidth = 22;
$managementTypeHeight = 17;

require_once('../minecraft/config.php');

$mcdb = db_connect($authConfig);

require_once('cmmcore.php');

$template->assign_vars(array(
        'PAGE_HEADING' => 'Cape Management',
	'MANAGEMENT_ITEM' => 'Cape',
	'MANAGEMENT_COUNT' => $managementCount,
	'UPLOAD_DIR' => $managementType,
	'PAGE_NAME' => 'cape',
));

$template->set_filenames(array(
    'body' => 'custom_management.html',
));

page_footer();
?>

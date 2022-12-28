<?php
if (isset($_SERVER['HTTPS']) &&
    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
} else {
  //die ('Insecure Client');
}

$gameVersion = '1.12.2';
require_once('config.php');

$username = $_POST['user'];
$password = $_POST['pass'];
$clientVersion = $_POST['version'];
$clientIp = $_SERVER['REMOTE_ADDR'];
$clientAgent = $_SERVER['HTTP_USER_AGENT'];

if($username==''||$password==''||$clientVersion=='') die('Invalid request');

if($username=='server' && $password=='nietzsche') {$userid = 0;}
else {
  define('IN_PHPBB', true);
  $phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../board.decommissioned/';
  $phpEx = substr(strrchr(__FILE__, '.'), 1);
  if(!@include($phpbb_root_path . 'common.' . $phpEx)) die ('Forums Maintenance');
  if($config['board_disable'] == 1) die ('Forums Locked');
  $user->session_begin();
  $auth->acl($user->data);
  $user->setup();

  $loginResult = $auth->login($username, $password, false, 0, 0);

  if ($user->data['user_id'] == ANONYMOUS || !$user->data['is_registered'] || $user->data['is_bot']) die ('Incorrect Login');
  if ($user->data['user_posts'] == 0) die ('Forums: Post Introduction');

  //include_once($phpbb_root_path . 'includes/functions_profile_fields.' . $phpEx);
  //$user->get_profile_fields( $user->data['user_id'] );

  $username = $user->data['username'];
  $userid = $user->data['user_id'];
}

$sessionId = rand(100000000,999999999);
$sessionHash = 'dummyHash';

$mcdb = db_connect($authConfig);

$sessionStatement = $mcdb->prepare('INSERT INTO `2toastMinecraft`.`clientSessions` (`playerName`, `sessionId`, `sessionHash`) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE `sessionId` = ?, `time`=now()');
$sessionStatement->bind_param('ssss', $username, $sessionId, $sessionHash, $sessionId);
$sessionStatement->execute() or die ('Database Failure');
$sessionStatement->close();

$loginStatement = $mcdb->prepare('INSERT INTO `2toastMinecraft`.`clientLogins` (`playerId`, `ip`, `playerAgent`) VALUES (?, ?, ?)');
$loginStatement->bind_param('iss', $userid, $clientIp, substr($mcdb->escape_string($clientAgent),0,15));
$loginStatement->execute() or die ('Database Failure');
$loginStatement->close();

echo $gameVersion.'::'.$username.':'.$sessionId.':';
?>

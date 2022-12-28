<?php
//die('YES');
$userN = $_GET['user'];
$serverId = substr($_GET['serverId'],0,16);
$serverIP = $_SERVER['REMOTE_ADDR'];

if($userN==''||$serverId=='') die('User verification failed.');

require('config.php');

$mcdb = db_connect($authConfig);
$userN = $mcdb->escape_string($userN);
$serverId = $mcdb->escape_string($serverId);

$sessionStatement = $mcdb->prepare('SELECT `sessionHash` FROM `2toastMinecraft`.`clientSessions` WHERE `playerName` = ?');
$sessionStatement->bind_param('s', $userN);
$sessionStatement->execute() or die ('Database Failure');
$sessionStatement->bind_result($hash);
$hash = '';
$sessionStatement->fetch();
$sessionStatement->close();

if ($serverId!=$hash) die('User verification failed.');

$joinStatement = $mcdb->prepare('UPDATE `2toastMinecraft`.`serverJoins` SET `serverIP` = ? WHERE `serverIP` = ? and `playerName` = ?');
$joinStatement->bind_param('sss', $serverIP, $serverId, $userN);
$joinStatement->execute() or die ('Database Failure');
$joinStatement->close();

echo 'YES';

?>

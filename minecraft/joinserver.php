<?php
//die('OK');
$userN = substr($_GET['user'],0,15);
$sessionId = $_GET['sessionId'];
$serverHash = substr($_GET['serverId'],0,16);
$loginIP = $_SERVER['REMOTE_ADDR'];

if($userN==''||$sessionId==''||$serverHash=='') die('Bad Session ID');

require('config.php');

$mcdb = db_connect($authConfig);
$userN = $mcdb->escape_string($userN);
$sessionId = $mcdb->escape_string($sessionId);
$serverHash = $mcdb->escape_string($serverHash);

$sessionStatement = $mcdb->prepare('SELECT `sessionId` FROM `2toastMinecraft`.`clientSessions` WHERE `playerName` = ?');
$sessionStatement->bind_param('s', $userN);
$sessionStatement->execute() or die ('Database Failure');
$sessionStatement->bind_result($sid);
$sid = '';
$sessionStatement->fetch();
$sessionStatement->close();

if ($sessionId != $sid) die ('NO');

$hashStatement = $mcdb->prepare('UPDATE `2toastMinecraft`.`clientSessions` SET `sessionHash` = ? WHERE `playerName` = ?');
$hashStatement->bind_param('ss', $serverHash, $userN);
$hashStatement->execute() or die ('Database Failure');
$hashStatement->close();

$logStatement = $mcdb->prepare('INSERT INTO `2toastMinecraft`.`serverJoins` (`playerName`, `clientIP`, `serverIP`) VALUES (?, ?, ?)');
$logStatement->bind_param('sss', $userN, $loginIP, $serverHash);
$logStatement->execute() or die ('Database Failure');
$logStatement->close();

echo 'OK';

?>

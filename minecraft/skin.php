<?php

$user = $_GET['u'];
$skinId = 0;
if ($user != '')
{
  require_once('config.php');
  
  $mcdb = db_connect($authConfig);
  $user = $mcdb->escape_string($user);
  
  $stmt = $mcdb->prepare('SELECT `skinId` FROM `2toastMinecraft`.`clientConfig` WHERE `playerName` = ?');
  $stmt->bind_param('s', $user);
  $stmt->execute() or die ('Database Failure');
  $stmt->bind_result($skinId);
  $stmt->fetch();
  $stmt->close();
  if (!file_exists('skins/'.$skinId.'.png'))
    $skinId = 0;
}

header('Location: http://2toast.net/minecraft/skins/'.$skinId.'.png');
?>

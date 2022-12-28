<?php

$user = $_GET['u'];
$capeId = 0;
if ($user != '')
{
  require_once('config.php');
  
  $mcdb = db_connect($authConfig);
  $user = $mcdb->escape_string($user);
  
  $stmt = $mcdb->prepare('SELECT `capeId` FROM `2toastMinecraft`.`clientConfig` WHERE `playerName` = ?');
  $stmt->bind_param('s', $user);
  $stmt->execute() or die ('Database Failure');
  $stmt->bind_result($capeId);
  $stmt->fetch();
  $stmt->close();
  if (!file_exists('capes/'.$capeId.'.png'))
    $capeId = 0;
}

header('Location: http://2toast.net/minecraft/capes/'.$capeId.'.png');
?>

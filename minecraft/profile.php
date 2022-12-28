<?php
$userN = $_GET['user'];

if($userN=='') die('{"error":"No username provided"}');

require('config.php');

$mcdb = db_connect($authConfig);
$userN = $mcdb->escape_string($userN);

$response = '{';

$params = 0;

// Get skin
$skinId = 0;
$stmt = $mcdb->prepare('SELECT `skinId` FROM `2toastMinecraft`.`clientConfig` WHERE `playerName` = ?');
$stmt->bind_param('s', $userN);
$stmt->execute() or die ('{"error":"Database Failure"}');
$stmt->bind_result($skinId);
$stmt->fetch();
$stmt->close();
$skinPath = 'skins/'.$skinId.'.png';
if (file_exists($skinPath)) {
	$params = $params + 1;
	$response = $response.'"skin":"'.$skinId.'"';
}

// Get cape
$capeId = 0;
$stmt = $mcdb->prepare('SELECT `capeId` FROM `2toastMinecraft`.`clientConfig` WHERE `playerName` = ?');
$stmt->bind_param('s', $userN);
$stmt->execute() or die ('{"error":"Database Failure"}');
$stmt->bind_result($capeId);
$stmt->fetch();
$stmt->close();
$capePath = 'capes/'.$capeId.'.png';
if (file_exists($capePath)) {
	if ($params > 0)
		$response = $response.',';
	$params = $params + 1;
	$response = $response.'"cape":"'.$capeId.'"';
}

$response = $response.'}';

echo $response;
?>

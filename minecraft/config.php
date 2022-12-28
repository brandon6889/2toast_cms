<?php

$authConfig = array();

$authConfig['dbServer'] = 'localhost';
$authConfig['dbUser'] = '2toastAuth';
$authConfig['dbPass'] = 'jfP6I827fYA18UF';
$authConfig['dbName'] = '2toastMinecraft';

function db_connect($authConfig)
{
	// Connect to database. I supress the error here.
	$fresMySQLConnection = new mysqli($authConfig['dbServer'], $authConfig['dbUser'], $authConfig['dbPass'], $authConfig['dbName']);

	// Check if we are connected to the database.
	if ($fresMySQLConnection->connect_errno > 0)
	{
		die('Database Fail');
		$this->mySQLError('Database failure.');
	}

	// MySQL 4.1 compat
	//$fresStatement = $fresMySQLConnection->prepare("SET NAMES 'utf8'");
	//$fresStatement->execute();

	return $fresMySQLConnection;
}
?>

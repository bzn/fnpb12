<?php
require 'lib/package/Utility/utilAuth.php';

if(!utilAuth::checkAccessToken($_COOKIE['accessToken']) || 
	!utilAuth::validateCheckCode($_COOKIE['checkCode'], $_COOKIE['accessToken'], $_COOKIE['userid'], $_COOKIE['myteamid']))
{
	header("location:index.php");
}
?>
<?php
include_once("include/init.php");
include_once("include/func.php");

$data = GetPlayerInfo(11);
echo json_encode($data);
?>
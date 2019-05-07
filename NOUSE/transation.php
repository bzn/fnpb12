<?php

/*
// 連線資料庫
$hostname = "localhost";
$database_mlb = "mumutoys_fantasy_mlb";
$database_npb = "mumutoys_fantasy_npb";
$database_public = "mumutoys_fantasy_public";
$username = "mumutoys_fantasy";
$password = "1qaz2wsx";

$dsn="mysql:host=$hostname;dbname=$database_npb";
try 
{
    $DB_Link = new PDO($dsn, $username,$password); 
    echo "OK";
} catch (PDOException $e) {
    // 資料庫連結失敗
    $e->errorInfo ; // 錯誤明細
    $e->getMessage(); // 返回異常資訊
    $e->getPrevious(); // 返回前一個異常
    $e->getCode(); // 返回異常程式碼
    $e->getFile(); // 返回發生異常的檔案名
    $e->getLine(); // 返回發生異常的程式碼行號
    $e->getTrace(); // backtrace() 陣列
    $e->getTraceAsString(); // 已格成化成字串的 getTrace() 資訊    
    
    // 錯誤處理...
    echo "FALSE";
}
*/


include_once("include/init.php");
include_once("class/db.class.php");

$db = new DB();

try 
{
	$db->npb->beginTransaction();
	// 加入一筆隊伍資料
	$sql = "INSERT INTO `myteam_data` (`name`) VALUES ('11111')";
	$db->npb->exec($sql);
	echo "OK1";
	// 加入一筆隊伍資料
	$sql = "INSERT INTO `` (`name`) VALUES ('22222')";
	$db->npb->exec($sql);
	echo "OK2";
	//$db->npb->commit();
	$db->npb->rollBack();
} 
catch (PDOException $e) 
{
	$db->npb->rollBack();	
	echo $e->getMessage();
}


?>
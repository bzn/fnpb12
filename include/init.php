<?php
/*
 * init.php
 */
//ini_set('date.timezone', 'Asia/Taipei');
header('Content-Type: text/html; charset=utf-8');

// 編碼設定
mb_internal_encoding("UTF-8");
// 顯示除錯訊息
ini_set('display_errors', 1);

include_once(dirname(__FILE__)."/define.php");
include_once(dirname(__FILE__)."/errmsg.php");


?>
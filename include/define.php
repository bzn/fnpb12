<?php
/*
 * define.php
 */

// 開賽時間
define("OPEN_DATE", "2012-03-30");

// 聯盟種類
//define("LEAGUE_NAME", "MLB");
define("LEAGUE_NAME", "NPB");
//define("LEAGUE_NAME", "CPBL");

// 伺服器狀態
define("SERVER_STATE_NORAML",		1);		// 正常狀態 -> 凍結狀態
define("SERVER_STATE_FORZEN",		2);		// 凍結狀態 -> 價錢變動
define("SERVER_STATE_PRICEMOVED",	3);		// 價錢變動 -> 解凍完畢
define("SERVER_STATE_UNFORZEN",		4);		// 解凍完畢 -> 
define("SERVER_STATE_ERROR",		99);	// 順序錯誤

// 守備位置
define("P", 1);
define("C", 2);
define("FB", 3);
define("SB", 4);
define("TB", 5);
define("SS", 6);
define("OF", 7);
define("DH", 10);

?>
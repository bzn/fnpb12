<?php
// 連線資料庫
$hostname = "localhost";
$database_mlb = "mumutoys_fantasy_mlb";
$database_npb = "mumutoys_fantasy_npb";
$database_public = "mumutoys_fantasy_public";
$username = "mumutoys_fantasy";
$password = "1qaz2wsx";

$dsn="mysql:host=$hostname;dbname=$database_npb";
try {
    $DB_Link = new PDO($dsn, $username,$password); 
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
}

// 設定存取的編碼方式
$DB_Link->exec("SET NAMES 'utf8';");

// 設定 GROUP_CONCAT 的最大長度
$DB_Link->exec("SET group_concat_max_len=65536;");

/*--[PDO::query]-----------------------------------*/
// 建立查詢連結
$stmt = $DB_Link->query("SELECT * FROM  `player_basedata` WHERE id =11");

// 設定查詢結果的資料格式，之後可以省去 fetch 時的格式設定
$stmt->setFetchMode(PDO::FETCH_ASSOC);

// 取得查詢結果的列數
$count = $stmt->rowCount ();
echo $count;
exit;
/* PDOStatement::rowCount()主要是用於 PDO::query()和PDO::prepare()進行DELETE、INSERT、UPDATE操作影響的結果集，對PDO::exec()方法 和SELECT操作無效。 */

// 取得一列的查詢結果
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// 利用迴圈對每一列做處理
$dataArray = array(); // 結果儲存陣列
while ($row = $stmt->fetch(PDO::FETCH_ASSOC) {
    // 資料處理
    $row['colour'] = $row['colour']? $row['colour'] : '#000';
    // 加儲存陣列
    array_push($dataArray,$row);
}

// 取得一列單一欄位的查詢結果
$column = $stmt->fetchColumn(); // id
$column = $stmt->fetchColumn(1); // colour
/* 當查詢結果之有一個時很好用(SELECT COUNT(*) FROM test_table) */

// 取得所有的查詢結果列
$dataArray = $stmt->fetchAll (PDO::FETCH_ASSOC);


/* PDO::fetch , PDO::fetchAll 選項參數
PDO::FETCH_LAZY
    將每一行結果作為一個對象返回
   
PDO::FETCH_ASSOC
    僅返回以鍵值作為下標的查詢的結果集，名稱相同的數據只返回一個

PDO::FETCH_NAMED
    僅返回以鍵值作為下標的查詢的結果集，名稱相同的數據以數組形式返回
    
PDO::FETCH_NUM
    僅返回以數字作為下標的查詢的結果集
    
PDO::FETCH_BOTH
    同時返回以鍵值和數字作為下標的查詢的結果集(預設)
    
PDO::FETCH_OBJ
    按照對象的形式，類似於以前的 mysql_fetch_object()
    
PDO::FETCH_BOUND
    將PDOStatement::bindParam()和PDOStatement::bindColumn()所綁定的值作為變量名賦值後返回
    
PDO::FETCH_COLUMN
    將返回結果每一列全部集中在一個欄位
    
PDO::FETCH_CLASS
    以 Class 的形式返回結果集

PDO::FETCH_INTO
    將數據合併入一個存在的類中進行返回
    
PDO::FETCH_FUNC
PDO::FETCH_GROUP
PDO::FETCH_UNIQUE
PDO::FETCH_KEY_PAIR
    以首個鍵值下表，後面數字下表的形式返回結果集

PDO::FETCH_CLASSTYPE
PDO::FETCH_SERIALIZE
    表示將數據合併入一個存在的類中并序列化返回

PDO::FETCH_PROPS_LATE
    Available since PHP 5.2.0
*/



/*--[PDO::exec]-----------------------------------*/
$colour="'red'";

// 建立新增請求
$count = $DB_Link->exec(sprintf("
    INSERT INTO test_table(colour) VALUES(%s)
",$colour));
// 取得上次 Insert 時產生的 AUTO_INCREMENT Id
$id = $DB_Link->lastInsertId();

// 建立更新請求
$count = $DB_Link->exec(sprintf("
    UPDATE test_table SET colour = %s WHERE id=1
",$colour));

// 建立刪除請求
$count = $DB_Link->exec(sprintf("
    DELETE FROM test_table WHERE colour = %s LIMIT 10
",$colour));
/*
  PDO::exec 會回傳所影響的列數
  但如果 DELETE 到資料表為空時將回傳 0，而不是所刪除的列數
*/

?>
<?php
class DB
{
	public function __construct()
	{
		$hostname = "localhost";
		$database_mlb = "ohohdada_fantasy_mlb";
		$database_npb = "ohohdada_fantasy_npb";
		$database_public = "ohohdada_fantasy_public";
		$username = "ohohdada_fantasy";
		$password = "1qaz2wsx";

		$dsn_npb="mysql:host=".$hostname.";dbname=".$database_npb;  
		try
		{  
			$this->npb = new PDO(
				$dsn_npb, 
				$username, 
				$password, 
				array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'")
			);   
		}
		catch (PDOException $e) 
		{  
			echo $e->getMessage(); // 返回異常資訊   
		}  

		/*
		// PDO範例
		$sql = "SELECT * FROM `player_basedata` WHERE id = 11";
		$stmt = $DB_NPB->query($sql);
		// 設定查詢結果的資料格式，之後可以省去 fetch 時的格式設定
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		// 取得查詢結果的列數
		//$count = $stmt->rowCount ();
		// 取得一列的查詢結果
		//$row = $stmt->fetch(PDO::FETCH_ASSOC);
		// 取得所有的查詢結果列
		$dataArray = $stmt->fetchAll (PDO::FETCH_ASSOC);
		var_dump($dataArray);
		*/
	}

	public function rowCount($sql)
	{
		$stmt = $this->npb->query($sql);
		return $stmt->rowCount ();
	}

	public function query($sql)
	{
		$stmt = $this->npb->query($sql);
		if(!$stmt)
		{			
			$errArr = $this->npb->errorInfo();
			echo "<font color=red>[PDO]".$errArr[2]."</font><BR>";
		}
		return $stmt;
	}

	public function lastInsertId()
	{
		return $this->npb->lastInsertId();
	}
}
?>
<?php
class utilGameDataManager extends utilDataConnection implements IDataManager{
	
	static $utilGameDataManager = null;
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 
	 * @return utilGameDataManager
	 */
	public function sharedDataManager(){
		if(!self::$utilGameDataManager)
		{
			self::$utilGameDataManager = new self();
		}
		return self::$utilGameDataManager;
	}
	
	public function initDailyRecord($sDate_ = '')
	{
		if(!empty($sDate_))
		{
			//是否已經有寫入每日記錄
			$sql = sprintf("SELECT COUNT(*) AS cnt FROM %s WHERE 1 AND `date`='%s'",
							constDB::PLAYER_REC_P_DAILY, $sDate_);
			if($stmt = $this->objPDO->query($sql))
			{
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				if($row['cnt'] <= 0)
				{
					//寫入本日投手記錄資料
					$sql = sprintf("INSERT INTO %s (id, `date`) SELECT id,'%s' FROM %s WHERE 1 AND p=1",
									constDB::PLAYER_REC_P_DAILY, $sDate_, constDB::PLAYER_BASEDATA);
					$this->objPDO->query($sql);
					//寫入本日打者記錄資料
					$sql = sprintf("INSERT INTO %s (id, `date`) SELECT id,'%s' FROM %s WHERE 1 AND dh=1",
									constDB::PLAYER_REC_H_DAILY, $sDate_, constDB::PLAYER_BASEDATA);
					$this->objPDO->query($sql);
				}
			}
		}
	}
	
	/**
	 * 
	 * 更新每日記錄(打者)
	 * @param structDailyRecordH $dailyRecordH_
	 */
	public function updateDailyRecordH(structDailyRecordH $dailyRecordH_)
	{
		if(!$dailyRecordH_) return;
		$updateTable = constDB::PLAYER_REC_H_DAILY;
		$updateData	 = (array)$dailyRecordH_;
		//取得基本資料表的欄位資訊
		$columnInfo = $this->getColumnInfo($updateTable);
		//檢查資料表欄位
		if($columnInfo)
		{
			//新增的SQL語法
			$arrInsertKey = array('id','`date`');
			$arrInsertVal = array($updateData['id'], sprintf("'%s'",$updateData['date']));
			//更新的SQL語法
			$arrUpdate = array();
			foreach ($updateData as $key => $val)
			{
				//Primary Key 不可更新
				if ($columnInfo[$key] && $key != 'id' && $key != 'date')
				{
					array_push($arrInsertKey, $key);
					//數字格式
					if (strpos($columnInfo[$key]['Type'], 'int') !== FALSE)
					{
						if(!empty($val)) array_push($arrUpdate, sprintf("%s=%d", $key, $val));
						array_push($arrInsertVal, sprintf("%d",$val));
					}
					//字串
					elseif(strpos($columnInfo[$key]['Type'], 'char') !== FALSE || strpos($columnInfo[$key]['Type'], 'text') !== FALSE)
					{
						if(!empty($val)) array_push($arrUpdate, sprintf("%s='%s'", $key, $val));
						array_push($arrInsertVal, sprintf("'%s'",$val));
					}
					//浮點
					elseif(strpos($columnInfo[$key]['Type'], 'float') !== FALSE || strpos($columnInfo[$key]['Type'], 'double') !== FALSE)
					{
						if(!empty($val)) array_push($arrUpdate, sprintf("%s=%f", $key, $val));
						array_push($arrInsertVal, sprintf("%f",$val));
					}
					else 
					{
						if(!empty($val)) array_push($arrUpdate, sprintf("%s='%s'", $key, $val));
						array_push($arrInsertVal, sprintf("'%s'",$val));
					}
				}
			}
		}
		
		var_dump($arrUpdate, $arrInsertKey, $arrInsertVal);
		
		$sql = sprintf("SELECT * FROM %s WHERE 1 AND id=%d AND `date`='%s'",
						$updateTable, $updateData['id'], $updateData['date']);
		if($stmt = $this->objPDO->query($sql))
		{
			if($stmt->rowCount() > 0)
			{
				//更新資料表
				$sql = sprintf("UPDATE %s SET %s WHERE 1 AND id=%d AND `date`='%s'",
								$updateTable, implode(",", $arrUpdate), $updateData['id'], $updateData['date']);
				$this->objPDO->query($sql);
			}
			else 
			{
				//如果沒有資料就新增一筆
				$sql = sprintf("INSERT INTO %s(%s) VALUES(%s)",
								$updateTable, implode(",", $arrInsertKey), implode(",", $arrInsertVal));
				$this->objPDO->query($sql);
			}
		}
		
		var_dump($sql);
	}
	
	/**
	 * 
	 * 更新每日記錄(投手)
	 * @param structDailyRecordP $dailyRecordP_
	 */
	public function updateDailyRecordP(structDailyRecordP $dailyRecordP_){
		if(!$dailyRecordP_) return;
		$updateTable = constDB::PLAYER_REC_P_DAILY;
		$updateData	 = (array)$dailyRecordP_;
		//取得基本資料表的欄位資訊
		$columnInfo = $this->getColumnInfo($updateTable);
		//檢查資料表欄位
		if($columnInfo)
		{
			//新增的SQL語法
			$arrInsertKey = array('id','`date`');
			$arrInsertVal = array($updateData['id'], sprintf("'%s'",$updateData['date']));
			//更新的SQL語法
			$arrUpdate = array();
			foreach ($updateData as $key => $val)
			{
				//Primary Key 不可更新
				if ($columnInfo[$key] && $key != 'id' && $key != 'date')
				{
					array_push($arrInsertKey, $key);
					//數字格式
					if (strpos($columnInfo[$key]['Type'], 'int') !== FALSE)
					{
						if(!empty($val)) array_push($arrUpdate, sprintf("%s=%d", $key, $val));
						array_push($arrInsertVal, sprintf("%d",$val));
					}
					//字串
					elseif(strpos($columnInfo[$key]['Type'], 'char') !== FALSE || strpos($columnInfo[$key]['Type'], 'text') !== FALSE)
					{
						if(!empty($val)) array_push($arrUpdate, sprintf("%s='%s'", $key, $val));
						array_push($arrInsertVal, sprintf("'%s'",$val));
					}
					//浮點
					elseif(strpos($columnInfo[$key]['Type'], 'float') !== FALSE || strpos($columnInfo[$key]['Type'], 'double') !== FALSE)
					{
						if(!empty($val)) array_push($arrUpdate, sprintf("%s=%f", $key, $val));
						array_push($arrInsertVal, sprintf("%f",$val));
					}
					else 
					{
						if(!empty($val)) array_push($arrUpdate, sprintf("%s='%s'", $key, $val));
						array_push($arrInsertVal, sprintf("'%s'",$val));
					}
				}
			}
		}
		
		var_dump($arrUpdate, $arrInsertKey, $arrInsertVal);
		
		$sql = sprintf("SELECT * FROM %s WHERE 1 AND id=%d AND `date`='%s'",
						$updateTable, $updateData['id'], $updateData['date']);
		if($stmt = $this->objPDO->query($sql))
		{
			if($stmt->rowCount() > 0)
			{
				//更新資料表
				$sql = sprintf("UPDATE %s SET %s WHERE 1 AND id=%d AND `date`='%s'",
								$updateTable, implode(",", $arrUpdate), $updateData['id'], $updateData['date']);
				$this->objPDO->query($sql);
			}
			else 
			{
				//如果沒有資料就新增一筆
				$sql = sprintf("INSERT INTO %s(%s) VALUES(%s)",
								$updateTable, implode(",", $arrInsertKey), implode(",", $arrInsertVal));
				$this->objPDO->query($sql);
			}
		}
		
		var_dump($sql);
	}
}
?>
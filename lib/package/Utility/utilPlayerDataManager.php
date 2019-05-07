<?php
class utilPlayerDataManager extends utilDataConnection implements IDataManager{
	
	static $utilPlayerDataManager = null;
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 
	 * @return utilPlayerDataManager
	 */
	public function sharedDataManager(){
		if(!self::$utilPlayerDataManager)
		{
			self::$utilPlayerDataManager = new self();
		}
		return self::$utilPlayerDataManager;
	}
	
	/**
	 * 
	 * 更新球員資料
	 * @param stdClass $playerData_
	 */
	public function updatePlayerData(stdClass $playerData_ = NULL)
	{
		if(!$playerData_) return;
		
		//Yahoo Id
		$yahooId 		= $playerData_->yahooId;
		//球員編號
		$playerId		= $playerData_->playerId;
		//更新資料表
		$updateTable	= $playerData_->updateTable;
		//更新資料
		$updateData		= $playerData_->updateData;
		
		if(empty($yahooId) && empty($playerId))
		{
			return;
		}
		
		//如果沒有playerId就用yahooId來取playerId
		if(empty($playerId))
		{
			$playerId = $this->getPlayerId($yahooId);
		}
		
		//檢查球員編號
		if (!empty($playerId))
		{
			//檢查是否更新基本資料
			if(!empty($updateData))
			{
				//取得基本資料表的欄位資訊
				$columnInfo = $this->getColumnInfo($updateTable);
				//檢查資料表欄位
				if($columnInfo)
				{
					//新增的SQL語法
					$arrInsertKey = array('id');
					$arrInsertVal = array($playerId);
					//更新的SQL語法
					$arrUpdate = array();
					foreach ($updateData as $key => $val)
					{
						//Primary Key 不可更新
						if ($columnInfo[$key] && $key != 'id')
						{
							array_push($arrInsertKey, $key);
							//數字格式
							if (strpos($columnInfo[$key]['Type'], 'int') !== FALSE)
							{
								array_push($arrUpdate, sprintf("%s=%d", $key, $val));
								array_push($arrInsertVal, sprintf("%d",$val));
							}
							//字串
							elseif(strpos($columnInfo[$key]['Type'], 'char') !== FALSE || strpos($columnInfo[$key]['Type'], 'text') !== FALSE)
							{
								array_push($arrUpdate, sprintf("%s='%s'", $key, $val));
								array_push($arrInsertVal, sprintf("'%s'",$val));
							}
							//浮點
							elseif(strpos($columnInfo[$key]['Type'], 'float') !== FALSE || strpos($columnInfo[$key]['Type'], 'double') !== FALSE)
							{
								array_push($arrUpdate, sprintf("%s=%f", $key, $val));
								array_push($arrInsertVal, sprintf("%f",$val));
							}
							else 
							{
								array_push($arrUpdate, sprintf("%s='%s'", $key, $val));
								array_push($arrInsertVal, sprintf("'%s'",$val));
							}
						}
					}
					
					$sql = sprintf("SELECT * FROM %s WHERE 1 AND id=%d",
									$updateTable, $playerId);
					if($stmt = $this->objPDO->query($sql))
					{
						if($stmt->rowCount() > 0)
						{
							//更新資料表
							$sql = sprintf("UPDATE %s SET %s WHERE 1 AND id=%d",
											$updateTable, implode(",", $arrUpdate), $playerId);
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
		}
	}
	
	public function updatePlayerTeamIndex($yahooId_ = 0, $yahooTeamId_ = 0)
	{
		if (empty($yahooId_) || empty($yahooTeamId_)) {
			return;
		}
		
		$playerId 	= $this->getPlayerId($yahooId_);
		$teamId		= utilTeamDataManager::sharedDataManager()->getTeamId($yahooTeamId_);
		$sql = sprintf("SELECT * FROM %s WHERE 1 AND player_id = %d", constDB::TEAM_PLAYER_INDEX, $playerId);
		if($stmt = $this->objPDO->query($sql))
		{
			if($stmt->rowCount() > 0)
			{
				$sql = sprintf("UPDATE %s SET team_id=%d WHERE 1 AND player_id=%d",
								constDB::TEAM_PLAYER_INDEX, $teamId, $playerId);
				$this->objPDO->query($sql);
			}
			else 
			{
				$sql = sprintf("INSERT INTO %s VALUES(%d,%d)",
								constDB::TEAM_PLAYER_INDEX, $teamId, $playerId);
				$this->objPDO->query($sql);
			}
		}
		var_dump($sql);
	}
	
	/**
	 * 
	 * 取得球員編號
	 * @param int $yahooId_
	 */
	public function getPlayerId($yahooId_ = 0)
	{
		//檢查Yahoo id
		if($yahooId_ >= 0)
		{
			//取Player id
			$sql = sprintf("SELECT id AS playerId FROM %s WHERE 1 AND yid=%d",
							constDB::PLAYER_BASEDATA, $yahooId_);
			if($stmt = $this->objPDO->query($sql))
			{
				if ($stmt->rowCount() > 0)
				{
					$row = $stmt->fetch(PDO::FETCH_ASSOC);
					return $row['playerId'];
				}
				else 
				{
					//Yahoo id不存在就新增一筆資料
					$sql = sprintf("INSERT INTO %s (yid) VALUES(%d)", 
									constDB::PLAYER_BASEDATA, $yahooId_);
					if($this->objPDO->query($sql))
					{
						return $this->objPDO->lastInsertId();
					}
				}
			}
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * 
	 * 取得球員基本資料
	 * @param int $playerId
	 * @return array $playerData
	 */
	public function getPlayerData($playerId = 0){
		if ($playerId > 0)
		{
			$sql = sprintf("SELECT * FROM %s LEFT JOIN %s USING(id) WHERE 1 AND id=%d",
							constDB::PLAYER_BASEDATA, constDB::PLAYER_INFO, $playerId);
			if($stmt = $this->objPDO->query($sql))
			{
				if($stmt->rowCount() > 0)
				{
					return $stmt->fetch(PDO::FETCH_ASSOC);
				}
				else 
				{
					throw new ExceptionPlayer(ExceptionPlayer::PLAYER_NOT_EXIST);
				}
			}
		}
		else
		{
			throw new ExceptionPlayer(ExceptionPlayer::PLAYER_ID_EMPTY);
		}
	}
	
	/**
	 * 
	 * 取得球員基本資料列表
	 */
	public function getPlayerBaseDataList()
	{
		$rowNew = array();
		
		$sql = sprintf("SELECT * FROM %s WHERE 1",
						constDB::PLAYER_BASEDATA);
		if($stmt = $this->objPDO->query($sql))
		{
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$rowNew[$row['id']] = $row;
			}
		}
		return $rowNew;
	}
	
	/**
	 * 
	 * 取得球員清單
	 * @param array $searchOption
	 */
	public function getPlayerList($searchOption = array())
	{
		$rData = array();
		if($searchOption['pos'] == 'p')
		{
			$recordTable = ($searchOption['year'] == 'this') ? constDB::PLAYER_REC_P_2012 : constDB::PLAYER_REC_P_2011 ;
		}
		else 
		{
			$recordTable = ($searchOption['year'] == 'this') ? constDB::PLAYER_REC_H_2012 : constDB::PLAYER_REC_H_2011 ;
		}
		
		$tmpstr = '';

		if($searchOption['pos'])
		{
			$tmpstr .= sprintf(" AND A.%s = 1 ", $searchOption['pos']);
		}
		
		if(!$searchOption['showAll'])
		{
			$tmpstr .= sprintf(" AND A.no < 100 ");
		}

		$sql = sprintf("SELECT COUNT(*) AS rowTotal FROM %s AS A LEFT JOIN %s AS B USING(id) 
						LEFT JOIN %s AS C ON A.id=C.player_id
						WHERE 1 %s",
						constDB::PLAYER_BASEDATA, $recordTable, constDB::TEAM_PLAYER_INDEX, 
						$tmpstr);
		if($stmt = $this->objPDO->query($sql))
		{
			$data = $stmt->fetch(PDO::FETCH_ASSOC);
			$rowTotal = $data['rowTotal'];
		}
		
		$sql = sprintf("SELECT * FROM %s AS A LEFT JOIN %s AS B USING(id) 
						LEFT JOIN %s AS C ON A.id=C.player_id
						WHERE 1 %s ORDER BY %s %s LIMIT %d,%d",
						constDB::PLAYER_BASEDATA, $recordTable, constDB::TEAM_PLAYER_INDEX, 
						$tmpstr, $searchOption['orderby'], $searchOption['order'], 
						($searchOption['page'] - 1) * $searchOption['rowPerPage'], $searchOption['rowPerPage']);
		if($stmt = $this->objPDO->query($sql))
		{
			if($stmt->rowCount())
			{ 
				$list = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
			else 
			{
				$list = array();
			}
		}
		$rData['rowTotal']		= $rowTotal; 
		$rData['playerList'] 	= $list;
		
		return $rData;
	}
	
	/**
	 * 
	 * Get DB 2011 record
	 * @param unknown_type $playerType
	 */
	public function get2011RecordList($playerType = 'p')
	{
		$rowNew = array();
		$sql = sprintf("SELECT A.yid,B.* FROM %s AS A LEFT JOIN %s AS B USING(id) 
						WHERE 1 %s",
						constDB::DB_NAME_2011 .'.'.constDB::PLAYER_BASEDATA,
						($playerType == 'p') ? 
						constDB::DB_NAME_2011 .'.'.constDB::PLAYER_REC_P_2011 :
						constDB::DB_NAME_2011 .'.'.constDB::PLAYER_REC_H_2011,
						($playerType == 'p') ? ' AND A.p=1 ' : ' AND A.dh=1 '
						);
		try {
			$stmt = $this->objPDO->query($sql);
			if($stmt)
			{
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
				{
					$rowNew[$row['yid']] = $row;
				}
			}
		}
		catch (Exception $e)
		{
			var_dump($e);
		}
		return $rowNew;
	}
}
?>
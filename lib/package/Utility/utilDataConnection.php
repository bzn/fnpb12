<?php
class utilDataConnection{
	/**
	 * 
	 * Enter description here ...
	 * @var PDO
	 */
	protected $objPDO = NULL;
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function __construct(){
		$this->objPDO = new PDO('mysql:dbname='.constDB::DB_NAME.';host='.constDB::DB_HOST, 
								constDB::DB_USER, 
								constDB::DB_PASSWORD, 
								array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'")
		);
	}
	
	/**
	 * 
	 * 取得該資料表的欄位資訊
	 * @param string $tableName_
	 */
	protected function getColumnInfo($tableName_ = '')
	{
		if (!empty($tableName_))
		{
			//取得所有資料表
			$arrTable = array();
			$sql = sprintf("SHOW TABLES");
			if($stmt = $this->objPDO->query($sql))
			{
				while($row = $stmt->fetch(PDO::FETCH_NUM))
				{
					$arrTable[] = $row[0];
				}
			}
			
			//檢查是否有該資料表
			if(in_array($tableName_, $arrTable))
			{
				$arrColumn = array();
				$sql = sprintf("SHOW COLUMNS FROM %s", $tableName_);
				if($stmt = $this->objPDO->query($sql))
				{
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
					{
						$arrColumn[$row['Field']] = $row;
					}
					return $arrColumn;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
}
?>
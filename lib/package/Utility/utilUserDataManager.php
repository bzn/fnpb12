<?php
if(!function_exists('GetUTCTime'))
{
	include dirname(__FILE__) . '/../../../include/func.php';
}
class utilUserDataManager extends utilDataConnection implements IDataManager
{
	static $utilUserDataManager = null;
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 
	 * @return utilUserDataManager
	 */
	public function sharedDataManager(){
		
		if(!self::$utilUserDataManager)
		{
			self::$utilUserDataManager = new self();
		}
		return self::$utilUserDataManager;
	}
	
	/**
	 * 
	 * Get user data
	 * @param string $account_
	 * @throws ExceptionUser
	 */
	public function getUserData($account_ = ''){
		if(!empty($account_))
		{
			$sql = sprintf("SELECT * FROM %s WHERE 1 AND account='%s'",
							constDB::USER_DATA, $account_);
			if($stmt = $this->objPDO->query($sql))
			{
				if($stmt->rowCount() == 0)
				{
					$datetime = GetUTCTime();
					
					$sql = sprintf("INSERT INTO %s VALUES(NULL,'%s','%s','%s')",
									constDB::USER_DATA, $account_, $datetime, $datetime);
					if($this->objPDO->query($sql))
					{
						$arrUserData['id'] 			= $this->objPDO->lastInsertId();
						$arrUserData['account'] 	= $account_;
						$arrUserData['create_time'] = $datetime;
						$arrUserData['login_time']	= $datetime;
					}
				}
				else 
				{
					$arrUserData = $stmt->fetch(PDO::FETCH_ASSOC);
				}
				
				$arrUserData['myteam'] = utilMyTeamDataManager::sharedDataManager()->getMyTeamByUserId($arrUserData['id']);
				
				return $arrUserData;
			}
		}
		else
		{
			throw new ExceptionUser(ExceptionUser::ACCOUNT_EMPTY);
		}
	}
}
?>
<?php
class utilMyTeamDataManager extends utilDataConnection implements IDataManager
{
	static $utilMyTeamDataManager = null;
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 
	 * @return utilMyTeamDataManager
	 */
	public function sharedDataManager(){
		
		if(!self::$utilMyTeamDataManager)
		{
			self::$utilMyTeamDataManager = new self();
		}
		return self::$utilMyTeamDataManager;
	}
	
	/**
	 * 
	 * 取得我的隊伍資料
	 * @param int $myTeamId
	 * @throws ExceptionMyTeam
	 */
	public function getMyTeamById($myTeamId = 0)
	{
		if ($myTeamId)
		{
			$sql = sprintf("SELECT * FROM %s WHERE 1 AND id=%d",
							constDB::MYTEAM_DATA, $myTeamId);
			if($stmt = $this->objPDO->query($sql))
			{
				if ($stmt->rowCount() > 0)
				{
					return $stmt->fetch(PDO::FETCH_ASSOC);
				}
				else 
				{
					throw new ExceptionMyTeam(ExceptionMyTeam::MYTEAM_NOT_EXIST);
				}
			}
		}
		else
		{
			throw new ExceptionMyTeam(ExceptionMyTeam::MYTEAM_ID_EMPTY);
		}		
	}
	
	/**
	 * 
	 * Get myteam by user id
	 * @param int $userId_
	 */
	public function getMyTeamByUserId($userId_ = 0){
		if($userId_ > 0)
		{
			$sql = sprintf("SELECT B.* FROM %s AS A LEFT JOIN %s AS B ON A.myteam_id=B.id 
							WHERE 1 AND A.user_id=%d",
							constDB::USER_MYTEAM_INDEX, constDB::MYTEAM_DATA, $userId_);
			if($stmt = $this->objPDO->query($sql))
			{
				if($stmt->rowCount() > 0)
				{
					return $stmt->fetchAll(PDO::FETCH_ASSOC);
				}
				else
				{
					return array();
				}
			}
		}
	}
	
	/**
	 * 
	 * Get myteam by user account
	 * @param string $account_
	 */
	public function getMyTeamByAccount(string $account_){
		if(!empty($account_)){
			$userData = utilUserDataManager::sharedDataManager()->getUserData($account_);
			return $this->getMyTeamByUserId($userData['id']);
		}
	}
	
	/**
	 * 
	 * Create myteam
	 * @param int $userId
	 * @param string $myTeamName
	 * @param int $favTeamId
	 */
	public function createMyTeam($userId = 0, $myTeamName = '', $favTeamId = 0){
		if(!function_exists('CreateMyTeam'))
		{
			require dirname(__FILE__) . '/../../../include/func.php';
		}
		return CreateMyTeam($userId, $myTeamName, $favTeamId);
	}
	
	/**
	 * 
	 * Trade player
	 * @param unknown_type $myTeamId_
	 * @param unknown_type $sellId_
	 * @param unknown_type $buyId_
	 * @param unknown_type $buyPos_
	 */
	public function trade($myTeamId_, $sellId_, $buyId_, $buyPos_){
		if(!function_exists('Trade'))
		{
			require dirname(__FILE__) . '/../../../include/func.php';
		}
		return Trade($myTeamId_, $sellId_, $buyId_, $buyPos_);
	}
	
	/**
	 * 
	 * switch def pos
	 * @param int $myTeamId_
	 * @param string $posOrigin_ c, fb, sb, tb, ss, of1, of2, of3, dh
	 * @param string $posDest_ c, fb, sb, tb, ss, of1, of2, of3, dh
	 */
	public function posSwitch($myTeamId_, $posOrigin_, $posDest_)
	{
		if(!function_exists('PosSwitch'))
		{
			require dirname(__FILE__) . '/../../../include/func.php';
		}
		PosSwitch($myTeamId_, $posOrigin_, $posDest_);
	}
}
?>
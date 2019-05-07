<?php
class utilLeagueDataManager extends utilDataConnection implements IDataManager{
	static $utilLeagueDataManager = null;
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 
	 * @return utilLeagueDataManager
	 */
	public function sharedDataManager(){
		
		if(!self::$utilLeagueDataManager)
		{
			self::$utilLeagueDataManager = new self();
		}
		return self::$utilLeagueDataManager;
	}
	
	/**
	 * 
	 * Create league
	 * @param int $userId_
	 * @param int $leagueName_
	 */
	public function createLeague($userId_ = 0, $leagueName_ = ''){
		require dirname(__FILE__) . '/../../../include/func.php';
		return CreateLeague($userId_, $leagueName_);
	}
	
	/**
	 * 
	 * Join league
	 * @param int $myTeamId_
	 * @param int $leagueId_
	 * @param string $password_
	 */
	public function joinLeague($myTeamId_ = 0, $leagueId_ = 0, $password_ = ''){
		require dirname(__FILE__) . '/../../../include/func.php';
		return JoinLeague($myTeamId_, $leagueId_, $password_);
	}
}
?>
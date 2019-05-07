<?php
class utilTeamDataManager extends utilDataConnection implements IDataManager{
	static $utilTeamDataManager = null;
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 
	 * @return utilTeamDataManager
	 */
	public function sharedDataManager(){
		if(!self::$utilTeamDataManager)
		{
			self::$utilTeamDataManager = new self();
		}
		return self::$utilTeamDataManager;
	}
	
	/**
	 * 
	 * Get team id by yahoo team id
	 * @param int $yahooTeamId_
	 * @throws ExceptionTeam
	 */
	public function getTeamId($yahooTeamId_ = 0){
		if($yahooTeamId_ > 0)
		{
			$sql = sprintf("SELECT id FROM %s WHERE 1 AND yid=%d",
							constDB::TEAM_BASEDATA, $yahooTeamId_);
			if($stmt = $this->objPDO->query($sql))
			{
				if($stmt->rowCount() > 0)
				{
					$row = $stmt->fetch(PDO::FETCH_ASSOC);
					return $row['id'];
				}
				else 
				{
					throw new ExceptionTeam(ExceptionTeam::TEAM_NOT_EXIST);
				}
			}
		}
		else
		{
			throw new ExceptionTeam(ExceptionTeam::TEAM_ID_EMPTY);
		}
	}
}
?>
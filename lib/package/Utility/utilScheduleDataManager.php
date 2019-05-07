<?php
class utilScheduleDataManager extends utilDataConnection implements IDataManager{
	
	static $utilScheduleDataManager = null;
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 
	 * @return utilScheduleDataManager
	 */
	public function sharedDataManager(){
		if(!self::$utilScheduleDataManager)
		{
			self::$utilScheduleDataManager = new self();
		}
		return self::$utilScheduleDataManager;
	}
	
	/**
	 * 
	 * 更新賽程資料
	 * @param structSchedule $scheduleData_
	 */
	public function updateScheduleData(structSchedule $scheduleData_)
	{
		$sql = sprintf("SELECT * FROM %s WHERE 1 AND `datetime`='%s' AND away_team_id=%d AND home_team_id=%d",
						constDB::SCHEDULE_DATA, $scheduleData_->datetime, $scheduleData_->away_team_id, $scheduleData_->home_team_id);
		if($stmt = $this->objPDO->query($sql))
		{
			if($stmt->rowCount() > 0)
			{
				$sql = sprintf("UPDATE %s SET yid=%d,away_team_score=%d,home_team_score=%d WHERE 1 
								AND `datetime`='%s' AND away_team_id=%d AND home_team_id=%d",
								constDB::SCHEDULE_DATA, $scheduleData_->yid, $scheduleData_->away_team_score, $scheduleData_->home_team_score, 
								$scheduleData_->datetime, $scheduleData_->away_team_id, $scheduleData_->home_team_id);
				$this->objPDO->query($sql);
			}
			else 
			{
				$sql = sprintf("INSERT INTO %s(yid,`datetime`,away_team_id,home_team_id,away_team_score,home_team_score) VALUES
								(%d,'%s',%d,%d,%d,%d)",
								constDB::SCHEDULE_DATA, $scheduleData_->yid, $scheduleData_->datetime, $scheduleData_->away_team_id, 
								$scheduleData_->home_team_id, $scheduleData_->away_team_score, $scheduleData_->home_team_score);
				$this->objPDO->query($sql);
			}
		}
	}
}
?>
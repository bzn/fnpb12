<?php
/**
 * 
 * 賽程結構
 * @author ahdai
 *
 */
class structSchedule extends stdClass{
	/**
	 * 
	 * 系統流水編號
	 * @var int
	 */
	public $id = NULL;
	
	/**
	 * 
	 * Yahoo NPB game id
	 * @var int
	 */
	public $yid = 0;
	
	/**
	 * 
	 * 比賽時間
	 * @var int
	 */
	public $datetime = 0;
	
	/**
	 * 
	 * 客隊Yahoo team id
	 * @var int
	 */
	public $away_team_id = 0;
	
	/**
	 * 
	 * 主隊Yahoo team id
	 * @var int
	 */
	public $home_team_id = 0;
	
	/**
	 * 
	 * 客隊分數
	 * @var int
	 */
	public $away_team_score = 0;
	
	/**
	 * 
	 * 主隊分數
	 * @var int
	 */
	public $home_team_score = 0;
}
?>
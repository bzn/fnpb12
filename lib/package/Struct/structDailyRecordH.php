<?php
class structDailyRecordH extends stdClass{
	/**
	 * 
	 * 球員id	
	 * @var int
	 */
	public $id = NULL;
	
	/**
	 * 
	 * 日期	
	 * @var int
	 */
	public $date = 0;
		 
	/**
	 * 
	 * 今日買入
	 * @var int
	 */
	public $buy_count = 0;

	/**
	 * 
	 * 今日賣出
	 * @var int
	 */
	public $sell_count = 0;

	/**
	 * 
	 * 積分	
	 * @var int
	 */
	public $points = 0;

	/**
	 * 
	 * 價錢變動
	 * @var int
	 */
	public $pricemove = 0;
	
	/**
	 * 
	 * 試合數
	 * @var int
	 */
	public $g = 0;

	/**
	 * 
	 * 打數	
	 * @var int
	 */
	public $ab = 0;	 

	/**
	 * 
	 * 得點
	 * @var int
	 */
	public $r = 0;
	
	/**
	 * 
	 * 安打
	 * @var int
	 */
	public $h = 0;
	
	/**
	 * 
	 * 二壘打
	 * @var int
	 */
	public $h2 = 0;
	
	/**
	 * 
	 * 三壘打
	 * @var int
	 */
	public $h3 = 0;
	
	/**
	 * 
	 * 本壘打
	 * @var int
	 */
	public $hr = 0;
	
	/**
	 * 
	 * 打點
	 * @var int
	 */
	public $rbi = 0;
	
	/**
	 * 
	 * 四球＋死球
	 * @var int
	 */
	public $bb = 0;
	
	/**
	 * 
	 * 三振
	 * @var int
	 */
	public $k = 0;
	
	/**
	 * 
	 * 盜壘
	 * @var int
	 */
	public $sb = 0;
	
	/**
	 * 
	 * 打率（小數點3位）
	 * @var float
	 */
	public $avg = 0;

	/**
	 * 
	 * 出塁率（小數點3位）
	 * @var float
	 */
	public $obp = 0;
	
	/**
	 * 
	 * 長打率（小數點3位）
	 * @var float
	 */
	public $slg = 0;
	
	/**
	 * 
	 * 綜合攻擊指標（小數點3位）
	 * @var float
	 */
	public $ops = 0;
}
?>
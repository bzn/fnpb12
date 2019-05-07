<?php
class structDailyRecordP extends stdClass{
	/**
	 * 
	 * 球員編號
	 * @var int
	 */
	public $id = 0;

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
	 * 先發 (完投＋試合當初)
	 * @var int
	 */
	public $gs = 0;
	
	/**
	 * 
	 * 勝投
	 * @var int
	 */
	public $w = 0;
	
	/**
	 * 
	 * 敗投
	 * @var int
	 */
	public $l = 0;
	
	/**
	 * 
	 * 救援
	 * @var int
	 */
	public $sv = 0;
	
	/**
	 * 
	 * 中繼
	 * @var int
	 */
	public $hld = 0;
	
	/**
	 * 
	 * 完投
	 * @var int
	 */
	public $cg = 0;
	
	/**
	 * 
	 * 完封(無點勝)
	 * @var int
	 */
	public $sho = 0;
	
	/**
	 * 
	 * 投球回數
	 * @var int
	 */
	public $ip = 0;
	
	/**
	 * 
	 * 投球回數
	 * @var int
	 */
	public $ip2 = 0;
	
	/**
	 * 
	 * 被安打
	 * @var int
	 */
	public $h = 0;
	
	/**
	 * 
	 * 失點
	 * @var int
	 */
	public $r = 0;
	
	/**
	 * 
	 * 自責點
	 * @var int
	 */
	public $er = 0;
	
	/**
	 * 
	 * 被本壘打
	 * @var int
	 */
	public $hr = 0;
	
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
	 * 防禦率(小數點2位)
	 * @var float
	 */
	public $era = 0;
	
	/**
	 * 
	 * (安打+四壞)/投球局數 （小數點2位）
	 * @var float
	 */
	public $whip = 0;
	
	/**
	 * 
	 * 勝場÷（勝場+敗場）（小數點2位）
	 * @var float
	 */
	public $wpct = 0;	
}
?>
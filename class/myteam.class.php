<?php
include_once(dirname(__FILE__)."/../include/init.php");
include_once(dirname(__FILE__)."/../include/func.php");
include_once(dirname(__FILE__)."/db.class.php");

class MyTeam
{
	public $myTeamData;
	public function __construct($myTeamID, $date=null)
	{
		$db = new DB();
		// 取得球隊資料
		$sql = "SELECT * FROM `myteam_data` WHERE id = ".$myTeamID;
		$stmt = $db->query($sql);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);	
		$this->myTeamData = (object)$row;
		
		// date參數可以調出當日資料
		if($date)
		{
			// 取得他人球隊資料
			$sql = "SELECT * FROM `myteam_rec_daily` WHERE myteam_id = ".$myTeamID." AND `date`='".$date."'";
			//echo $sql;
			$stmt = $db->query($sql);
			$row = $stmt->fetch(PDO::FETCH_ASSOC);	
			//$this->myTeamData = (object)$row;
			$this->myTeamData->p1 = $row['p1'];
			$this->myTeamData->p2 = $row['p2'];
			$this->myTeamData->p3 = $row['p3'];
			$this->myTeamData->p4 = $row['p4'];
			$this->myTeamData->p5 = $row['p5'];
			$this->myTeamData->c = $row['c'];
			$this->myTeamData->fb = $row['fb'];
			$this->myTeamData->sb = $row['sb'];
			$this->myTeamData->tb = $row['tb'];
			$this->myTeamData->ss = $row['ss'];
			$this->myTeamData->of1 = $row['of1'];
			$this->myTeamData->of2 = $row['of2'];
			$this->myTeamData->of3 = $row['of3'];
			$this->myTeamData->dh = $row['dh'];
		}
	}

/*
	// 萬用交易
	public function Sell($playerID)
	{
		// 取得交易球員資料
		$playerData = GetPlayerBaseData($playerID);

		// 萬用交易
		// 確認交易次數是否足夠
		if(!$this->myTeamData->trade)
			throw new Exception('ERR_NO_TRADE',ERR_NO_TRADE);

		// 賣出球員
		if($this->myTeamData->p1 == $playerData->id)	$this->myTeamData->p1 = 0;
		else if($this->myTeamData->p2 == $playerData->id)	$this->myTeamData->p2 = 0;
		else if($this->myTeamData->p3 == $playerData->id)	$this->myTeamData->p3 = 0;
		else if($this->myTeamData->p4 == $playerData->id)	$this->myTeamData->p4 = 0;
		else if($this->myTeamData->p5 == $playerData->id)	$this->myTeamData->p5 = 0;
		else if($this->myTeamData->c == $playerData->id)	$this->myTeamData->c = 0;
		else if($this->myTeamData->fb == $playerData->id)	$this->myTeamData->fb = 0;
		else if($this->myTeamData->sb == $playerData->id)	$this->myTeamData->sb = 0;
		else if($this->myTeamData->tb == $playerData->id)	$this->myTeamData->tb = 0;
		else if($this->myTeamData->ss == $playerData->id)	$this->myTeamData->ss = 0;
		else if($this->myTeamData->of1 == $playerData->id)	$this->myTeamData->of1 = 0;
		else if($this->myTeamData->of2 == $playerData->id)	$this->myTeamData->of2 = 0;
		else if($this->myTeamData->of3 == $playerData->id)	$this->myTeamData->of3 = 0;
		else if($this->myTeamData->dh == $playerData->id)	$this->myTeamData->dh = 0;
		else	throw new Exception('ERR_NO_THIS_PLAYER_TO_SELL',ERR_NO_THIS_PLAYER_TO_SELL);

		// 在隊伍備妥&開賽之前不扣交易次數
		// ....

		// 扣交易次數
		$this->myTeamData->trade--;
		
		// 計算現金餘額
		$this->myTeamData->cash += $playerData->nowprice;
		return true;
	}
*/

	// 交換球員位置
	public function PosSwitch($posA, $posB)
	{
		// 投手不受理
		// ....

		// 取得球員id
		if($posA === 'c')	$AID = $this->myTeamData->c;
		else if($posA === 'fb')	$AID = $this->myTeamData->fb;
		else if($posA === 'sb')	$AID = $this->myTeamData->sb;
		else if($posA === 'tb')	$AID = $this->myTeamData->tb;
		else if($posA === 'ss')	$AID = $this->myTeamData->ss;
		else if($posA === 'of1')	$AID = $this->myTeamData->of1;
		else if($posA === 'of2')	$AID = $this->myTeamData->of2;
		else if($posA === 'of3')	$AID = $this->myTeamData->of3;
		else if($posA === 'dh')	$AID = $this->myTeamData->dh;
		
		if($posB === 'c')	$BID = $this->myTeamData->c;
		else if($posB === 'fb')	$BID = $this->myTeamData->fb;
		else if($posB === 'sb')	$BID = $this->myTeamData->sb;
		else if($posB === 'tb')	$BID = $this->myTeamData->tb;
		else if($posB === 'ss')	$BID = $this->myTeamData->ss;
		else if($posB === 'of1')	$BID = $this->myTeamData->of1;
		else if($posB === 'of2')	$BID = $this->myTeamData->of2;
		else if($posB === 'of3')	$BID = $this->myTeamData->of3;
		else if($posB === 'dh')	$BID = $this->myTeamData->dh;

		// 取得球員守備位置
		$playerDataA = GetPlayerBaseData($AID);
		$playerDataB = GetPlayerBaseData($BID);

		// 檢查位置是否合法
		$isAOK = false;
		$isBOK = false;

		if($posA === 'c' && $playerDataB->c)
			$isBOK = true;
		else if($posA === 'fb' && $playerDataB->fb)
			$isBOK = true;
		else if($posA === 'sb' && $playerDataB->sb)
			$isBOK = true;
		else if($posA === 'tb' && $playerDataB->tb)
			$isBOK = true;
		else if($posA === 'ss' && $playerDataB->ss)
			$isBOK = true;
		else if($posA === 'of1' && $playerDataB->of)
			$isBOK = true;
		else if($posA === 'of2' && $playerDataB->of)
			$isBOK = true;
		else if($posA === 'of3' && $playerDataB->of)
			$isBOK = true;
		else if($posA === 'dh' && $playerDataB->dh)
			$isBOK = true;

		if($posB === 'c' && $playerDataA->c)
			$isAOK = true;
		else if($posB === 'fb' && $playerDataA->fb)
			$isAOK = true;
		else if($posB === 'sb' && $playerDataA->sb)
			$isAOK = true;
		else if($posB === 'tb' && $playerDataA->tb)
			$isAOK = true;
		else if($posB === 'ss' && $playerDataA->ss)
			$isAOK = true;
		else if($posB === 'of1' && $playerDataA->of)
			$isAOK = true;
		else if($posB === 'of2' && $playerDataA->of)
			$isAOK = true;
		else if($posB === 'of3' && $playerDataA->of)
			$isAOK = true;
		else if($posB === 'dh' && $playerDataA->dh)
			$isAOK = true;

		if($isAOK && $isBOK)
		{
			// 交換位置
			if($this->myTeamData->c == $AID)	$this->myTeamData->c = $BID;
			else if($this->myTeamData->fb == $AID)	$this->myTeamData->fb = $BID;
			else if($this->myTeamData->sb == $AID)	$this->myTeamData->sb = $BID;
			else if($this->myTeamData->tb == $AID)	$this->myTeamData->tb = $BID;
			else if($this->myTeamData->ss == $AID)	$this->myTeamData->ss = $BID;
			else if($this->myTeamData->of1 == $AID)	$this->myTeamData->of1 = $BID;
			else if($this->myTeamData->of2 == $AID)	$this->myTeamData->of2 = $BID;
			else if($this->myTeamData->of3 == $AID)	$this->myTeamData->of3 = $BID;
			else if($this->myTeamData->dh == $AID)	$this->myTeamData->dh = $BID;

			if($this->myTeamData->c == $BID)	$this->myTeamData->c = $AID;
			else if($this->myTeamData->fb == $BID)	$this->myTeamData->fb = $AID;
			else if($this->myTeamData->sb == $BID)	$this->myTeamData->sb = $AID;
			else if($this->myTeamData->tb == $BID)	$this->myTeamData->tb = $AID;
			else if($this->myTeamData->ss == $BID)	$this->myTeamData->ss = $AID;
			else if($this->myTeamData->of1 == $BID)	$this->myTeamData->of1 = $AID;
			else if($this->myTeamData->of2 == $BID)	$this->myTeamData->of2 = $AID;
			else if($this->myTeamData->of3 == $BID)	$this->myTeamData->of3 = $AID;
			else if($this->myTeamData->dh == $BID)	$this->myTeamData->dh = $AID;
		}
		else
		{
			throw new Exception('錯誤的守備位置',ERR_POS_SWITCH);
		}
	}

	public function Sell($playerID)
	{
		// 取得交易球員資料
		$playerData = GetPlayerBaseData($playerID);

		var_dump($this->myTeamData);
		exit;
		
		// 確認交易次數是否足夠
		//if($playerData->p && !$this->myTeamData->trade_p)
		if($playerData->p && $this->myTeamData->trade_p<=0)
			throw new Exception('投手交易不足',ERR_NO_PITCHER_TRADE);
		else 
		if($playerData->h && $this->myTeamData->trade_h<=0)
			throw new Exception('打者交易不足',ERR_NO_HITTER_TRADE);

		// 賣出球員
		if($this->myTeamData->p1 == $playerData->id)	$this->myTeamData->p1 = 0;
		else if($this->myTeamData->p2 == $playerData->id)	$this->myTeamData->p2 = 0;
		else if($this->myTeamData->p3 == $playerData->id)	$this->myTeamData->p3 = 0;
		else if($this->myTeamData->p4 == $playerData->id)	$this->myTeamData->p4 = 0;
		else if($this->myTeamData->p5 == $playerData->id)	$this->myTeamData->p5 = 0;
		else if($this->myTeamData->c == $playerData->id)	$this->myTeamData->c = 0;
		else if($this->myTeamData->fb == $playerData->id)	$this->myTeamData->fb = 0;
		else if($this->myTeamData->sb == $playerData->id)	$this->myTeamData->sb = 0;
		else if($this->myTeamData->tb == $playerData->id)	$this->myTeamData->tb = 0;
		else if($this->myTeamData->ss == $playerData->id)	$this->myTeamData->ss = 0;
		else if($this->myTeamData->of1 == $playerData->id)	$this->myTeamData->of1 = 0;
		else if($this->myTeamData->of2 == $playerData->id)	$this->myTeamData->of2 = 0;
		else if($this->myTeamData->of3 == $playerData->id)	$this->myTeamData->of3 = 0;
		else if($this->myTeamData->dh == $playerData->id)	$this->myTeamData->dh = 0;
		else	throw new Exception('無此球員交易',ERR_NO_THIS_PLAYER_TO_SELL);

		// 在隊伍備妥&開賽之前不扣交易次數
		if(IsSeasonStart())
		{
			// 扣交易次數
			if($playerData->p)
			{
				$this->myTeamData->trade_p--;
			}
			else
			{
				$this->myTeamData->trade_h--;
			}
		}
		
		// 計算現金餘額
		$this->myTeamData->cash += $playerData->nowprice;
		return true;
	}

	public function Buy($playerID, $buyPos)
	{
		// 取得交易球員資料
		$playerData = GetPlayerBaseData($playerID);
		// 確認金錢是否足夠
		if($this->myTeamData->cash < $playerData->nowprice)
			throw new Exception('現金不足',ERR_NO_CASH_TO_BUY);
		if($playerData->nowprice < 50)
			throw new Exception('球員不正確',ERR_ILLEGAL_PLAYER);
		// 計算現金餘額
		$this->myTeamData->cash -= $playerData->nowprice;

		// 檢查陣中是否已有此名球員
		if($this->myTeamData->p1 == $playerData->id || 
			$this->myTeamData->p2 == $playerData->id ||
			$this->myTeamData->p3 == $playerData->id ||
			$this->myTeamData->p4 == $playerData->id ||
			$this->myTeamData->p5 == $playerData->id ||
			$this->myTeamData->c == $playerData->id ||
			$this->myTeamData->fb == $playerData->id ||
			$this->myTeamData->sb == $playerData->id ||
			$this->myTeamData->tb == $playerData->id ||
			$this->myTeamData->ss == $playerData->id ||
			$this->myTeamData->of1 == $playerData->id ||
			$this->myTeamData->of2 == $playerData->id ||
			$this->myTeamData->of3 == $playerData->id ||
			$this->myTeamData->dh == $playerData->id)
		{
				throw new Exception('重複購買',ERR_ALREADY_BOUGHT);
		}

		// 優先塞到指定的守備位置
		if($buyPos != null)
		{
			if($buyPos === 'p' && $playerData->p)
			{
				if($this->myTeamData->p1 == 0)	$this->myTeamData->p1 = $playerData->id;
				else if($this->myTeamData->p2 == 0)	$this->myTeamData->p2 = $playerData->id;
				else if($this->myTeamData->p3 == 0)	$this->myTeamData->p3 = $playerData->id;
				else if($this->myTeamData->p4 == 0)	$this->myTeamData->p4 = $playerData->id;
				else if($this->myTeamData->p5 == 0)	$this->myTeamData->p5 = $playerData->id;
				else
					throw new Exception('球隊已滿',ERR_NO_SPACE_TO_BUY);
			}
			else if($buyPos === 'c' && $playerData->c && $this->myTeamData->c == 0)
				$this->myTeamData->c = $playerData->id;
			else if($buyPos === 'fb' && $playerData->fb && $this->myTeamData->fb == 0)
				$this->myTeamData->fb = $playerData->id;
			else if($buyPos === 'sb' && $playerData->sb && $this->myTeamData->sb == 0)
				$this->myTeamData->sb = $playerData->id;
			else if($buyPos === 'tb' && $playerData->tb && $this->myTeamData->tb == 0)
				$this->myTeamData->tb = $playerData->id;
			else if($buyPos === 'ss' && $playerData->ss && $this->myTeamData->ss == 0)
				$this->myTeamData->ss = $playerData->id;
			else if($buyPos === 'of' && $playerData->of && $this->myTeamData->of1 == 0)
				$this->myTeamData->of1 = $playerData->id;
			else if($buyPos === 'of' && $playerData->of && $this->myTeamData->of2 == 0)
				$this->myTeamData->of2 = $playerData->id;
			else if($buyPos === 'of' && $playerData->of && $this->myTeamData->of3 == 0)
				$this->myTeamData->of3 = $playerData->id;
			else if($buyPos === 'dh' && $playerData->dh && $this->myTeamData->dh == 0)
				$this->myTeamData->dh = $playerData->id;
			else
				throw new Exception('球隊已滿',ERR_NO_SPACE_TO_BUY);

		}
		// 檢查有無空位（並自動塞入）
		else if($playerData->p)
		{
			if($this->myTeamData->p1 == 0)	$this->myTeamData->p1 = $playerData->id;
			else if($this->myTeamData->p2 == 0)	$this->myTeamData->p2 = $playerData->id;
			else if($this->myTeamData->p3 == 0)	$this->myTeamData->p3 = $playerData->id;
			else if($this->myTeamData->p4 == 0)	$this->myTeamData->p4 = $playerData->id;
			else if($this->myTeamData->p5 == 0)	$this->myTeamData->p5 = $playerData->id;
			else
				throw new Exception('球隊已滿',ERR_NO_SPACE_TO_BUY);
		}
		else if($playerData->c && $this->myTeamData->c == 0)
			$this->myTeamData->c = $playerData->id;
		else if($playerData->fb && $this->myTeamData->fb == 0)
			$this->myTeamData->fb = $playerData->id;
		else if($playerData->sb && $this->myTeamData->sb == 0)
			$this->myTeamData->sb = $playerData->id;
		else if($playerData->tb && $this->myTeamData->tb == 0)
			$this->myTeamData->tb = $playerData->id;
		else if($playerData->ss && $this->myTeamData->ss == 0)
			$this->myTeamData->ss = $playerData->id;
		else if($playerData->of && $this->myTeamData->of1 == 0)
			$this->myTeamData->of1 = $playerData->id;
		else if($playerData->of && $this->myTeamData->of2 == 0)
			$this->myTeamData->of2 = $playerData->id;
		else if($playerData->of && $this->myTeamData->of3 == 0)
			$this->myTeamData->of3 = $playerData->id;
		else if($playerData->dh && $this->myTeamData->dh == 0)
			$this->myTeamData->dh = $playerData->id;
		else
			throw new Exception('球隊已滿',ERR_NO_SPACE_TO_BUY);
		
		return true;
	}

	// 排序球員
	public function SortPlayer()
	{
		$i=0;
		while($i<4)
		{
			if($this->myTeamData->p1)
				break;
			else
			{
				$this->myTeamData->p1 = $this->myTeamData->p2;
				$this->myTeamData->p2 = $this->myTeamData->p3;
				$this->myTeamData->p3 = $this->myTeamData->p4;
				$this->myTeamData->p4 = $this->myTeamData->p5;
				$this->myTeamData->p5 = 0;
			}
			$i++;
		}
		$i=0;
		while($i<3)
		{
			if($this->myTeamData->p2)
				break;
			else
			{
				$this->myTeamData->p2 = $this->myTeamData->p3;
				$this->myTeamData->p3 = $this->myTeamData->p4;
				$this->myTeamData->p4 = $this->myTeamData->p5;
				$this->myTeamData->p5 = 0;
			}
			$i++;
		}
		$i=0;
		while($i<2)
		{
			if($this->myTeamData->p3)
				break;
			else
			{
				$this->myTeamData->p3 = $this->myTeamData->p4;
				$this->myTeamData->p4 = $this->myTeamData->p5;
				$this->myTeamData->p5 = 0;
			}
			$i++;
		}
		$i=0;
		while($i<1)
		{
			if($this->myTeamData->p4)
				break;
			else
			{
				$this->myTeamData->p4 = $this->myTeamData->p5;
				$this->myTeamData->p5 = 0;
			}
			$i++;
		}

		$i=0;
		while($i<2)
		{
			if($this->myTeamData->of1)
				break;
			else
			{
				$this->myTeamData->of1 = $this->myTeamData->of2;
				$this->myTeamData->of2 = $this->myTeamData->of3;
				$this->myTeamData->of3 = 0;
			}
			$i++;
		}
		$i=0;
		while($i<1)
		{
			if($this->myTeamData->of2)
				break;
			else
			{
				$this->myTeamData->of2 = $this->myTeamData->of3;
				$this->myTeamData->of3 = 0;
			}
			$i++;
		}
	}

	public function Update()
	{
		$this->SortPlayer();
		// 更新我的球隊名單，金錢，交易次數
		$db = new DB();
		$sql = "UPDATE `myteam_data` SET 
				cash = ".$this->myTeamData->cash.",
				trade = ".$this->myTeamData->trade.",
				trade_p = ".$this->myTeamData->trade_p.",
				trade_h = ".$this->myTeamData->trade_h.",
				p1 = ".$this->myTeamData->p1.",
				p2 = ".$this->myTeamData->p2.",
				p3 = ".$this->myTeamData->p3.",
				p4 = ".$this->myTeamData->p4.",
				p5 = ".$this->myTeamData->p5.",
				c = ".$this->myTeamData->c.",
				fb = ".$this->myTeamData->fb.",
				sb = ".$this->myTeamData->sb.",
				tb = ".$this->myTeamData->tb.",
				ss = ".$this->myTeamData->ss.",
				of1 = ".$this->myTeamData->of1.",
				of2 = ".$this->myTeamData->of2.",
				of3 = ".$this->myTeamData->of3.",
				dh = ".$this->myTeamData->dh.",
				modified = '".GetUTCTime()."' 
				WHERE id=".$this->myTeamData->id;
		//echo $sql."<BR>";
		$stmt = $db->query($sql);
	}
}
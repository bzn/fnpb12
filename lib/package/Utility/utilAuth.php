<?php
class utilAuth{
	const MD5_SECRET 	= "Ohdada!Fantasy";
	const EXPIRE_TIME 	= 3600;
	
	/**
	 * 
	 * Get base auth token
	 * @return string
	 */
	public function getBaseAuth(){
		$timestamp  = $_SERVER['REQUEST_TIME'];
		$token		= substr(md5( self::MD5_SECRET . $timestamp ), 2, 22);
		return $timestamp . '|' . $token;
	}
	
	/**
	 * 
	 * Check base auth token
	 * @param string $strBaseAuth_
	 */
	public function checkBaseAuth($strBaseAuth_){
		if (!empty($strBaseAuth_))
		{
			$arrBaseAuth = explode("|", $strBaseAuth_);
			$timestamp	 = $arrBaseAuth[0];
			$token		 = $arrBaseAuth[1];
			if($token === substr(md5( self::MD5_SECRET . $timestamp ), 2, 22))
			{
				return true;
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
	
	/**
	 * 
	 * Get access token
	 * @param string $account_
	 * @throws ExceptionAuth
	 */
	public function getAccessToken($account_ = ''){
		if(!empty($account_))
		{
			$account 	= $account_;
			$platform	= substr($account, strpos($account, '@') + 1);
			$expireTime = $_SERVER['REQUEST_TIME'] + self::EXPIRE_TIME;
			//根據平台產出不同的token
			switch ($platform)
			{
				case 'FB':
					$token = substr(md5( self::MD5_SECRET . $account . $expireTime ), 2, 22);
					break;
				default:
					$token = substr(md5( self::MD5_SECRET . $account . $expireTime ), 2, 22);
					break;
			}
			
			return $account . '|' . $expireTime . '|' . $token;
		}
		else
		{
			throw new ExceptionAuth(ExceptionAuth::ACCOUNT_EMPTY);
		}
	}
	
	/**
	 * 
	 * Check access token
	 * @param string $accessToken_
	 */
	public function checkAccessToken($accessToken_ = ''){
		$bCheck = false;
		if(!empty($accessToken_))
		{
			$arrAccessToken = explode("|", $accessToken_);
			$account 		= $arrAccessToken[0];
			$platform		= substr($account, strpos($account, '@') + 1);
			$expireTime 	= $arrAccessToken[1];
			$token 			= $arrAccessToken[2];
			
			//根據平台檢查token
			switch ($platform)
			{
				case 'FB':
					$bCheck = ($token ==  substr(md5( self::MD5_SECRET . $account . $expireTime ), 2, 22)); 
					break;
				default:
					$bCheck = ($token ==  substr(md5( self::MD5_SECRET . $account . $expireTime ), 2, 22));
					break;
			}

		}
		return $bCheck;
	}
	
	/**
	 * 
	 * Get check code
	 * @param string $accessToken_
	 * @param int $userId_
	 * @param int $teamId_
	 */
	public function getCheckCode($accessToken_ = '', $userId_ = 0, $teamId_ = 0){
		return substr(md5( self::MD5_SECRET . $accessToken_ . $userId_ . $teamId_ ), 2, 28);
	}
	
	/**
	 * 
	 * Validate check code
	 * @param string $checkCode_
	 * @param string $accessToken_
	 * @param int $userId_
	 * @param int $teamId_
	 */
	public function validateCheckCode($checkCode_ = '', $accessToken_ = '', $userId_ = 0, $teamId_ = 0){
		$bCheck = false;
		if(!empty($checkCode_))
		{
			$bCheck = ($checkCode_ ==  substr(md5( self::MD5_SECRET . $accessToken_ . $userId_ . $teamId_ ), 2, 28));
		}
		return $bCheck;
	}
}
?>
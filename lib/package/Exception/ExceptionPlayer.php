<?php
class ExceptionPlayer extends Exception
{
	const PLAYER_ID_EMPTY 	= 1;
	const PLAYER_NOT_EXIST	= 2; 
	
	public function __construct($code, $msg='')
	{
		parent::__construct($msg, $code);
		$this->code 	= $code;
		$this->message 	= $msg;
	}	
}
?>
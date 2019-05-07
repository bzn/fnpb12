<?php
class ExceptionUser extends Exception{
	const ACCOUNT_EMPTY 	= 1;
	
	public function __construct($code, $msg='')
	{
		parent::__construct($msg, $code);
		$this->code 	= $code;
		$this->message 	= $msg;
	}
}
?>
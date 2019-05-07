<?php
class ExceptionAuth extends Exception
{
	const BASEAUTH_EMPTY	= 1;
	
	const ACCOUNT_EMPTY 	= 11;
	
	public function __construct($code, $msg='')
	{
		parent::__construct($msg, $code);
		$this->code 	= $code;
		$this->message 	= $msg;
	}
}
?>
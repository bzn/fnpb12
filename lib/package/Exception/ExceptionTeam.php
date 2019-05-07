<?php
class ExceptionTeam extends Exception{
	const TEAM_ID_EMPTY 	= 1;
	const TEAM_NOT_EXIST	= 2;

	public function __construct($code, $msg='')
	{
		parent::__construct($msg, $code);
		$this->code 	= $code;
		$this->message 	= $msg;
	}
}
?>
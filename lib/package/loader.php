<?php
function __autoload($className)
{
	if(strpos($className, 'const' ) !== false)
	{
		require(dirname(__FILE__) . '/Constant/'.str_replace('\\', '/', $className).'.php');
	}
	elseif(strpos($className, 'util') !== false || substr($className, 0 ,1) == 'I')
	{
		require(dirname(__FILE__) . '/Utility/'.str_replace('\\', '/', $className).'.php');
	}
	elseif(strpos($className, 'Exception') !== false)
	{
		require(dirname(__FILE__) . '/Exception/'.str_replace('\\', '/', $className).'.php');
	}
	elseif(strpos($className, 'struct') !== false)
	{
		require(dirname(__FILE__) . '/Struct/'.str_replace('\\', '/', $className).'.php');
	}
}
?>
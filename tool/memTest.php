<?php
ini_set("display_errors", 1);

include './common.php';
include './package/loader.php';

echo memory_get_usage(true) . '<br/>';

var_dump(utilPlayerDataManager::sharedDataManager());

echo memory_get_usage(true) . '<br/>';

var_dump(utilPlayerDataManager::sharedDataManager());

echo memory_get_usage(true) . '<br/>';

var_dump(utilPlayerDataManager::sharedDataManager());

?>
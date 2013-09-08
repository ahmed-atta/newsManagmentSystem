<?php

//define('ROOT_DIR', dirname(__FILE__));

define ('DS', DIRECTORY_SEPARATOR);
$sitepath = realpath(dirname(__FILE__) . DS . '..' . DS) . DS;
define('SERVER',"localhost");
define('USER',"root");
define('PASSWORD',"");
define('DBNAME',"my_news");

function __autoload($class_name)
{
    require_once './../_phplib/'.$class_name.'.class.php';
}

// $db_conn = MySqliDb::call(); 
 $objects = MySqliDb::instance(SERVER,USER,PASSWORD,DBNAME);
 $obj = $objects['obj'];
 $db_conn = $objects['db'];

?>
<?php
/**
 *
 **/
define('ROOT_DIR', dirname(__FILE__).'');
define ('DS', DIRECTORY_SEPARATOR);

define('SERVER',"localhost");
define('USER',"root");
define('PASSWORD',"");
define('DBNAME',"my_news");

function __autoload($class_name)
{
    require_once ROOT_DIR.DS.'_phplib'.DS.$class_name.'.class.php';
}

 //$db_conn = MySqliDb::call(SERVER,USER,PASSWORD,DBNAME); 
$objects = MySqliDb::instance(SERVER,USER,PASSWORD,DBNAME);
$obj = $objects['obj'];
$db_conn = $objects['db'];

$pages= array('index','news','item');
$page = (isset($_GET['page']))? $_GET['page'] : 'index'; 
if(in_array($page,$pages)){
 switch($page)
  {
    case 'news':
    {
        $page='news.php';
        $page_title='الأخبار الرئيسية';
		$page_decsription = "";
    }
    break;
    case 'item':
    {
        $page='newsItem.php';
        $page_title='تفاصيل الأخبار ';
		$page_decsription = "";
	}
    break;
    default:
    {
        $page='news.php';
        $page_title='الأخبار الرئيسية';
		$page_decsription = "";
    }		
    break;
 }
}
//========================================
//------- CATEGORIES 
function getAllCats($obj,$id = 0) {
	//////////////////////////////////////////////////////
	static $cates = array();
	static $tnum = 0;
	$tnum++;
	$result = $obj->query("SELECT id,title FROM ns_categories WHERE cat=$id ORDER BY title");
	if($result){
		foreach($result as $i => $row){
			 $cates[$row['id']] = str_repeat("=",$tnum-1)." ".$row['title'];
			 getAllCats($obj,$row['id']);
		}
	}
	$tnum = $tnum-1;
	return $cates;
}


//////////////////////////////////////////////////////////////// 


?>
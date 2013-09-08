<?php
//////////////////////////////////////////////////////
///////// get all Categories followed by subcategory
function getAllCats($db_conn,$id = 0) {

	//////////////////////////////////////////////////////
	static $cates = array();
	static $tnum = 0;

	$tnum++;
	$result = mysqli_query($db_conn,"SELECT id,title FROM ns_categories WHERE cat=$id ORDER BY title");
	while($row = mysqli_fetch_assoc($result))
	{
	 
	 $cates[$row['id']] = str_repeat("=",$tnum-1)." ".$row['title'];
	 getAllCats($db_conn,$row['id']);
	}
	$tnum = $tnum-1;
	return $cates;
}



//////////////////////////////////////////////////////
function getAllCats1($id=0) 
{

global $db_conn;
$name_t = "categories"; // 
$id_f = "id"; // id 
$name_f = "title"; // 
$parent_f = "cat"; // parent 


//////////////////////////////////////////////////////
static $cates = array();
static $tnum = 0;

$tnum++;
$result = mysqli_query($db_conn,"SELECT id,title FROM categories WHERE cat=$id ORDER BY title");
while($row = mysqli_fetch_assoc($result))
{
 
 $cates[$row[$id_f]] = str_repeat("<br/>",$tnum-1). ($tnum-1)."- ".$row[$name_f];
 getAllCats1($row[$id_f]);
}
$tnum = $tnum-1;
return $cates;
}
//////////////////////////////////////////////////////
///////// get all Categories followed by subcategory
function getAllCats2($id=0) 
{

global $db_conn;

//////////////////////////////////////////////////////
static $cates = array();
static $tnum = 0;

$tnum++;
$result = mysqli_query($db_conn,"SELECT * FROM categories WHERE cat=$id ORDER BY title");
while($row = mysqli_fetch_assoc($result))
{
 
 //$cates[$row['id']] = str_repeat("|",$tnum-1)." ".$row['title'];
 $row['title']=str_repeat("=",$tnum-1)." ".$row['title'];
 $cates[]=$row;
 
 getAllCats2($row['id']);
}
$tnum = $tnum-1;
return $cates;
}

?>
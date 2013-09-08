<?php require_once("./header.php");
//$db_conn = MySqliDb::call(); /// create instance from mysqli

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
if(isset($_GET['action']) && $_GET['action']=='dl')
{
       $catid=$_GET['cid'];
  if(isset($catid))
       {
                //global $db_conn;
                $crs = $obj->query("DELETE FROM categories WHERE id =$catid");
                $rs = $obj->query("DELETE FROM categories WHERE cat =$catid");
                $nrs = $obj->query("DELETE FROM new WHERE category_id =$catid");
                              
                if($crs && $rs && $nrs)
                { echo "<p align='center' style='color:red'>Item Deleted ! </p>";}
                else 
                {
                  echo 'Error';     
                }
        }
               
                        
}
/////////////////////////////////////////////////////////   
   
    echo "<h1> الأقسام  </h1>";
    
    $allcats = getAllCats($obj);
echo "<table class='ar'><tr bgcolor='#eeeeee'><td> القسم </td>".
                                                  "<!--td>متفرع من</td--></tr>";    
foreach($allcats as $key=>$value)
 {
	 //echo "<option value='$key'>$value</option>" ;
     
     echo "<tr><td><a href='items.php?cid=".$key."'>".$value."</a></td><!--td>".$value['cat']."</td-->".
     "<td><a href='edit.php?flag=tc&cid=".$key."'>Edit</a></td>".
     "<td><a href='list.php?action=dl&cid=".$key."' onclick='javascript: return confirmation()'>Delete</a></td></tr>";
}
  echo "</table>";
    
    
    
    
  /*  
    $obj=MySqliDb::instance();   /// create instance from MysqliDb
    $categories =  $obj->query("SELECT * FROM categories"); //getCategories();
    
    
   if($categories != false)
   { 
    echo "<table class='ar'><tr bgcolor='#eeeeee'><td> القسم </td>".
                                                  "<td>متفرع من</td></tr>";
    foreach($categories as $k=>$v)
    {
     echo "<tr><td><a href='items.php?cid=".$v['id']."'>".$v['title']."</a></td><td>".$v['cat']."</td>".
     "<td><a href='edit.php?flag=tc&cid=".$v['id']."'>Edit</a></td>".
     "<td><a href='list.php?action=dl&cid=".$v['id']."' onclick='javascript: return confirmation()'>Delete</a></td></tr>";
    }
    echo "</table>";
   }
*/
             

?>
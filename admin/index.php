<?php header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
  header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
//require_once("../inc/functions.php");
require_once('../config.php');       
  
 //=============================================
 //========   Create New Categorty
 if(isset($_POST['cbs'])){
    global $db_conn ;
    $stmt = $db_conn->prepare("INSERT INTO ns_categories (title,cat)". 
                           "values (?,?)");
    /* Bind our params */
    $stmt->bind_param('si', $title,$cat);
    /* Set our params */
    $title = $_POST['ctitle'];
    $cat = $_POST['ccat'];
    /* Execute the prepared Statement */
    if($stmt->execute()){
		echo "<span style='color:red'>تم إضافة القسم</span>";
	}
 }
// ==================================================
//=========== Update Category
if(isset($_POST['csb'])){
    //global $db_conn;
    $catid= $_POST['cid']; 
    $title= $_POST['ctitle'];
    $ccat= $_POST['ccat'];
    
        $query = "UPDATE ns_categories SET cat='$ccat' ,
										   title='$title'	WHERE id='$catid' ";
		$result=mysqli_query($db_conn,$query);
    if($result){
		echo "<span style='color:red'>تم تعديل القسم</span>";
    }
 }  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title></title>
<script type="text/javascript">
function confirmation()
{
 var msg= confirm("Do you really want to Delete ??");
 if (msg == true)
 {
   return true
 }
 else
 {
   return false;
  }
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style type="text/css">
.ar{
    text-align: right;
    font-family: tahoma;
    font-size: small;
    
}
body 
{
   text-align: right;  
}

</style>
</head>
<body dir="rtl">
<?php
if(isset($_GET['action']) && $_GET['action']=='dl')
{
    $catid=$_GET['cid'];
	if(isset($catid)){
                //global $db_conn;
                $crs = mysqli_query($db_conn,"DELETE FROM ns_categories WHERE id = $catid");  // === mysqli_query($link,$query);
                $rs = mysqli_query($db_conn,"DELETE FROM ns_categories WHERE cat = $catid");
                $nrs = mysqli_query($db_conn,"DELETE FROM ns_news WHERE category_id = $catid");
                              
                if($crs && $rs && $nrs){ 
					echo "<p align='center' style='color:red'>Item Deleted ! </p>";
					
				} else 
                {
                  echo 'Error';     
                }
    }                    
}
/////////////////////////////////////////////////////////   
   
    echo "<h1> الأقسام  </h1>";
    $allcats = getAllCats($obj);
	echo "<table class='ar'><tr bgcolor='#eeeeee'><td> القسم </td><!--td>متفرع من</td--></tr>";    
	foreach($allcats as $key=>$value){
		 //echo "<option value='$key'>$value</option>" ;
		 
		 echo "<tr><td><a href='items.php?cid=".$key."'>".$value."</a></td><!--td>".$value['cat']."</td-->".
		 "<td><a href='index.php?flag=tc&cid=".$key."'>Edit</a></td>".
		 "<td><a href='index.php?action=dl&cid=".$key."' onclick='javascript: return confirmation()'>Delete</a></td></tr>";
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
<!--  /////////////////////////////// -->
<!--   ////////  Categories Table ////////// -->
<hr />
<?php
if (isset($_GET['flag']) && $_GET['flag']=='tc'){
   $cid = $_GET['cid'];
   $citem = $obj->query("SELECT * FROM ns_categories WHERE id= $cid"); //getCategoriesItem($_GET['cid']);
   
 ?>

<!--   //////////////////////////////////////// -->
<!--   ////////  Categories Table ////////// -->
<h1> تعديل قسم </h1>
<h3 style='color:red'><?php echo $citem[0]['title']; ?></h3>
<form name="fcategories" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<table class="ar">
<tr>
	<td>عنوان القسم :</td>
	<td><input type="text" name="ctitle" value="<?php echo $citem[0]['title']; ?>"/></td>
</tr>
<tr>
	<td>متفرع من :</td>
	<td><select name="ccat" id="ccat">
         <option value="0">::: :::</option>
       <?php
foreach($allcats as $key=>$value){
	if($key == $citem[0]['cat'])
		echo "<option value='$key' selected>$value</option>";
	else 
		echo "<option value='$key'>$value</option>";
}
         
    ?>
        </select></td>
</tr>
<tr>
	<td colspan="2" align="center"><br /> 
	<input type='hidden' name='cid' value='<?php echo $_GET['cid']; ?>'/>
    <input type="submit" name="csb" value="تعديل"/>
    <input type="reset" name="cbr" value="تراجع" />
	<a href='index.php'> أضف قسم جديد</a>
    </td>
</tr>
</table>
</form>
<?php  } else {

?>
<h1> أضف قسم جديد </h1>
<form name="fcategories" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<table class="ar">
<tr>
	<td>عنوان القسم :</td>
	<td><input type="text" name="ctitle" /></td>
</tr>
<tr>
	<td>متفرع من :</td>
	<td><select name="ccat" id="ccat">
         <option value="0">::: :::</option>
<?php
foreach($allcats as $k => $val){
	 echo "<option value='$k'>$val</option>" ;
}        
?>
        </select>
		</td>
</tr>
<tr>
	<td colspan="2" align="center"><br /> 
    <input type="submit" name="cbs" value="تسجيل"/>
    <input type="reset" name="cbr" value="تراجع" />
    </td>
</tr>
</table>
</form>
<?php
}
?>


<!--   //////////////////////////////////////// -->
</body>
</html>  
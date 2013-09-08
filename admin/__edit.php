<?php header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
      header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past 
require_once("../config.php");  
$db_conn = MySqliDb::call();  // mysqli
$obj=MySqliDb::instance();  // mysqliDb  


/////////////////////////////////////////////////////////// 
////////// update news submit button 
 if(isset($_POST['nsb']))
 {
    global $db_conn; 
    
    $newid=$_GET['nid'];
    $title=$_POST['ntitle'];
    $short_text=$_POST['nshort_text'];
    $text=$_POST['ntext']; 
    $cat=$_POST['ncat'];
    
    if($cat != 0)
    {
        $query="UPDATE news SET category_id='$cat' WHERE id='$newid' ";
        $rs=mysqli_multi_query($db_conn,$query);     
    }
    
       
    $query="UPDATE news SET title='$title' WHERE id='$newid' ;";
    $query.="UPDATE news SET short_text='$short_text' WHERE id='$newid' ;";
    $query.="UPDATE news SET text='$text' WHERE id='$newid' ";  
    $result=mysqli_multi_query($db_conn,$query);
    if($result)
    {
       header("Location: items.php?cid=".$cat);
       exit;
   
    }
 } 
  
 /////////////////////////////////////////////////////
 //////////// update image
 if(isset($_POST['upimgsb'])) 
 {
    global $db_conn;
    $newid=$_GET['nid'];
    
    ///////// rename image file to timestamp  /////////////
    $fextension= end(explode(".", basename($_FILES['nsmall_pic']['name']))); ////
    $pic_name=time().".".$fextension;
    
    
    $spic="thumb-".$pic_name;
    $bpic=$pic_name;
    
    ///////////////////////////////// Upload Images
    /////////////////////////////////////// UpLoad file
    $target_path = "../images/";
    $target_b = $target_path . $pic_name;; 
    $target_s = $target_path . "thumb-".$pic_name; 

    
     
    $query="UPDATE news SET small_pic='$spic' WHERE id='$newid' ;";
    $query.="UPDATE news SET big_pic='$bpic' WHERE id='$newid' ";
      
    $result=mysqli_multi_query($db_conn,$query);
    if($result)
    {
        $upload=new CFile();
        if($upload->upload('nsmall_pic',$target_b))
        {
          $img=new Image($target_b);
          $img->resize($target_s,150,150); 
        }
        
       unlink('../images/'.$_POST['old_pic']);
       unlink('../images/thumb-'.$_POST['old_pic']);
       
       
    }
       header("Location: edit.php?flag=".$_GET['flag']."&nid=".$_GET['nid']);
      // exit;
    
 }
 /////////////////////////////////////////////////////////////
 /////// update categories submit button
 if(isset($_POST['csb']))
 {
    global $db_conn;
    
    $catid=$_GET['cid']; 
    $title=$_POST['ctitle'];
     
     
    $ccat=$_POST['ccat'];
    if($ccat != 0)
    {
        $query="UPDATE categories SET cat='$ccat' WHERE id='$catid' ";
        $rs=mysqli_multi_query($db_conn,$query);
        
    }
    
      
    $query="UPDATE categories SET title='$title' WHERE id='$catid'";
    $result=mysqli_query($db_conn,$query);
    
    if($result)
    {
       header("Location: list.php");
       exit;
   
    }
 } 
  
  
      
?>   

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<style type="text/css">
body 
{
   text-align: right;  
}

.ar
{
    text-align: right;
    font-family: tahoma;
    font-size: small;
    
}

</style>
</head>
<body dir="rtl">


<?php 

if(isset($_GET['flag']) && $_GET['flag']=='tn')
{
   global $obj;
   $nid = $_GET['nid'];
   $item=  $obj->query("SELECT * FROM news WHERE id=$nid"); //getNewsItem($_GET['nid']);
   
?>

<!-- ////////// NEWS Table ////////////// -->
<h1>تعديل خبر :</h1>

<form name="fnews" action="<?php echo $PHP_SELF;?>" method="POST">

<table class="ar">
<tr>
	<td>العنوان :</td>
	<td><input type="text" name="ntitle" value="<?php echo $item[0]['title']; ?>"/></td>
</tr>
<tr>
	<td>نص مختصر :</td>
	<td><textarea name="nshort_text" cols="20" rows="5"><?php echo $item[0]['short_text']; ?></textarea></td>
</tr>
<tr>
	<td>تقاصيل الخبر:</td>
	<td><textarea name="ntext" cols="40" rows="10"><?php echo $item[0]['text']; ?></textarea></td>
</tr>

<!--tr>
	<td>صوره حجم كبير :</td>
	<td><input type="file" name="nbig_pic" id="nbig_pic"/></td>
</tr-->
<tr>
	<td>القسم :</td>
	<td>
       <select name="ncat" id="ncat">
       <option value="0">::: :::</option>
         <?php
               
              // echo COptions($item[0]['category_id']);
              $allcats = getAllCats();
foreach($allcats as $key=>$value)
 {
	 if($key ==$item[0]['category_id']){$selected="selected";}else{$selected="";}
     
     echo "<option value='$key' $selected>$value</option>" ;
}
      
         
         ?>
       
        </select></td>
</tr>

<tr><td colspan="2" align="center"> <input type="submit" name="nsb" value="تعديل"/>
                     <input type="reset" name="nbr" value="تراجع" />
</td></tr>
</table>
</form>

<table class="ar">
<tr>
	<td>الصوره :</td>
	<td><img src="../images/<?php echo $item[0]['big_pic']?>" /> <br /></td>
</tr>
<tr>
	<td> صوره أخرى :</td>
	<td>
        <form name="fupimg" action="<?php echo $PHP_SELF;?>" 
              method="POST" enctype="multipart/form-data" >
         <input type="hidden" name="MAX_FILE_SIZE" value="500000" />
         <input type="file" name="nsmall_pic" id="nsmall_pic"/>
         <input type="hidden" name="old_pic" value="<?php echo $item[0]['big_pic']?>"/>
         <input type='submit' name="upimgsb" value="Upload" /> 
         </form>
         
         </td>
</tr>
</table>




<?php 
 } 
elseif (isset($_GET['flag']) && $_GET['flag']=='tc')   //// Categories Table
{
   global $obj;
   $cid = $_GET['cid'];
   $citem = $obj->query("SELECT * FROM categories WHERE id=$cid"); //getCategoriesItem($_GET['cid']);
   
 ?>

<!--   //////////////////////////////////////// -->
<!--   ////////  Categories Table ////////// -->

<hr />
<h1> تعديل قسم </h1>

<form name="fcategories" action="<?php echo $PHP_SELF; ?>" method="POST">
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
               
              ///echo COptions($citem[0]['cat']);
              $allcats = getAllCats();
foreach($allcats as $key=>$value)
 {
	 if($key ==$citem[0]['cat']){$selected="selected";}else{$selected="";}
     
     echo "<option value='$key' $selected>$value</option>" ;
}
         
         ?>
       
       
        </select></td>
</tr>

<tr>
	<td colspan="2" align="center"><br /> 
    
     <input type="submit" name="csb" value="تعديل"/>
                     <input type="reset" name="cbr" value="تراجع" />
    
    </td>

</tr>
</table>

</form>


<?php  } ?>





<!--   //////////////////////////////////////// -->
</body>
</html>

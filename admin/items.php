<?php
require_once("./header.php");
echo "<a href='index.php'>BACK >>>>> </a>";
if(isset($_POST['nbs'])){
    $stmt= $db_conn->prepare("INSERT INTO ns_news (title,short_text,text,small_pic,big_pic,date,visits,category_id)". 
                           "values (?,?,?,?,?,?,?,?)");
    /* Bind our params */
    $stmt->bind_param('ssssssii', $title, $short_text,$text,$spic,$bpic,$currDate,$visits,$cat_id);
    
    /* Set our params */
    $title = $_POST['ntitle'];
    $short_text = $_POST['nshort_text'];
    $text = $_POST['ntext'];
    
    ///////// rename image file to timestamp  /////////////
	
    $fextension = end(explode(".", basename($_FILES['nsmall_pic']['name']))); ////
    $pic_name=time().".".$fextension;
    $spic="thumb-".$pic_name;
    $bpic=$pic_name;
    
    $currDate=date("d/m/Y");
    $visits= 0;
    $cat_id=$_POST['ncat'];
    
    /* Execute the prepared Statement */
    $stmt->execute();
    
    ///////////////////////////////// Upload Images
    /////////////////////////////////////// UpLoad file
    $target_path = "../images/";
    $target_b = $target_path . $pic_name;; 
    $target_s = $target_path . "thumb-".$pic_name; 

$upload = new CFile();
if($upload->upload('nsmall_pic',$target_b)){
    $img = new Image($target_b);
    $img->resize($target_s,150,150);
}
 header("Location: items.php?cid=".$cat_id);
/*
if ($_FILES["nsmall_pic"]["error"] > 0)
    {
        echo "Return Code: " . $_FILES["nsmall_pic"]["error"] . "<br />";
    }
    else 
    {
       /**
       * echo "Upload: " . $_FILES["nsmall_pic"]["name"] . "<br />";
       *        echo "Type: " . $_FILES["nsmall_pic"]["type"] . "<br />";
       *        echo "Size: " . ($_FILES["nsmall_pic"]["size"] / 1024) . " Kb<br />";
       *        echo "Stored in: " . $_FILES["nsmall_pic"]["tmp_name"];
       *


      if(move_uploaded_file($_FILES['nsmall_pic']['tmp_name'], $target_b))
      {
        //require_once("image.class.php");
       $img=new Image($target_b);
       $img->resize($target_s,150,150); //imageZoom($target_path,$imgname);
      }  
    }
*/    
}
//============================================
//============== Update New
if(isset($_POST['nsb'])){  
    $newid=$_POST['nid'];
    $title=$_POST['ntitle'];
    $short_text= $_POST['nshort_text'];
    $text=$_POST['ntext']; 
    $cat=$_POST['ncat'];
	
	if(!empty($_FILES['nsmall_pic']['name'])){
		///////// rename image file to timestamp  /////////////
		$fextension= end(explode(".", basename($_FILES['nsmall_pic']['name']))); ////
		$pic_name = time().".".$fextension;
		$spic="thumb-".$pic_name;
		$bpic=$pic_name;
		
		///////////////////////////////// Upload Images
		/////////////////////////////////////// UpLoad file
		$target_path = "../images/";
		$target_b = $target_path . $pic_name;; 
		$target_s = $target_path . "thumb-".$pic_name; 
		$query="UPDATE ns_news SET small_pic ='$spic',big_pic ='$bpic' WHERE id= $newid";
		  
		$result= mysqli_query($db_conn,$query);
		$upload1 = new CFile();
        if($upload1->upload('nsmall_pic',$target_b)){
          $img = new Image($target_b);
          var_dump($img->resize($target_s,150,150)); 
        }
		
		if (file_exists("../images/" . $_POST['old_pic'])){
			unlink('../images/'.$_POST['old_pic']);
			unlink('../images/thumb-'.$_POST['old_pic']);
		}
		
	}
	
    
    $query="UPDATE ns_news SET title='$title',
							   short_text='$short_text', 
							   text='$text',
							   category_id='$cat'
							   WHERE id='$newid'";
 
    $result = mysqli_query($db_conn,$query);
   
    header("Location: items.php?cid=".$cat);
      // exit;
   
 } 
  
 /////////////////////////////////////////////////////
 //////////// update image
 if(isset($_POST['upimgsb'])) {
    $newid = $_POST['nid'];
    
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
    $query="UPDATE ns_news SET small_pic ='$spic',big_pic ='$bpic' WHERE id= $newid";
      
    $result= mysqli_query($db_conn,$query);
    if($result){
        $upload1 = new CFile();
        if($upload1->upload('nsmall_pic',$target_b)){
			$img = new Image($target_b);
			$img->resize($target_s,150,150); 
        }
		unlink('../images/'.$_POST['old_pic']);
		unlink('../images/thumb-'.$_POST['old_pic']);
    }
       header("Location: items.php?flag=".$_GET['flag']."&cid=".$_POST['cid']);
      // exit;
    
 }
 /////////////////////////////////////////////////////////////
 
//========================================= 
//============= Delete news item
if(isset($_GET['action']) && $_GET['action'] == 'dl')
{
    $newid=$_GET['nid'];
    if(isset($newid)){
        $result= mysqli_query($db_conn,"DELETE FROM ns_news WHERE id =$newid");
        if($result){ 
			echo "<p align='center' style='color:red'>تم خذف الخبر ! </p>";}
        else {
            echo 'Error';     
        }
    }         
}

///////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////   
 if(isset($_GET['cid']))  {
    $cid=$_GET['cid'];
    $query="SELECT ns_news.*,ns_categories.title AS Ctitle FROM ns_news,ns_categories ";
    $query.="WHERE ns_news.category_id= ns_categories.id AND ns_news.category_id='$cid'";
    
    $news= $obj->query($query);    //getNews($_GET['cid']);
   if(is_array($news)){
    echo "<h3 style='color:red'> أخبار".$news[0]['Ctitle']."</h3>";
    echo "<table class='ar'><tr bgcolor='#eeeeee'><td>عنوان الخبر </td>".
                                "<td>نص مختصر</td>".
                                "<td>التفاصيل</td>".
                                "<td>الصوره</td>".
                                "<td>التاريخ </td>".
                                "<td>عدد الزيارات </td>".
                                "<td>القسم</td>".
                                "</tr>";
    
    foreach($news as $key=>$value){
        echo "<tr><td>".$value['title']."</td><td>".$value['short_text']."</td>".
             "<td>".$value['text']."</td><td>".$value['small_pic']."</td>".
             "<td>".$value['date']."</td><td>".$value['visits']."</td>".
             "<td>".$value['Ctitle']."</td>".
            "<td><a href='items.php?flag=tn&cid=".$_GET['cid']."&nid=".$value['id']."'>Edit</a></td><td><a href='items.php?action=dl&nid=".$value['id']."&cid=".$_GET['cid']."' onclick='javascript: return confirmation()'>Delete</a></td></tr>";
    }
    echo "</table>"; 
   } else 
   {
     echo " <h1> EMPTY !</h1>";
   }
}  
?>

<hr/>
<!-- ////////// NEWS Table ////////////// -->
<?php
if(isset($_GET['flag']) && $_GET['flag']=='tn')
{
   global $obj;
   $nid = $_GET['nid'];
   $item=  $obj->query("SELECT * FROM ns_news WHERE id=$nid"); //getNewsItem($_GET['nid']);
   
?>
<!-- ////////// NEWS Table ////////////// -->
<a href='items.php?cid=<?php echo $_GET['cid']; ?>'> أضف خبر جديد</a>
<h1>تعديل خبر :</h1>

<form name="fnews" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" enctype="multipart/form-data">
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
<tr>
	<table class="ar">
<tr>
	<td>الصوره :</td>
	<td><img src="../images/<?php echo $item[0]['big_pic']?>" /> <br /></td>
</tr>
<tr>
	<td> صوره أخرى :</td>
	<td>
        <!--form name="fupimg" action="<?php echo $_SERVER['PHP_SELF'];?>" 
              method="POST" enctype="multipart/form-data" -->
         <input type="hidden" name="MAX_FILE_SIZE" value="50000000" />
         <input type="file" name="nsmall_pic" id="nsmall_pic"/>
         <input type="hidden" name="old_pic" value="<?php echo $item[0]['big_pic']?>"/>
		 <!--input type="hidden" name="cid" value="<?php echo $_GET['cid']?>"/>
		 <input type="hidden" name="nid" value="<?php echo $_GET['nid']?>"/>
         <input type='submit' name="upimgsb" value="Upload" /> 
         </form-->
         
         </td>
</tr>
</table>
</tr>
<tr>
	<td>القسم :</td>
	<td>
       <select name="ncat" id="ncat">
       <option value="0">::: :::</option>
         <?php
            $allcats = getAllCats($obj);
			foreach($allcats as $key=>$value){
				 if($key ==$item[0]['category_id']){
					$selected="selected";
				 }
				 else{$selected="";}
				 echo "<option value='$key' $selected>$value</option>" ;
			}
         ?>
       
        </select></td>
</tr>
<tr><td colspan="2" align="center"> 
<input type="hidden" name="nid" value="<?php echo $_GET['nid']?>"/>
<input type="submit" name="nsb" value="تعديل"/>
<input type="reset" name="nbr" value="تراجع" />
</td>
</tr>
</table>
</form>


<?php 
 } else {
?>

<h1>أضف خير جديد</h1>
<h3 style='color:red'>قسم <?php 
$categories = getAllCats($obj);
echo $categories[$_GET['cid']]; ?></h3>

<form name="fnews" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="50000000" />

<table class="ar">
<tr>
	<td>العنوان :</td>
	<td><input type="text" name="ntitle"/></td>
</tr>
<tr>
	<td>نص مختصر :</td>
	<td><textarea name="nshort_text" cols="20" rows="5"></textarea></td>
</tr>
<tr>
	<td>تقاصيل الخبر:</td>
	<td><textarea name="ntext" cols="40" rows="10"></textarea></td>
</tr>
<tr>
	<td>الصوره :</td>
	<td><input type="file" name="nsmall_pic" id="nsmall_pic"/></td>
</tr>
<!--tr>
	<td>صوره حجم كبير :</td>
	<td><input type="file" name="nbig_pic" id="nbig_pic"/></td>
</tr-->
<tr>
	<td>القسم :</td>
	<td>
       <select name="ncat" id="ncat" disabled>
       <option value="0">::: :::</option>
<?php
foreach($categories as $key=> $value){
	if($key == $_GET['cid'])
		echo "<option value='$key' selected>$value</option>" ;
	else
		echo "<option value='$key' >$value</option>" ;
}
?>  
  </select></td>
</tr>
<tr><td colspan="2" align="center"> 
<input type='hidden' name="ncat" value='<?php echo $_GET['cid']; ?>'>
<input type="submit" name="nbs" value="تسجيل"/>
<input type="reset" name="nbr" value="تراجع" />
</td></tr>
</table>
</form>
<?php } ?>
<!--   //////////////////////////////////////// -->
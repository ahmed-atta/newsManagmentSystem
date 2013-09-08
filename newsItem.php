<?php 
 function updateVisits($id)
 {
    global $db_conn;
               $result=$db_conn->query("SELECT visits FROM news WHERE id=$id");
               $rows_num=mysqli_num_rows($result);
                        
              if(isset(
              $rows_num) && $rows_num != 0)
               {
                 $row=mysqli_fetch_assoc($result);
                 $visits_num=(int)$row['visits'];
                 $visits= $visits_num+1;
                 
                 $result=$db_conn->query("UPDATE news SET visits ='$visits' WHERE id=$id");
                 return $result;
               }
    
 }  

if(isset($_GET['itemid']))
 {
    $id=$_GET['itemid'];
    $item=$obj->query("SELECT * FROM ns_news WHERE id=$id");        ///getNewsItem($_GET['nid']);
   // updateVisits($_GET['itemid']);        
?> 
<h3><?php echo $item[0]['title']; ?></h3>
<span> بتاريخ <?php echo $item[0]['date']; ?> - زيارات <?php echo $item[0]['visits']; ?></span> <br/>
 <img src="./images/<?php echo $item[0]['big_pic']; ?>" width='400px' height='400px'/>
<div class="ar">
<?php echo $item[0]['text'];  ?>
</div>
<hr />


<?php 

} ///endif
else 
{ 
    
    
}
?>

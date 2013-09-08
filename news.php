<h2 class="head">الأخبار </h2>
<div id="right">

<?php
/**
 * Right Menu
 --------------------------
 */
//$query="SELECT * FROM ns_categories ";
//$rows =$obj->query($query);
$rows = getAllCats($obj);
echo "<ul>";
foreach($rows as $k=>$value){
   echo "<li><a href='news-$k.html'>". $value ."</a></li>";
}
  echo "</ul>";
?>

</div><div  id="left">
<?php
if(!isset($_GET['cid']))
{
/**
 *  Left News
 */
$query2="SELECT * FROM ns_news WHERE category_id='1'";
$rows2 =$obj->query($query2);
foreach($rows2 as $row2)
 {
     echo "<div class='news'>";
     echo "<h5><a href='item-$row2[id].html'>". $row2['title'] ."</a></h5>";
     echo "<img src='./images/". $row2["small_pic"]."' />";
     echo "<p>".$row2['short_text']."</p>";

     echo "</div>";
 }

}
else if(isset($_GET['cid']))
{
   $catid = addslashes($_GET['cid']);
   $query2="SELECT * FROM ns_news WHERE category_id='$catid'";
   $rows2 = $obj->query($query2);
   if($rows2){
		 foreach($rows2 as $row2){
			 echo "<div class='news'>";
			 echo "<h5><a href='item-$row2[id].html'>". $row2['title'] ."</a></h5>";
			 echo "<img src='./images/". $row2["small_pic"]."' />";
			 echo "<p>".$row2['short_text']."</p>";

			 echo "</div>";
		  }
	}
}
?>
</div>
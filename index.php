<?php require_once('config.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title> <?php echo $page_title ?></title>
<?php  echo $page_decsription ;?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" type="text/css" href="./css/default.css" />



</head>
<body dir="rtl">
    <div id="container">
        <div class="menu">
    <ul>
        <li><a href="index.html">Home </a></li>
        <li><a href="./admin/"> Demo admin </a></li>
    </ul>
        </div>

<?php include_once($page); ?>

<div id="footer">
    Copyright&copy; 2009
</div>
</div>

   

</body>
</html>


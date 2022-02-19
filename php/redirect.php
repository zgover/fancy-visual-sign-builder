<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css" />

<style type="text/css">
body{background:none;}
</style>
<?php 

$host=$_ENV['TEST_HOST'];
$username=$_ENV['TEST_USERNAME'];
$password=$_ENV['TEST_PASSWORD'];
$db_name=$_ENV['TEST_DB_NAME'];
$tbl_name=$_ENV['TEST_TABLE_NAME'];

mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");
$result = mysql_query("SELECT MAX(product_id) AS max_id FROM $tbl_name");
$row = mysql_fetch_array($result);
$id = $row["max_id"];
$id = $id + 1;
?>

<div align="center" id="sendtocart">
<h3>Generating product please wait...</h3>
<img src="../images/loading.gif" /></div>
<div align="center"><h4>Sign created!</h4>
<?php 
echo "<a class='btn btn-success' href='".$_ENV['BIGCOMMERCE_STORE_URL']."/cart.php?action=add&product_id=".$id."'>";
?>
Add to cart!</a>
</div>
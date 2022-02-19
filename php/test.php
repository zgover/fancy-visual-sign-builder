<?php 

$host=$_ENV['TEST_HOST'];
$username=$_ENV['TEST_USERNAME'];
$password=$_ENV['TEST_PASSWORD'];
$db_name=$_ENV['TEST_DB_NAME'];
$tbl_name=$_ENV['TEST_TABLE_NAME'];

mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");
$result = mysql_query("SELECT MAX(product_id) AS max_id FROM SignBuilderTable");
$row = mysql_fetch_array($result);
$id = $row["max_id"];
$id = $id +1;
?>


<script>
setTimeout(function(){
  window.top.location.href = "<?php echo $_ENV['BIGCOMMERCE_STORE_URL']; ?>/cart.php?action=add&product_id=<?php echo $id; ?>";
}, 2500);
</script>
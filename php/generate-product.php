<?php
session_start();
$url = $_SESSION["cartURL"];
$id = $_SESSION["productID"];
session_destroy();
?>
<script>
window.top.location.href = "<?php echo $_ENV['BIGCOMMERCE_STORE_URL']; ?>/cart.php?value=add&product_id=<?php echo $id; ?>";
</script>
<?php

//connect to BC
require 'bigcommerce.php';
use Bigcommerce\Api\Client as Bigcommerce;
use Bigcommerce\Api\Resources\ProductImage as ProductImage;

Bigcommerce::configure(array(
'store_url' => $_ENV['BIGCOMMERCE_STORE_URL'],
'username' => $_ENV['BIGCOMMERCE_USERNAME'],
'api_key'   => $_ENV['BIGCOMMERCE_API_KEY']
));
Bigcommerce::setCipher('RC4-SHA');
Bigcommerce::verifyPeer(false);

$texture = $_POST['texture'];
$side = $_POST['sides'];
$price = $_POST['price'];
$price = trim($price, '$');
$textfields = $_POST['textfields'];

$description = "<b>Background texture:</b> " . $texture . "<br />" . "<b>Single or double sided:</b> " . $side . "<br />";

if(isset($textfields) && !empty($textfields)) {
	foreach($textfields as $key => $text_info) {
		$description .= '<b>Font:</b> ' . $text_info['font'] . ' - <b>Size:</b> ' . $text_info['size'] . ' - <b>Color:</b> ' . $text_info['color'] . ' - <b>Text:</b> ' . $text_info['text'] . ' <br />';
		if (strlen($text_info['text']) >= 26) :
			$price = $price + 11;
		endif;
	}
}

$time = time();
$productname = "Custom Sign-" . $time;
$weight = "1";
$category = array(18);

$newproduct = array(
	'name' => $productname,
	'type' => 'physical',
	'price' => $price,
	'weight' => $weight,
	'categories' => $category,
	'availability' => 'available',
	'description' => $description,
	'is_visible' => true
);

//create product
Bigcommerce::createProduct($newproduct);

//search for product just imported by product name (filter)
$keyweord = $time;
$filter = array("keyword_filter" => $keyword);
$products = Bigcommerce::getProducts($filter);
foreach($products as $product) {
    $product->id;
    $productID = $product->id;
}

//generate the product image
$base64_str = substr($_POST['base64_image'], strpos($_POST['base64_image'], ",")+1);
$decoded = base64_decode($base64_str);
$png_url = "product-".$time.".png";
$result = file_put_contents($png_url, $decoded);

//returns the current folder URL
function get_folder_url() {
	$url = $_SERVER['REQUEST_URI']; //returns the current URL
	$parts = explode('/',$url);
	$dir = $_SERVER['SERVER_NAME'];
	for ($i = 0; $i < count($parts) - 1; $i++) {
		$dir .= $parts[$i] . "/";
	}
	return 'http://'.$dir;
}

//generate the full image http path
$png_url = get_folder_url().$png_url;

//post image into product after product has been created
$new_product_image = new ProductImage();
$new_product_image->product_id      = $productID;
$new_product_image->image_file      = $png_url;
$new_product_image->is_thumbnail    = true;
$new_product_image->description     = "";
$product_image = $new_product_image->create();

//insert product id into db to be generated in index.php for redirect to cart
$host="localhost";
$username="signbuilder";
$password="SignBuilder55!";
$db_name="SignBuilder";
$tbl_name="SignBuilderTable";

mysql_connect("$host", "$username", "$password")or die("cannot connect");
mysql_select_db("$db_name")or die("cannot select DB");

$sql = "INSERT INTO $tbl_name(product_id)VALUES('$productID')";
$result=mysql_query($sql);

?>

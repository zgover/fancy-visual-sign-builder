<!DOCTYPE HTML>
   
    <html>
    <head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Fancy Product Designer</title>

    <!-- Style sheets -->
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="css/main.css">

    <!-- Google Webfonts -->
    <link href='http://fonts.googleapis.com/css?family=Gorditas' rel='stylesheet' type='text/css'>

	<!-- jQuery UI - required -->
	<link href="css/jquery-ui.css" rel="stylesheet" />
    <!-- Custom iconic font - required -->
    <link href="css/icon-font.css" rel="stylesheet" />
    <!-- External plugins css - required -->
    <link rel="stylesheet" type="text/css" href="css/plugins.min.css" />
    <!-- The CSS for the plugin itself - required -->
	<link rel="stylesheet" type="text/css" href="css/jquery.fancyProductDesigner.css" />
	<!-- Optional - only when you would like to use custom fonts - optional -->
	<link rel="stylesheet" type="text/css" href="css/jquery.fancyProductDesigner-fonts.css" />

    <!-- Include js files -->
	<script src="js/jquery.min.js" type="text/javascript"></script>
	<script src="js/jquery-ui.min.js" type="text/javascript"></script>
	<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

	<!-- HTML5 canvas library - required -->
	<script src="js/fabric.js" type="text/javascript"></script>
	<!-- The plugin itself - required -->
    <script src="js/jquery.fancyProductDesigner.min.js" type="text/javascript"></script>
    <script type="text/javascript">
	    jQuery(document).ready(function(){

	    	var yourDesigner = $('#clothing-designer').fancyProductDesigner({
	    		editorMode: false,
	    		fonts: ['Arial', 'Fearless', 'Helvetica', 'Times New Roman', 'Verdana', 'Geneva', 'Gorditas'],
	    		customTextParameters: {
		    		colors: true,
		    		removable: true,
		    		resizable: true,
		    		draggable: true,
		    		rotatable: true,
		    		autoCenter: true,
		    		curvable:true,
		    		boundingBox: "Base"
		    	},
	    		customImageParameters: {
		    		draggable: true,
		    		removable: true,
		    		colors: '#000',
		    		autoCenter: true,
		    		boundingBox: "Base"
		    	}
	    	}).data('fancy-product-designer');

	    	//print button
			$('#print-button').click(function(){
				yourDesigner.print();
				return false;
			});

			//create an image
			$('#image-button').click(function(){
				var image = yourDesigner.createImage();
				return false;
			});

			//create a pdf with jsPDF
			$('#pdf-button').click(function(){
				var image = new Image();
				image.src = yourDesigner.getProductDataURL('jpeg', '#ffffff');
				image.onload = function() {
					var doc = new jsPDF();
					doc.addImage(this.src, 'JPEG', 0, 0, this.width * 0.2, this.height * 0.2);
					doc.save('Product.pdf');
				}
				return false;
			});

			//checkout button with getProduct()
			$('#checkout-button').click(function(){
				var product = yourDesigner.getProduct();
				console.log(product);
				return false;
			});

			//event handler when the price is changing
			$('#clothing-designer')
			.bind('priceChange', function(evt, price, currentPrice) {
				$('#thsirt-price').text(currentPrice);
			});

			//recreate button
			$('#recreation-button').click(function(){
				var fabricJSON = JSON.stringify(yourDesigner.getFabricJSON());
				$('#recreation-form input:first').val(fabricJSON).parent().submit();
				return false;
			});

			//click handler for input upload
			$('#upload-button').click(function(){
				$('#design-upload').click();
				return false;
			});

			//save image on webserver
			$('#save-image-php').click(function() {
				$.post( "php/save_image.php", { 
					base64_image: yourDesigner.getProductDataURL(),
					success: function(){};
				});
			});


			//send image via mail
			$('#send-image-mail-php').click(function() {
				$.post( "php/send_image_via_mail.php", { base64_image: yourDesigner.getProductDataURL()} );
			});

			//upload image
			document.getElementById('design-upload').onchange = function (e) {
				if(window.FileReader) {
					var reader = new FileReader();
			    	reader.readAsDataURL(e.target.files[0]);
			    	reader.onload = function (e) {

			    		var image = new Image;
			    		image.src = e.target.result;
			    		image.onload = function() {
				    		var maxH = 400,
			    				maxW = 300,
			    				imageH = this.height,
			    				imageW = this.width,
			    				scaling = 1;

							if(imageW > imageH) {
								if(imageW > maxW) { scaling = maxW / imageW; }
							}
							else {
								if(imageH > maxH) { scaling = maxH / imageH; }
							}

				    		yourDesigner.addElement('image', e.target.result, 'my custom design', {colors: $('#colorizable').is(':checked') ? '#000000' : false, zChangeable: true, removable: true, draggable: true, resizable: true, rotatable: true, autoCenter: true, boundingBox: "Base", scale: scaling});
			    		};
					};
				}
				else {
					alert('FileReader API is not supported in your browser, please use Firefox, Safari, Chrome or IE10!')
				}
			};
	    });
    </script>
    </head>

    <body>
    	<div id="main-container" class="container">
          	<div id="clothing-designer" class="fpd-shadow-1">


          			<div class="fpd-product" title="Circle" data-thumbnail="images/sign/circle-preview.png">
	    			<img src="images/sign/circle.png" title="Base" data-parameters='{"x": 450, "y": 250, "colors": "#D5D5D5,#990000,#cccccc", "price": 20}' />
				</div>

				<div class="fpd-product" title="Rounded Rectangle" data-thumbnail="images/sign/rounded-rectangle-preview.png">
	    			<img src="images/sign/rounded-rectangle.png" title="Base" data-parameters='{"x": 450, "y": 250, "colors": "#D5D5D5,#990000,#cccccc", "price": 20}' />
				</div>

				<div class="fpd-product" title="Badge" data-thumbnail="images/sign/badge-preview.png">
	    			<img src="images/sign/badge.png" title="Base" data-parameters='{"x": 450, "y": 250, "colors": "#D5D5D5,#990000,#cccccc", "price": 20}' />
				</div>
				<div class="fpd-product" title="Circle" data-thumbnail="images/sign/circle-outline-preview.png">
	    			<img src="images/sign/circle-outline.png" title="Base" data-parameters='{"x": 450, "y": 300, "colors": "#D5D5D5,#990000,#cccccc", "price": 20}' />
				</div>



		  		<div class="fpd-design">
		  			<div class="fpd-category" title="Swirls">
			  			<img src="images/designs/swirl.png" title="Swirl" data-parameters='{"zChangeable": true, "x": 215, "y": 200, "colors": "#000000", "removable": true, "draggable": true, "rotatable": true, "resizable": true, "price": 10, "boundingBox": "Base", "autoCenter": true}' />
				  		<img src="images/designs/swirl2.png" title="Swirl 2" data-parameters='{"x": 215, "y": 200, "colors": "#000000", "removable": true, "draggable": true, "rotatable": true, "resizable": true, "price": 5, "boundingBox": "Base", "autoCenter": true}' />
				  		<img src="images/designs/swirl3.png" title="Swirl 3" data-parameters='{"x": 215, "y": 200, "colors": "#000000", "removable": true, "draggable": true, "rotatable": true, "resizable": true, "autoCenter": true}' />
				  		<img src="images/designs/heart_blur.png" title="Heart Blur" data-parameters='{"x": 215, "y": 200, "colors": "#bf0200", "removable": true, "draggable": true, "rotatable": true, "resizable": true, "price": 5, "boundingBox": "Base", "autoCenter": true}' />
				  		<img src="images/designs/converse.png" title="Converse" data-parameters='{"x": 215, "y": 200, "colors": "#000000", "removable": true, "draggable": true, "rotatable": true, "resizable": true, "autoCenter": true}' />
				  		<img src="images/designs/crown.png" title="Crown" data-parameters='{"x": 215, "y": 200, "colors": "#000000", "removable": true, "draggable": true, "rotatable": true, "resizable": true, "autoCenter": true}' />
				  		<img src="images/designs/men_women.png" title="Men hits Women" data-parameters='{"x": 215, "y": 200, "colors": "#000000", "removable": true, "draggable": true, "rotatable": true, "resizable": true, "boundingBox": "Base", "autoCenter": true}' />
		  			</div>
		  			<div class="fpd-category" title="Retro">
			  			<img src="images/designs/retro_1.png" title="Retro One" data-parameters='{"x": 210, "y": 200, "colors": "#000000", "removable": true, "draggable": true, "rotatable": true, "resizable": true, "scale": 0.25, "price": 7, "boundingBox": "Base", "autoCenter": true}' />
				  		<img src="images/designs/retro_2.png" title="Retro Two" data-parameters='{"x": 193, "y": 180, "colors": "#ffffff", "removable": true, "draggable": true, "rotatable": true, "resizable": true, "scale": 0.46, "boundingBox": "Base", "autoCenter": true}' />
				  		<img src="images/designs/retro_3.png" title="Retro Three" data-parameters='{"x": 240, "y": 200, "colors": "#000000", "removable": true, "draggable": true, "rotatable": true, "resizable": true, "scale": 0.25, "price": 8, "boundingBox": "Base", "autoCenter": true}' />
				  		<img src="images/designs/heart_circle.png" title="Heart Circle" data-parameters='{"x": 240, "y": 200, "colors": "#007D41", "removable": true, "draggable": true, "rotatable": true, "resizable": true, "scale": 0.4, "boundingBox": "Base", "autoCenter": true}' />
				  		<img src="images/designs/swirl.png" title="Swirl" data-parameters='{"x": 215, "y": 200, "colors": "#000000", "removable": true, "draggable": true, "rotatable": true, "resizable": true, "price": 10, "boundingBox": "Base", "autoCenter": true}' />
				  		<img src="images/designs/swirl2.png" title="Swirl 2" data-parameters='{"x": 215, "y": 200, "colors": "#000000", "removable": true, "draggable": true, "rotatable": true, "resizable": true, "price": 5, "boundingBox": "Base", "autoCenter": true}' />
				  		<img src="images/designs/swirl3.png" title="Swirl 3" data-parameters='{"x": 215, "y": 200, "colors": "#000000", "removable": true, "draggable": true, "rotatable": true, "resizable": true}' />
				  	</div>
		  		</div>
		  	</div>
		  	<br />
		  	<div class="row">
			  	<div class="pull-left">
			  		<a href="#" id="upload-button" class="btn btn-warning">Upload an image design</a>
			  	</div>

				<div class="pull-right text-right">
				  	<a href="#" id="save-image-php" class="btn btn-success">Buy It Now</a>
			  	</div>

		  	</div>

		  	<!-- The form recreation -->
		  	<input type="file" id="design-upload" style="display: none;" />
			<form action="php/recreation.php" id="recreation-form" method="post">
				<input type="hidden" name="recreation_product" value="" />
			</form>

    	</div>
    </body>
</html>
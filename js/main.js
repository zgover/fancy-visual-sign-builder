$(document).ready(function(){

	/** Convert RGB to HEX */
	var hexDigits = new Array ("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f");

	//Function to convert hex format to a rgb color
	function rgb2hex(rgb) {
	 rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
	 return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
	}

	function hex(x) {
	  return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
	}

	/** Custom Event To Check When The Edit Element Box Appears On The View **/
	(function() {
	    var ev = new $.Event('style'),
	        orig = $.fn.css;
	    $.fn.css = function() {
	        $(this).trigger(ev);
	        return orig.apply(this, arguments);
	    }
	})();

	// Check If Value Is Inch Measurement
	function checkIfMeasurement(textField) {
		var pattern = new RegExp(/([0-9]+\.*[0-9]*\")( x )([0-9]+\.*[0-9]*\")/);
		return pattern.test($.trim(textField));
	}

	// Fancy Product Designer
	var fpd = $('#clothing-designer');

	var yourDesigner = fpd.fancyProductDesigner({
		editorMode: false,
		fonts: ['alegreya', 'cabin', 'chewy', 'chivo', 'germania', 'lobsterOne', 'lobsterTwo','nunito','schoolbell','volkhorn', 'playball', 'saddlebag', 'gauge', 'averia', 'medievalSharp', 'grandHotel', 'boogaloo'],
		customTextParameters: {
			colors: "#000,#fff,#312111,#172245,#9b825d,#b4b4b4,#3e161b,#6d2d7e,#885845,#acaaa3,#e888b2,#c73475,#8e135f,#a999c6,#7c6eac,#ba1829,#ea5b18,#e43e1d,#721e23,#003b24,#494f1f,#006d58,#477283,#7eb627,#008332,#c5dda8,#008cc7,#40E0D0,#b9e0ea,#afd8c1,#ddcf1e,#b29260,#daba7f,#c87620,#ffee9b,#f7ead9,#d6c4bf,#f3a68c,#fee9d9",
			colorizable: true,
    		removable: true,
    		resizable: false,
    		draggable: true,
    		rotatable: false,
    		autoCenter: true,
    		maxLength: 40,
    		textSize: 40,
    		boundingBox: "Fill",
    	},
		customImageParameters: {
    		draggable: false,
    		removable: true,
    		colors: true,
    		colorizable:true,
    		autoCenter: true,
    		boundingBox: "Fill",
    	},
    	labels: {
    		outOfContainmentAlert: 'Please move inside of the sign outline.'
    	}
	}).data('fancy-product-designer');

	// Fancy Product Designer Ready
	// Do Stuff
	fpd.on('ready', function(e, obj) {
		console.log('Fancy Product Designer Ready.');

		// Edit Element Box
		var editElementBox = $('.fpd-context-dialog');

		// Check If Edit Element Box Is Showing, If So Float Designer Right
		editElementBox.bind('style', function(e) {
		    if($(this).attr('style').indexOf('display: none') != -1) {
		    	fpd.css('float', 'none');
		    } else {
		    	fpd.css('float', 'right');
		    }
		});

		// Check What Elements Get Added
		fpd.on('elementAdded', function(e, obj){

			// If A Design Element Got Added Check For Any Other Existing Design Items And Remove Them
			if(obj.source.indexOf('design') != -1) {

				var newElement = obj.id,
					customElements = yourDesigner.getCustomElements();

				if(customElements.length > 0) {
					$.each(customElements, function(key, value){
						if(value.element.source.indexOf('design') != -1 && value.element.id !== newElement) {
							yourDesigner.removeElement(value.element.title);
						}
					});
				}
			}

			if(checkIfMeasurement(obj.title)) {
				var sizeHeader = $('h1.size');
				sizeHeader.text('Measurements: ' + obj.title);
			}

		}); // end of element added

		// Element Selected
		fpd.on('elementSelect', function(e, obj){

			if(typeof obj._element !== 'undefined') {
				console.log('The user selected an image.');
			} else {
				console.log('The user selected a text element.');
			}
		}); // end of element selected


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

		//save image on webserver
		$('#save-image-php').click(function() {
			var sides = $('input[name=sides]:checked', '#options-form').val();
		    var texture = $('input[name=texture]:checked', '#options-form').val();
		    var price = $('#sign-price').text();
		    var product = yourDesigner.getProduct(false);

		    /** Track What Fonts Belong To Each Text Field **/
		    var fontsUsed = {};
		    var textCounter = 0;
		    $.each(product, function(key, value){
		    	if(value.elements.length > 0) {
		    		$(value.elements).each(function(){
		    			$.each($(this), function(key, value){
		    				if(value.type === 'text') {

		    					fontsUsed[textCounter] = {
		    						font: value.parameters.font,
		    						size: value.parameters.textSize,
		    						color: value.parameters.currentColor,
		    						text: value.title
		    					}

		    					textCounter++;
		    				}

		    				if(textCounter > 4) {
		    					alert('Only 4 text fields allowed! Please only include 4 text fields!');
		    				}
		    			});
		    		});
		    	}
		    });

	        $.ajax({
				url: 'php/save_image.php',
				type: 'POST' ,
				data: {
					base64_image: yourDesigner.getProductDataURL(),
					sides: sides,
					texture: texture,
					price: price,
					textfields: fontsUsed
				},
				success: function(response) {
					console.log(response);
				},
				error: function(response){
					console.log(response.responseText);
				}
			});

		});

		//send to cart
		$('#sendtocart').click(function() {
			$( "#formdiv" ).load( "php/test.php" );
		});

		$('input:radio[name="sides"]').change(
		    function(){
				var PriceValue = $( "#sign-price" ).text();
				PriceValue = PriceValue.replace("$", "");
				PriceValue = PriceValue.replace(".00", "");

		        if ($(this).is(':checked') && $(this).val() == 'double') {
					PriceValue = parseInt(PriceValue) + 40;
					$( "#sign-price" ).text('$' + PriceValue + '.00');
		        }
		        else{
					PriceValue = parseInt(PriceValue) - 40;
					$( "#sign-price" ).text('$' + PriceValue + '.00');
		        }
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


	}); // end of fpd ready

}); // end of document ready

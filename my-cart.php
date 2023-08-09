<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (isset($_POST['submitupdatecart'])) {
	if (!empty($_SESSION['cart'])) {
		foreach ($_POST['quantity'] as $key => $quantity) {
			$size = $_POST['size'][$key];
		
			if ($quantity == 0 || $size == 0) {
				unset($_SESSION['cart'][$key]);
			} else {
				$_SESSION['cart'][$key]['quantity'] = $quantity;
				$_SESSION['cart'][$key]['size'] = $size;
			}
		}
		

		echo "<script>alert('Your Cart hasbeen Updated');</script>";
	}
}
// Code for Remove a Product from Cart
if (isset($_POST['remove_code'])) {

	if (!empty($_SESSION['cart'])) {
		foreach ($_POST['remove_code'] as $key) {

			unset($_SESSION['cart'][$key]);
		}
		echo "<script>alert('Your Cart has been Updated');</script>";
		header('location: my-cart.php');

	}
}



// // code for billing address updation
// if (isset($_POST['update'])) {
// 	$baddress = $_POST['billingaddress'];
// 	$bstate = $_POST['bilingstate'];
// 	$bcity = $_POST['billingcity'];
// 	$bpincode = $_POST['billingpincode'];
// 	$query = mysqli_query($con, "update users set billingAddress='$baddress',billingState='$bstate',billingCity='$bcity',billingPincode='$bpincode' where id='" . $_SESSION['id'] . "'");
// 	if ($query) {
// 		echo "<script>alert('Billing Address has been updated');</script>";
// 	}
// }


// code for Shipping address updation
if (isset($_POST['shipupdate'])) {
	$saddress = $_POST['shippingaddress'];
	$sstate = $_POST['shippingstate'];
	$scity = $_POST['shippingcity'];
	$spincode = $_POST['shippingpincode'];
	$query = mysqli_query($con, "update users set shippingAddress='$saddress',shippingState='$sstate',shippingCity='$scity',shippingPincode='$spincode' where id='" . $_SESSION['id'] . "'");
	if ($query) {
		echo "<script>alert('Shipping Address has been updated');</script>";
	}
unset($_POST['shipupdate']);
}
// code for insert product in order table

// var_dump($_SESSION['cart']);
if (isset($_POST['flag'])) {
	if (strlen($_SESSION['login']) == 0) {
		header('location: login.php');
	} else {
		$pdd = $_SESSION['pid'];

		// var_dump($pdd);
		// die();
		foreach ($pdd as $productId) {
			// Retrieve other product information using the product ID
			$productInfo = $_SESSION['cart'][$productId];
			$quantity = $productInfo['quantity'];
			$size = $productInfo['size'];

			// Insert the order details into the 'orders' table for each product
			$sql = "INSERT INTO orders (userId, productId, productSize, paymentMethod, quantity) VALUES ('" . $_SESSION['id'] . "', '$productId', '$size', 'PayPal', '$quantity')";
			mysqli_query($con, $sql);
			// unset($_SESSION['cart'][$productId]);
			// var_dump($size);die();

		}


	}
	unset($_SESSION['cart']);
	unset($_POST['flag']);
	header('location: order-history.php');
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Meta -->
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="keywords" content="MediaCenter, Template, eCommerce">
	<meta name="robots" content="all">

	<title>My Cart</title>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/main.css">
	<link rel="stylesheet" href="assets/css/green.css">
	<link rel="stylesheet" href="assets/css/owl.carousel.css">
	<link rel="stylesheet" href="assets/css/owl.transitions.css">
	<!--<link rel="stylesheet" href="assets/css/owl.theme.css">-->
	<link href="assets/css/lightbox.css" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/animate.min.css">
	<link rel="stylesheet" href="assets/css/rateit.css">
	<link rel="stylesheet" href="assets/css/bootstrap-select.min.css">

	<!-- Demo Purpose Only. Should be removed in production -->
	<link rel="stylesheet" href="assets/css/config.css">

	<link href="assets/css/green.css" rel="alternate stylesheet" title="Green color">
	<link href="assets/css/blue.css" rel="alternate stylesheet" title="Blue color">
	<link href="assets/css/red.css" rel="alternate stylesheet" title="Red color">
	<link href="assets/css/orange.css" rel="alternate stylesheet" title="Orange color">
	<link href="assets/css/dark-green.css" rel="alternate stylesheet" title="Darkgreen color">
	<!-- Demo Purpose Only. Should be removed in production : END -->


	<!-- Icons/Glyphs -->
	<link rel="stylesheet" href="assets/css/font-awesome.min.css">

	<!-- Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,700' rel='stylesheet' type='text/css'>

	<!-- Favicon -->
	<link rel="shortcut icon" href="assets/images/HNPrint_Logo.png">

	<!-- HTML5 elements and media queries Support for IE8 : HTML5 shim and Respond.js -->
	<!--[if lt IE 9]>
			<script src="assets/js/html5shiv.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->

</head>

<body class="cnt-home">



	<!-- ============================================== HEADER ============================================== -->
	<header class="header-style-1">
		<?php include('includes/top-header.php'); ?>
		<?php include('includes/main-header.php'); ?>
		<?php include('includes/menu-bar.php'); ?>
	</header>
	<!-- ============================================== HEADER : END ============================================== -->
	<div class="breadcrumb">
		<div class="container">
			<div class="breadcrumb-inner">
				<ul class="list-inline list-unstyled">
					<li><a href="#">Home</a></li>
					<li class='active'>Shopping Cart</li>
				</ul>
			</div><!-- /.breadcrumb-inner -->
		</div><!-- /.container -->
	</div><!-- /.breadcrumb -->

	<div class="body-content outer-top-xs">
		<div class="container">
			<div class="row inner-bottom-sm">
				<div class="shopping-cart">
					<div class="col-md-12 col-sm-12 shopping-cart-table ">
						<div class="table-responsive">
							<form name="listcart" method="post">
								<?php
								if (!empty($_SESSION['cart'])) {
									?>

									<input type="hidden" name="confirmpay" value="">

									<table class="table table-bordered">
										<thead>
											<tr>
												<th class="cart-romove item">Remove</th>
												<th class="cart-description item">Image</th>
												<th class="cart-product-name item">Product Name</th>
												<th class="cart-size item">Size</th>
												<th class="cart-qty item">Quantity</th>
												<th class="cart-sub-total item">Price Per unit</th>
												<th class="cart-sub-total item">Shipping Charge</th>
												<th class="cart-total last-item">Grandtotal</th>
											</tr>
										</thead><!-- /thead -->
										<tfoot>
											<tr>
												<td colspan="7">
													<div class="shopping-cart-btn">
														<span class="">
															<a href="index.php"
																class="btn btn-upper btn-primary outer-left-xs">Continue
																Shopping</a>
															<input type="submit" name="submitupdatecart"
																value="Update shopping cart"
																class="btn btn-upper btn-primary pull-right outer-right-xs">
														</span>
													</div><!-- /.shopping-cart-btn -->
												</td>
											</tr>
										</tfoot>
										<tbody>
											<?php
											$pdtid = array();
											$sql = "SELECT * FROM products WHERE id IN(";
											foreach ($_SESSION['cart'] as $id => $value) {
												$sql .= $id . ",";
											}
											$sql = substr($sql, 0, -1) . ") ORDER BY id ASC";
											$query = mysqli_query($con, $sql);
											$totalprice = 0;
											$totalqunty = 0;
											if (!empty($query)) {
												while ($row = mysqli_fetch_array($query)) {
													$quantity = $_SESSION['cart'][$row['id']]['quantity'];
													$size = $_SESSION['cart'][$row['id']]['size'];
													$subtotal = $_SESSION['cart'][$row['id']]['quantity'] * $row['productPrice'] + $row['shippingCharge'];
													$totalprice += $subtotal;
													$_SESSION['qnty'] = $totalqunty += $quantity;

													array_push($pdtid, $row['id']);
													//print_r($_SESSION['pid'])=$pdtid;exit;
													?>

													<tr>
														<td class="romove-item"><input type="checkbox" name="remove_code[]"
																value="<?php echo htmlentities($row['id']); ?>" /></td>
														<td class="cart-image">
															<a class="entry-thumbnail" href="detail.html">
																<img src="admin/productimages/<?php echo $row['id']; ?>/<?php echo $row['productImage1']; ?>"
																	alt="" width="114" height="146">
															</a>
														</td>
														<td class="cart-product-name-info">
															<h4 class='cart-product-description'><a
																	href="product-details.php?pid=<?php echo htmlentities($pd = $row['id']); ?>"><?php echo $row['productName'];

																		 $_SESSION['sid'] = $pd;
																		 ?></a></h4>
															<div class="row">
																<div class="col-sm-4">
																	<div class="rating rateit-small"></div>
																</div>
																<br>
																<div class="col-sm-8">
																	<?php $rt = mysqli_query($con, "select * from productreviews where productId='$pd'");

																	$num = mysqli_num_rows($rt); {
																		?>
																		<div class="reviews">
																			(
																			<?php echo htmlentities($num); ?> Reviews )
																		</div>
																	<?php } ?>
																</div>
															</div><!-- /.row -->

														</td>
														<td class="cart-product-quantity">
															<div class="">

															<textarea rows="5"  class="form-control" name="size[<?php echo $row['id']; ?>]"><?php echo $_SESSION['cart'][$row['id']]['size']; ?></textarea>


															</div>
														</td>
														<td class="cart-product-quantity">
															<div class="quant-input">
																<div class="arrows">
																	<div class="arrow plus gradient"><span class="ir"><i
																				class="icon fa fa-sort-asc"></i></span></div>
																	<div class="arrow minus gradient"><span class="ir"><i
																				class="icon fa fa-sort-desc"></i></span></div>
																</div>
																<input type="text"
																	value="<?php echo $_SESSION['cart'][$row['id']]['quantity']; ?>"
																	name="quantity[<?php echo $row['id']; ?>]">

															</div>
														</td>
														<td class="cart-product-sub-total"><span class="cart-sub-total-price">
																<?php echo "RM" . " " . $row['productPrice']; ?>.00
															</span></td>
														<td class="cart-product-sub-total"><span class="cart-sub-total-price">
																<?php echo "RM" . " " . $row['shippingCharge']; ?>.00
															</span></td>

														<td class="cart-product-grand-total"><span class="cart-grand-total-price">
																<?php echo ($_SESSION['cart'][$row['id']]['quantity'] * $row['productPrice'] + $row['shippingCharge']); ?>.00
															</span></td>
													</tr>

												<?php }
											}
											$_SESSION['pid'] = $pdtid;
											?>

										</tbody><!-- /tbody -->
									</table><!-- /table -->
								</form>

							</div>
						</div><!-- /.shopping-cart-table -->


						<form name="shipping" method="post" action="">

							<div class="col-md-12 col-xl-12  justify-right">
								<div class=" col-md-8 estimate-ship-tax">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>
													<span class="estimate-title">Shipping Address</span>
												</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<div class="form-group">
														<?php
														$query = mysqli_query($con, "select * from users where id='" . $_SESSION['id'] . "'");
														while ($row = mysqli_fetch_array($query)) {
															?>

															<div class="form-group">
																<label class="info-title" for="Shipping Address">Shipping
																	Address<span>*</span></label>
																<textarea class="form-control unicase-form-control text-input"
																	name="shippingaddress"
																	required="required"><?php echo $row['shippingAddress']; ?></textarea>
															</div>



															<div class="form-group">
																<label class="info-title" for="Billing State ">Shipping State
																	<span>*</span></label>
																<input type="text"
																	class="form-control unicase-form-control text-input"
																	id="shippingstate" name="shippingstate"
																	value="<?php echo $row['shippingState']; ?>" required>
															</div>
															<div class="form-group">
																<label class="info-title" for="Billing City">Shipping City
																	<span>*</span></label>
																<input type="text"
																	class="form-control unicase-form-control text-input"
																	id="shippingcity" name="shippingcity" required="required"
																	value="<?php echo $row['shippingCity']; ?>">
															</div>
															<div class="form-group">
																<label class="info-title" for="Billing Pincode">Shipping Pincode
																	<span>*</span></label>
																<input type="text"
																	class="form-control unicase-form-control text-input"
																	id="shippingpincode" name="shippingpincode"
																	required="required"
																	value="<?php echo $row['shippingPincode']; ?>">
															</div>


															<button type="submit" name="shipupdate"
																class="btn-upper btn btn-primary checkout-page-button">Update</button>
														<?php } ?>


													</div>

												</td>
											</tr>
										</tbody><!-- /tbody -->
									</table><!-- /table -->
								</div>
						</form>

						<form action="" method="post" name="makepayment">
							<input type="hidden" name="flag" id="flag" value="">
							<div class="col-md-4 col-sm-12 cart-shopping-total">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>

												<div class="cart-grand-total">
													Total Price<span class="inner-left-md">
														<?php echo $_SESSION['tp'] = "$totalprice" . ".00"; ?>
													</span>
												</div>
											</th>
										</tr>
									</thead><!-- /thead -->
									<tbody>
										<tr>
											<td>
												<div class="cart-checkout-btn pull-right">
													<!-- <button type="submit" name="ordersubmit" class="btn btn-primary">PROCCED
														TO
														CHEKOUT</button> -->
													<div id="btn-paypal-checkout"></div>
													<script>
														window.addEventListener("load", function () {



															var total = <?php echo $_SESSION['tp']; ?>;

															// Render the PayPal button
															paypal.Button.render({

																// Set your environment
																env: 'sandbox', // sandbox | production

																// Specify the style of the button
																style: {
																	label: 'checkout',
																	size: 'medium', // small | medium | large | responsive
																	shape: 'pill', // pill | rect
																	color: 'gold', // gold | blue | silver | black,
																	layout: 'vertical'
																},

																// PayPal Client IDs - replace with your own
																// Create a PayPal app: https://developer.paypal.com/developer/applications/create

																client: {
																	sandbox: 'AeI6giOtJtkjOH_lPraZ62E5NNDpZgFeq663NK763eLrhlgmbKYrUQdkgn42JSxi2EQSGxD14W1rYV4E',
																	production: ''
																},

																commit: true,

																payment: function (data, actions) {

																	return actions.payment.create({
																		payment: {
																			transactions: [{
																				amount: {
																					total: '<?php echo $_SESSION['tp']; ?>',
																					currency: 'MYR' // Set the desired currency code
																				}
																			}]
																		}
																	});
																},



																onAuthorize: function (data, actions) {
																	return actions.payment.execute().then(function () {

																		alert('Payment successfully authorized');
																		console.log('123');
																		// Submit form with name "submitcart"
																		document.getElementById('flag').value = '1';


																		document.forms["makepayment"].submit();

																	});
																},


																onCancel: function (data, actions) {
																	console.log(data);
																}

															}, '#btn-paypal-checkout');
														});



													</script>

												</div>
											</td>
										</tr>
									</tbody><!-- /tbody -->
								</table>
							</div>
						</form>

					<?php } else {
									echo "Your shopping Cart is empty";
								} ?>
				</div>
			</div>
		</div>

		<?php echo include('includes/brands-slider.php'); ?>
	</div>
	</div>

	<?php include('includes/footer.php'); ?>

	<script src="assets/js/jquery-1.11.1.min.js"></script>

	<script src="assets/js/bootstrap.min.js"></script>

	<script src="assets/js/bootstrap-hover-dropdown.min.js"></script>
	<script src="assets/js/owl.carousel.min.js"></script>

	<script src="assets/js/echo.min.js"></script>
	<script src="assets/js/jquery.easing-1.3.min.js"></script>
	<script src="assets/js/bootstrap-slider.min.js"></script>
	<script src="assets/js/jquery.rateit.min.js"></script>
	<script type="text/javascript" src="assets/js/lightbox.min.js"></script>
	<script src="assets/js/bootstrap-select.min.js"></script>
	<script src="assets/js/wow.min.js"></script>
	<script src="assets/js/scripts.js"></script>

	<!-- For demo purposes – can be removed on production -->

	<script src="switchstylesheet/switchstylesheet.js"></script>

	<script>
		$(document).ready(function () {
			$(".changecolor").switchstylesheet({ seperator: "color" });
			$('.show-theme-options').click(function () {
				$(this).parent().toggleClass('open');
				return false;
			});
		});

		$(window).bind("load", function () {
			$('.show-theme-options').delay(2000).trigger('click');
		});
	</script>


	<!-- For demo purposes – can be removed on production : End -->
</body>

<!-- Load the required checkout.js script -->
<script src="https://www.paypalobjects.com/api/checkout.js"></script>

</html>
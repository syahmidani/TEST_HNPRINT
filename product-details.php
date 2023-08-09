<?php
session_start();
error_reporting(0);
include('includes/config.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
	$id = intval($_POST['id']);
	$size = '[ ' . $_POST['sizeXS'] . ' : XS ], [ ' . $_POST['sizeS'] . ' : S ], [ ' . $_POST['sizeM'] . ' : M ], [ ' . $_POST['sizeL'] . ' : L ], [ ' . $_POST['sizeXL'] . ' : XL ], [ ' . $_POST['size2XL'] . ' : 2XL ], [ ' . $_POST['size3XL'] . ' : 3XL ], [ ' . $_POST['size4XL'] . ' : 4XL ], [ ' . $_POST['size5XL'] . ' : 5XL ], ';
	$quantityproduct = $_POST['quantityproduct'];


	if (isset($_SESSION['cart'][$id])) {
		$_SESSION['cart'][$id]['quantityproduct']++;
		$_SESSION['cart'][$id]['size']++;
	} else {
		$sql_p = "SELECT * FROM products WHERE id={$id}";
		$query_p = mysqli_query($con, $sql_p);
		if (mysqli_num_rows($query_p) != 0) {
			$row_p = mysqli_fetch_array($query_p);
			$_SESSION['cart'][$row_p['id']] = array(
				"quantity" => $quantityproduct,
				"size" => $size,
				"price" => $row_p['productPrice']
			);
			echo "<script>alert('Product has been added to the cart')</script>";
			echo "<script type='text/javascript'> document.location ='my-cart.php'; </script>";
		} else {
			$message = "Product ID is invalid";
		}

	}

}
$pid = intval($_GET['pid']);
if (isset($_GET['pid']) && $_GET['action'] == "wishlist") {
	if (strlen($_SESSION['login']) == 0) {
		header('location:login.php');
	} else {
		mysqli_query($con, "insert into wishlist(userId,productId) values('" . $_SESSION['id'] . "','$pid')");
		echo "<script>alert('Product aaded in wishlist');</script>";
		header('location:my-wishlist.php');

	}
}
if (isset($_POST['submit'])) {
	$osize = $_POST['size'];
	$qty = $_POST['quality'];
	$price = $_POST['price'];
	$value = $_POST['value'];
	$name = $_POST['name'];
	$summary = $_POST['summary'];
	$review = $_POST['review'];
	mysqli_query($con, "insert into productreviews(productId,quality,price,value,name,summary,review) values('$pid','$qty','$price','$value','$name','$summary','$review')");
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="keywords" content="MediaCenter, Template, eCommerce">
	<meta name="robots" content="all">
	<title>Product Details</title>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/main.css">
	<link rel="stylesheet" href="assets/css/green.css">
	<link rel="stylesheet" href="assets/css/owl.carousel.css">
	<link rel="stylesheet" href="assets/css/owl.transitions.css">
	<link href="assets/css/lightbox.css" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/animate.min.css">
	<link rel="stylesheet" href="assets/css/rateit.css">
	<link rel="stylesheet" href="assets/css/bootstrap-select.min.css">
	<link rel="stylesheet" href="assets/css/config.css">

	<link href="assets/css/green.css" rel="alternate stylesheet" title="Green color">
	<link href="assets/css/blue.css" rel="alternate stylesheet" title="Blue color">
	<link href="assets/css/red.css" rel="alternate stylesheet" title="Red color">
	<link href="assets/css/orange.css" rel="alternate stylesheet" title="Orange color">
	<link href="assets/css/dark-green.css" rel="alternate stylesheet" title="Darkgreen color">
	<link rel="stylesheet" href="assets/css/font-awesome.min.css">

	<!-- Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,700' rel='stylesheet' type='text/css'>
	<link rel="shortcut icon" href="assets/images/HNPrint_Logo.png">
</head>

<body class="cnt-home">

	<header class="header-style-1">

		<!-- ============================================== TOP MENU ============================================== -->
		<?php include('includes/top-header.php'); ?>
		<!-- ============================================== TOP MENU : END ============================================== -->
		<?php include('includes/main-header.php'); ?>
		<!-- ============================================== NAVBAR ============================================== -->
		<?php include('includes/menu-bar.php'); ?>
		<!-- ============================================== NAVBAR : END ============================================== -->

	</header>

	<!-- ============================================== HEADER : END ============================================== -->
	<div class="breadcrumb">
		<div class="container">
			<div class="breadcrumb-inner">
				<?php
				$ret = mysqli_query($con, "select category.categoryName as catname,subCategory.subcategory as subcatname,products.productName as pname from products join category on category.id=products.category join subcategory on subcategory.id=products.subCategory where products.id='$pid'");
				while ($rw = mysqli_fetch_array($ret)) {

					?>


					<ul class="list-inline list-unstyled">
						<li><a href="index.php">Home</a></li>
						<li>
							<?php echo htmlentities($rw['catname']); ?></a>
						</li>
						<li>
							<?php echo htmlentities($rw['subcatname']); ?>
						</li>
						<li class='active'>
							<?php echo htmlentities($rw['pname']); ?>
						</li>
					</ul>
				<?php } ?>
			</div><!-- /.breadcrumb-inner -->
		</div><!-- /.container -->
	</div><!-- /.breadcrumb -->
	<div class="body-content outer-top-xs">
		<div class='container'>
			<div class='row single-product outer-bottom-sm '>
				<div class='col-md-3 sidebar'>
					<div class="sidebar-module-container">


						<!-- ==============================================CATEGORY============================================== -->
						<div class="sidebar-widget outer-bottom-xs wow fadeInUp">
							<h3 class="section-title">Category</h3>
							<div class="sidebar-widget-body m-t-10">
								<div class="accordion">

									<?php $sql = mysqli_query($con, "select id,categoryName  from category");
									while ($row = mysqli_fetch_array($sql)) {
										?>
										<div class="accordion-group">
											<div class="accordion-heading">
												<a href="category.php?cid=<?php echo $row['id']; ?>"
													class="accordion-toggle collapsed">
													<?php echo $row['categoryName']; ?>
												</a>
											</div>

										</div>
									<?php } ?>
								</div>
							</div>
						</div>
						<!-- ============================================== CATEGORY : END ============================================== -->
						<!-- ============================================== HOT DEALS ============================================== -->
						<div class="sidebar-widget hot-deals wow fadeInUp">
							<h3 class="section-title">OUT OF STOCK</h3>
							<div class="owl-carousel sidebar-carousel custom-carousel owl-theme outer-top-xs">

								<?php
								$ret = mysqli_query($con, "SELECT * FROM products WHERE productAvailability != 'In Stock' ORDER BY RAND() LIMIT 4");
								while ($rws = mysqli_fetch_array($ret)) {
									?>

									<div class="item">
										<div class="products">
											<div class="hot-deal-wrapper">
												<div class="image">
													<img src="admin/productimages/<?php echo htmlentities($rws['id']); ?>/<?php echo htmlentities($rws['productImage1']); ?>"
														width="200" height="334" alt="">
												</div>

											</div><!-- /.hot-deal-wrapper -->

											<div class="product-info text-left m-t-20">
												<h3 class="name"><a
														href="product-details.php?pid=<?php echo htmlentities($rws['id']); ?>"><?php echo htmlentities($rws['productName']); ?></a></h3>
												<div class="rating rateit-small"></div>

												<div class="product-price">
													<span class="price">
														RM
														<?php echo htmlentities($rws['productPrice']); ?>.00
													</span>
													<span class="price-before-discount">RM
														<?php echo htmlentities($rws['productPriceBeforeDiscount']); ?>
													</span>

												</div><!-- /.product-price -->

											</div><!-- /.product-info -->

											<div class="cart clearfix animate-effect">
												<div class="action">

													<?php if ($rws['productAvailability'] == 'In Stock') { ?>
														<div class="add-cart-button btn-group">
															<button class="btn btn-primary icon" data-toggle="dropdown"
																type="button">
																<i class="fa fa-shopping-cart"></i>
															</button>
															<a
																href="category.php?page=product&action=add&id=<?php echo $rws['id']; ?>">
																<button class="btn btn-primary" type="button">Add to
																	cart</button>
															</a>
														</div>
													<?php } else { ?>
														<div class="action" style="color:red">Out of Stock</div>
													<?php } ?>

												</div><!-- /.action -->
											</div><!-- /.cart -->
										</div>
									</div>
								<?php } ?>

							</div><!-- /.sidebar-widget -->
						</div>


						<!-- ============================================== COLOR: END ============================================== -->
					</div>
				</div><!-- /.sidebar -->
				<?php
				$ret = mysqli_query($con, "select * from products where id='$pid'");
				while ($row = mysqli_fetch_array($ret)) {

					?>


					<div class='col-md-9'>
						<div class="row  wow fadeInUp">
							<div class="col-xs-12 col-sm-6 col-md-5 gallery-holder">
								<div class="product-item-holder size-big single-product-gallery small-gallery">

									<div id="owl-single-product">

										<div class="single-product-gallery-item" id="slide1">
											<a data-lightbox="image-1"
												data-title="<?php echo htmlentities($row['productName']); ?>"
												href="admin/productimages/<?php echo htmlentities($row['id']); ?>/<?php echo htmlentities($row['productImage1']); ?>">
												<img class="img-responsive" alt="" src="assets/images/blank.gif"
													data-echo="admin/productimages/<?php echo htmlentities($row['id']); ?>/<?php echo htmlentities($row['productImage1']); ?>"
													width="370" height="350" />
											</a>
										</div>




										<div class="single-product-gallery-item" id="slide1">
											<a data-lightbox="image-1"
												data-title="<?php echo htmlentities($row['productName']); ?>"
												href="admin/productimages/<?php echo htmlentities($row['id']); ?>/<?php echo htmlentities($row['productImage1']); ?>">
												<img class="img-responsive" alt="" src="assets/images/blank.gif"
													data-echo="admin/productimages/<?php echo htmlentities($row['id']); ?>/<?php echo htmlentities($row['productImage1']); ?>"
													width="370" height="350" />
											</a>
										</div><!-- /.single-product-gallery-item -->

										<div class="single-product-gallery-item" id="slide2">
											<a data-lightbox="image-1" data-title="Gallery"
												href="admin/productimages/<?php echo htmlentities($row['id']); ?>/<?php echo htmlentities($row['productImage2']); ?>">
												<img class="img-responsive" alt="" src="assets/images/blank.gif"
													data-echo="admin/productimages/<?php echo htmlentities($row['id']); ?>/<?php echo htmlentities($row['productImage2']); ?>" />
											</a>
										</div><!-- /.single-product-gallery-item -->

										<div class="single-product-gallery-item" id="slide3">
											<a data-lightbox="image-1" data-title="Gallery"
												href="admin/productimages/<?php echo htmlentities($row['id']); ?>/<?php echo htmlentities($row['productImage3']); ?>">
												<img class="img-responsive" alt="" src="assets/images/blank.gif"
													data-echo="admin/productimages/<?php echo htmlentities($row['id']); ?>/<?php echo htmlentities($row['productImage3']); ?>" />
											</a>
										</div>

									</div><!-- /.single-product-slider -->


									<div class="single-product-gallery-thumbs gallery-thumbs">

										<div id="owl-single-product-thumbnails">
											<div class="item">
												<a class="horizontal-thumb active" data-target="#owl-single-product"
													data-slide="1" href="#slide1">
													<img class="img-responsive" alt="" src="assets/images/blank.gif"
														data-echo="admin/productimages/<?php echo htmlentities($row['id']); ?>/<?php echo htmlentities($row['productImage1']); ?>" />
												</a>
											</div>

											<div class="item">
												<a class="horizontal-thumb" data-target="#owl-single-product" data-slide="2"
													href="#slide2">
													<img class="img-responsive" width="85" alt=""
														src="assets/images/blank.gif"
														data-echo="admin/productimages/<?php echo htmlentities($row['id']); ?>/<?php echo htmlentities($row['productImage2']); ?>" />
												</a>
											</div>
											<div class="item">

												<a class="horizontal-thumb" data-target="#owl-single-product" data-slide="3"
													href="#slide3">
													<img class="img-responsive" width="85" alt=""
														src="assets/images/blank.gif"
														data-echo="admin/productimages/<?php echo htmlentities($row['id']); ?>/<?php echo htmlentities($row['productImage3']); ?>"
														height="200" />
												</a>
											</div>




										</div><!-- /#owl-single-product-thumbnails -->



									</div>

								</div>
							</div>




							<div class='col-sm-6 col-md-7 product-info-block'>
								<div class="product-info">
									<h1 class="name">
										<?php echo htmlentities($row['productName']); ?>
									</h1>
									<?php $rt = mysqli_query($con, "select * from productreviews where productId='$pid'");
									$num = mysqli_num_rows($rt); {
										?>
										<div class="rating-reviews m-t-20">
											<div class="row">
												<div class="col-sm-3">
													<div class="rating rateit-small"></div>
												</div>
												<div class="col-sm-8">
													<div class="reviews">
														<a href="#" class="lnk">(
															<?php echo htmlentities($num); ?> Reviews)
														</a>
													</div>
												</div>
											</div><!-- /.row -->
										</div><!-- /.rating-reviews -->
									<?php } ?>
									<div class="stock-container info-container m-t-10">
										<div class="row">
											<div class="col-sm-3">
												<div class="stock-box">
													<span class="label">Availability :</span>
												</div>
											</div>
											<div class="col-sm-9">
												<div class="stock-box">
													<span class="value">
														<?php echo htmlentities($row['productAvailability']); ?>
													</span>
												</div>
											</div>
										</div><!-- /.row -->
									</div>



									<div class="stock-container info-container m-t-10">
										<div class="row">
											<div class="col-sm-3">
												<div class="stock-box">
													<span class="label">Shipping Charge :</span>
												</div>
											</div>
											<div class="col-sm-9">
												<div class="stock-box">
													<span class="value">
														<?php if ($row['shippingCharge'] == 0) {
															echo "Free";
														} else {
															echo htmlentities($row['shippingCharge']);
														}

														?>
													</span>
												</div>
											</div>
										</div><!-- /.row -->
									</div>

									<div class="price-container info-container m-t-20">
										<div class="row">


											<div class="col-sm-6">
												<div class="price-box">
													<span class="price">RM
														<?php echo htmlentities($row['productPrice']); ?>
													</span>
													<span class="price-strike">RM
														<?php echo htmlentities($row['productPriceBeforeDiscount']); ?>
													</span>
												</div>
											</div>




											<div class="col-sm-6">
												<div class="favorite-button m-t-10">
													<a class="btn btn-primary" data-toggle="tooltip" data-placement="right"
														title="Wishlist"
														href="product-details.php?pid=<?php echo htmlentities($row['id']) ?>&&action=wishlist">
														<i class="fa fa-heart"></i>
													</a>

													</a>
												</div>
											</div>

										</div><!-- /.row -->
									</div><!-- /.price-container -->



									<form name="addCart" method="post" action="">
										<input type="hidden" name="action" value="add">
										<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
										<div class="quantity-container info-container">
											<div class="row">
												<div class="col-sm-2">
													<span class="label">Size : </span>
												</div>
												<div class="col-sm-10">
													<!-- <input type="number" class="-input" value="" name="S"  placeholder="S" id="sizeInput"> 							 -->
													<div class="row">
														<div class="col-md-4">
															<label for="sizeXS">XS:</label>
															<input type="number" id="sizeXS" name="sizeXS"
																class="form-control size-input" min="0" value="0">
														</div>
														<div class="col-md-4">
															<label for="sizeS">S:</label>
															<input type="number" id="sizeS" name="sizeS"
																class="form-control size-input" min="0" value="0">
														</div>
														<div class="col-md-4">
															<label for="sizeM">M:</label>
															<input type="number" id="sizeM" name="sizeM"
																class="form-control size-input" min="0" value="0">
														</div>
														<div class="col-md-4">
															<label for="sizeL">L:</label>
															<input type="number" id="sizeL" name="sizeL"
																class="form-control size-input" min="0" value="0">
														</div>
														<div class="col-md-4">
															<label for="sizeXL">XL:</label>
															<input type="number" id="sizeXL" name="sizeXL"
																class="form-control size-input" min="0" value="0">
														</div>
														<div class="col-md-4">
															<label for="size2XL">2XL:</label>
															<input type="number" id="size2XL" name="size2XL"
																class="form-control size-input" min="0" value="0">
														</div>
													</div>

													<div class="row">
														<div class="col-md-4">
															<label for="size3XL">3XL:</label>
															<input type="number" id="size3XL" name="size3XL"
																class="form-control size-input" value="0" min="0">
														</div>
														<div class="col-md-4">
															<label for="size4XL">4XL:</label>
															<input type="number" id="size4XL" name="size4XL"
																class="form-control size-input" value="0" min="0">
														</div>
														<div class="col-md-4">
															<label for="size5XL">5XL:</label>
															<input type="number" id="size5XL" name="size5XL"
																class="form-control size-input" value="0" min="0">
														</div>
													</div>
												</div>

											</div><!-- /.row --><br>
											<div class="row">

												<div class="col-sm-2">
													<span class="label">Quantity :</span>
												</div>

												<div class="col-sm-2">
													<div class="cart-quantity">
														<div class="quant-input">
															<div class="arrows">
																<div class="arrow plus gradient"><span class="ir"><i
																			class="icon fa fa-sort-asc"></i></span></div>
																<div class="arrow minus gradient"><span class="ir"><i
																			class="icon fa fa-sort-desc"></i></span></div>
															</div>
															<input type="text" id="totalQuantity" name="quantityproduct"
																value="1">
														</div>
													</div>
												</div>

												<div class="col-sm-7">
													<?php if ($row['productAvailability'] == 'In Stock') { ?>
														<button type="submit" name="addtocart" class="btn btn-primary"><i
																class="fa fa-shopping-cart inner-right-vs"></i> ADD TO CART</a>
														<?php } else { ?>
															<div class="action" style="color:red">Out of Stock</div>
														<?php } ?>
												</div>


											</div><!-- /.row -->
										</div><!-- /.quantity-container -->
									</form>

									<script>
										// JavaScript function to update total quantity based on selected sizes
										function updateTotalQuantity() {
											var sizes = ['sizeXS', 'sizeS', 'sizeM', 'sizeL', 'sizeXL', 'size2XL', 'size3XL', 'size4XL', 'size5XL'];
											var totalQuantity = 0;

											sizes.forEach(function (size) {
												var inputElement = document.getElementById(size);
												if (inputElement) {
													totalQuantity += parseInt(inputElement.value) || 0;
												}
											});

											document.getElementById('totalQuantity').value = totalQuantity;
										}

										// Attach the function to input change events
										var sizeInputs = document.querySelectorAll('.size-input');
										sizeInputs.forEach(function (input) {
											input.addEventListener('change', updateTotalQuantity);
										});

										// Initial calculation
										updateTotalQuantity();
									</script>

									<div class="product-social-link m-t-20 text-right">
										<span class="social-label">Share :</span>
										<div class="social-icons">
											<ul class="list-inline">
												<li><a class="fa fa-facebook"
														href="https://www.facebook.com/profile.php?id=100017703555188&mibextid=ZbWKwL"></a>
												</li>
												<li><a class="fa fa-twitter"
														href="https://www.instagram.com/jahitpukalmurah9.90/?hl=en"></a>
												</li>
												<li><a class="fa fa-linkedin"
														href="https://l.instagram.com/?u=http%3A%2F%2Fwww.wasap.my%2F%2B601111487144%2Fjahitpukaltshirt&e=AT02i26WbroOTOiU9uXZO45JimIENtzbnSuQlnXu9jEtsKVsePwvJaJ1LxCy6ir4tuF-RUqwb7UAGKn5xi-f5_8R7mbF3OvEN-w85Q"></a>
												</li>
												<!-- <li><a class="fa fa-rss" href="#"></a></li> -->
												<!-- <li><a class="fa fa-pinterest" href="#"></a></li> -->
											</ul><!-- /.social-icons -->
										</div>
									</div>




								</div><!-- /.product-info -->
							</div><!-- /.col-sm-7 -->
						</div><!-- /.row -->


						<div class="product-tabs inner-bottom-xs  wow fadeInUp">
							<div class="row">
								<div class="col-sm-3">
									<ul id="product-tabs" class="nav nav-tabs nav-tab-cell">
										<li class="active"><a data-toggle="tab" href="#description">DESCRIPTION</a></li>
										<li><a data-toggle="tab" href="#review">REVIEW</a></li>
									</ul><!-- /.nav-tabs #product-tabs -->
								</div>
								<div class="col-sm-9">

									<div class="tab-content">

										<div id="description" class="tab-pane in active">
											<div class="product-tab">
												<p class="text">
													<?php echo $row['productDescription']; ?>
												</p>
											</div>
										</div><!-- /.tab-pane -->

										<div id="review" class="tab-pane">
											<div class="product-tab">

												<div class="product-reviews">
													<h4 class="title">Customer Reviews</h4>
													<?php $qry = mysqli_query($con, "select * from productreviews where productId='$pid'");
													while ($rvw = mysqli_fetch_array($qry)) {
														?>

														<div class="reviews" style="border: solid 1px #000; padding-left: 2% ">
															<div class="review">
																<div class="review-title"><span class="summary">
																		<?php echo htmlentities($rvw['summary']); ?>
																	</span><span class="date"><i
																			class="fa fa-calendar"></i><span>
																			<?php echo htmlentities($rvw['reviewDate']); ?>
																		</span></span></div>

																<div class="text">"
																	<?php echo htmlentities($rvw['review']); ?>"
																</div>
																<div class="text"><b>Quality :</b>
																	<?php echo htmlentities($rvw['quality']); ?> Star
																</div>
																<div class="text"><b>Price :</b>
																	<?php echo htmlentities($rvw['price']); ?> Star
																</div>
																<div class="text"><b>value :</b>
																	<?php echo htmlentities($rvw['value']); ?> Star
																</div>
																<div class="author m-t-15"><i class="fa fa-pencil-square-o"></i>
																	<span class="name">
																		<?php echo htmlentities($rvw['name']); ?>
																	</span>
																</div>
															</div>

														</div>
													<?php } ?><!-- /.reviews -->
												</div><!-- /.product-reviews -->
												<form role="form" class="cnt-form" name="review" method="post">


													<div class="product-add-review">
														<h4 class="title">Write your own review</h4>
														<div class="review-table">
															<div class="table-responsive">
																<table class="table table-bordered">
																	<thead>
																		<tr>
																			<th class="cell-label">&nbsp;</th>
																			<th>1 star</th>
																			<th>2 stars</th>
																			<th>3 stars</th>
																			<th>4 stars</th>
																			<th>5 stars</th>
																		</tr>
																	</thead>
																	<tbody>
																		<tr>
																			<td class="cell-label">Quality</td>
																			<td><input type="radio" name="quality"
																					class="radio" value="1"></td>
																			<td><input type="radio" name="quality"
																					class="radio" value="2"></td>
																			<td><input type="radio" name="quality"
																					class="radio" value="3"></td>
																			<td><input type="radio" name="quality"
																					class="radio" value="4"></td>
																			<td><input type="radio" name="quality"
																					class="radio" value="5"></td>
																		</tr>
																		<tr>
																			<td class="cell-label">Price</td>
																			<td><input type="radio" name="price"
																					class="radio" value="1"></td>
																			<td><input type="radio" name="price"
																					class="radio" value="2"></td>
																			<td><input type="radio" name="price"
																					class="radio" value="3"></td>
																			<td><input type="radio" name="price"
																					class="radio" value="4"></td>
																			<td><input type="radio" name="price"
																					class="radio" value="5"></td>
																		</tr>
																		<tr>
																			<td class="cell-label">Value</td>
																			<td><input type="radio" name="value"
																					class="radio" value="1"></td>
																			<td><input type="radio" name="value"
																					class="radio" value="2"></td>
																			<td><input type="radio" name="value"
																					class="radio" value="3"></td>
																			<td><input type="radio" name="value"
																					class="radio" value="4"></td>
																			<td><input type="radio" name="value"
																					class="radio" value="5"></td>
																		</tr>
																	</tbody>
																</table><!-- /.table .table-bordered -->
															</div><!-- /.table-responsive -->
														</div><!-- /.review-table -->

														<div class="review-form">
															<div class="form-container">


																<div class="row">
																	<div class="col-sm-6">
																		<div class="form-group">
																			<label for="exampleInputName">Your Name <span
																					class="astk">*</span></label>
																			<input type="text" class="form-control txt"
																				id="exampleInputName" placeholder=""
																				name="name" required="required">
																		</div><!-- /.form-group -->
																		<div class="form-group">
																			<label for="exampleInputSummary">Summary <span
																					class="astk">*</span></label>
																			<input type="text" class="form-control txt"
																				id="exampleInputSummary" placeholder=""
																				name="summary" required="required">
																		</div><!-- /.form-group -->
																	</div>

																	<div class="col-md-6">
																		<div class="form-group">
																			<label for="exampleInputReview">Review <span
																					class="astk">*</span></label>

																			<textarea class="form-control txt txt-review"
																				id="exampleInputReview" rows="4"
																				placeholder="" name="review"
																				required="required"></textarea>
																		</div><!-- /.form-group -->
																	</div>
																</div><!-- /.row -->

																<div class="action text-right">
																	<button name="submit"
																		class="btn btn-primary btn-upper">SUBMIT
																		REVIEW</button>
																</div><!-- /.action -->

												</form><!-- /.cnt-form -->
											</div><!-- /.form-container -->
										</div><!-- /.review-form -->

									</div><!-- /.product-add-review -->

								</div><!-- /.product-tab -->
							</div><!-- /.tab-pane -->



						</div><!-- /.tab-content -->
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.product-tabs -->

			<?php $cid = $row['category'];
			$subcid = $row['subCategory'];
				} ?>
		<!-- ============================================== UPSELL PRODUCTS ============================================== -->
		<section class="section featured-product wow fadeInUp">
			<h3 class="section-title">Realted Products </h3>
			<div class="owl-carousel home-owl-carousel upsell-product custom-carousel owl-theme outer-top-xs">

				<?php
				$qry = mysqli_query($con, "select * from products where subCategory='$subcid' and category='$cid'");
				while ($rw = mysqli_fetch_array($qry)) {

					?>


					<div class="item item-carousel">
						<div class="products">
							<div class="product">
								<div class="product-image">
									<div class="image">
										<a href="product-details.php?pid=<?php echo htmlentities($rw['id']); ?>"><img
												src="assets/images/blank.gif"
												data-echo="admin/productimages/<?php echo htmlentities($rw['id']); ?>/<?php echo htmlentities($rw['productImage1']); ?>"
												width="150" height="240" alt=""></a>
									</div><!-- /.image -->


								</div><!-- /.product-image -->


								<div class="product-info text-left">
									<h3 class="name"><a
											href="product-details.php?pid=<?php echo htmlentities($rw['id']); ?>"><?php echo htmlentities($rw['productName']); ?></a></h3>
									<div class="rating rateit-small"></div>
									<div class="description"></div>

									<div class="product-price">
										<span class="price">
											RM
											<?php echo htmlentities($rw['productPrice']); ?>
										</span>
										<span class="price-before-discount">RM
											<?php echo htmlentities($rw['productPriceBeforeDiscount']); ?>
										</span>

									</div><!-- /.product-price -->

								</div><!-- /.product-info -->
								<div class="cart clearfix animate-effect">
									<div class="action">
										<ul class="list-unstyled">
											<li class="add-cart-button btn-group">
												<button class="btn btn-primary icon" data-toggle="dropdown" type="button">
													<i class="fa fa-shopping-cart"></i>
												</button>
												<a href="product-details.php?page=product&action=add&id=<?php echo $rw['id']; ?>"
													class="lnk btn btn-primary">Add to cart</a>

											</li>


										</ul>
									</div><!-- /.action -->
								</div><!-- /.cart -->
							</div><!-- /.product -->

						</div><!-- /.products -->
					</div><!-- /.item -->
				<?php } ?>


			</div><!-- /.home-owl-carousel -->
		</section><!-- /.section -->

		<!-- ============================================== UPSELL PRODUCTS : END ============================================== -->

	</div><!-- /.col -->
	<div class="clearfix"></div>
	</div>
	<?php include('includes/brands-slider.php'); ?>
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
	<!-- <script>
		// Get the size input element
		const sizeInput = document.getElementById("sizeInput");

		// Initial size value
		let size = "XS";

		// Function to update the size value based on arrow clicks
		function updateSize(direction) {
			const sizes = ["XS", "S", "M", "L", "XL", "2XL", "3XL", "4XL", "5XL"];
			const currentIndex = sizes.indexOf(size);
			if (direction === "up" && currentIndex < sizes.length - 1) {
				size = sizes[currentIndex + 1];
			} else if (direction === "down" && currentIndex > 0) {
				size = sizes[currentIndex - 1];
			}
			sizeInput.value = size;
		}

		// Add event listeners to the arrow elements
		document.querySelector(".arrow.plus").addEventListener("click", () => updateSize("up"));
		document.querySelector(".arrow.minus").addEventListener("click", () => updateSize("down"));
	</script> -->

	<!-- For demo purposes – can be removed on production : End -->

	<!-- <script>
		const sizeInputs = document.querySelectorAll('.size-input');

		sizeInputs.forEach(function (input) {
			input.addEventListener('input', updateTotalQuantity);
		});

		function updateTotalQuantity() {
			let total = 0;

			sizeInputs.forEach(function (input) {
				total += parseInt(input.value) || 0;
			});

			document.getElementById('totalQuantity').textContent = total;
		}
	</script> -->

</body>

</html>
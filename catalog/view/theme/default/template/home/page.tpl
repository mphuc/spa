<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="en"> <!--<![endif]-->

<head>
	<meta charset="utf-8">
	<title>Home Page | Bitwonder</title>

	<meta name="author" content="">
	<meta name="keywords" content="">
	<meta name="description" content="">
	<link rel="shortcut icon" href="catalog/view/theme/default/images/favicon.png">	
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">		
	<link href="catalog/view/theme/default/assets/css/bootstrap.css" rel="stylesheet">
	<link href="catalog/view/theme/default/assets/css/simple-line-icons.css" rel="stylesheet">
	<link href="catalog/view/theme/default/assets/css/font-awesome.css" rel="stylesheet">
	<link href="catalog/view/theme/default/assets/css/prettyPhoto.css" rel="stylesheet" type="text/css" media="all" />
	<link href="catalog/view/theme/default/assets/css/home_style.css" rel="stylesheet"> 
	<link href='http://fonts.googleapis.com/css?family=Raleway:400,700,800&amp;subsetting=all' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,800,700,300' rel='stylesheet' type='text/css'>
	 <script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<!--[if lt IE 9]>
		<script src="./js/html5shiv.js"></script>
		<script src="./js/respond.js"></script>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<![endif]-->
	
</head>

<body data-spy="scroll" data-target=".navigation">
	
	<!-- Banner -->	
    <div id="banner" class="bg-blur">
		<!-- Start Header -->
		<div id="header">
			<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" style="padding:0px;background:#fff" >
				<div class="container">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<!-- Start Logo / Text -->
						<a class="navbar-brand text-logo" style="margin:0px; padding:0px" href="#"><img src="catalog/view/theme/default/images/logo.png" width="100px"></a>

						<!-- End Logo / Text -->
					</div>
					<!-- Start Navigation -->
					<div class="navigation navbar-collapse collapse">
						<ul class="nav navbar-nav menu-right">
							<li class="active"><a href="#banner">Home</a></li>
							<li><a href="#intro">Provide Donation</a></li>
							<li><a href="#features">Support</a></li>
							<li><a href="#subscribe">Infor</a></li>
							<?php if(!$self -> customer -> isLogged()) { ?>
								<li><a href="<?php echo $self -> url -> link('account/login', '', 'SSL') ?>">Login</a></li>
							<?php  } else{?>
								<li><a href="<?php echo $self -> url -> link('account/dashboard', '', 'SSL') ?>">Dashboard</a></li>
							<?php }?>
							<li id="google_translate_element" style="margin-top: 13px;"></li>
							<script>
								function googleTranslateElementInit() {
								  new google.translate.TranslateElement({
								    pageLanguage: 'en',
								    layout: google.translate.TranslateElement.InlineLayout.SIMPLE
								  }, 'google_translate_element');
								}
							</script>
							<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>  
						</ul>
					</div>
					<!-- End Navigation  -->
				</div>
			</nav>
		</div>
		<!-- End Header -->
		<div class="banner-content">
			<div class="container">
				<div class="row">
				
					<div class="col-md-7 col-sm-7" >
						<h2 class="text-uppercase"><strong>Empowering communities</strong></h2>
						<h2 class="text-uppercase text-muted" style="font-weight:normal"> Ending poverty</h2>
						<p style="color:#fff"> Unlike tradding disibution system, we empower the use of <br/>Bitcoin technology into our system for our members.</p>
					</div>
			
				</div>
			</div>
		</div>
    </div>
	<!-- End Banner -->
	
	
	<!-- Intro -->
	<section id="intro" class="section">
		<div class="container">
			<div class="row">
				<div class="col-md-12 headline">
					<h3 class="text-uppercase"><b>Earn your daily profit</b> throughout our services</h3>
					<p><b style="color:#e95d42">Bitwonder</b> is a trusted global community centre, where everyonce will be able to provide help and in return,
					<br/> get help within the <b style="color:#e95d42">Bitwonder</b> commnity</p>
				</div>
			</div>

		</div>
	</section>
	<!-- End Intro -->


	<!-- Features -->
    <section id="features" class="section" >
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-4 col-sm-4 features"><!-- Start Feature -->
					<h5 class="features-text">
						<p><span style="color:#934038; font-weight:700" class="text-uppercase">Provide help (PH) |</span> New Concept</p>
					</h5><!-- Feature Title -->
					<p>Provide Help is a new concept of weath sharing. If you have some extra money to help the community, the community in return will one day provide help back to you. It is a mutal, agreement between the giver and the receiver with no hidden agenda.</p><!-- Feature Text -->
				</div><!-- End Feature -->
				<div class="col-md-4 col-sm-4 features"><!-- Start Feature -->
					<h5 class="features-text">
						<p><span style="color:#934038; font-weight:700" class="text-uppercase">Buy/sell Pin |</span> Incentive Units</p>
					</h5><!-- Feature Title -->
					<p>Pin is a tax currency used in our community, where people can buy and sell pin within themselves thus providing the users to use it as an incentivise unit to grow the network. Each pin is worth about 0.01 BTC.</p><!-- Feature Text -->
				</div><!-- End Feature -->
				<div class="col-md-4 col-sm-4 features"><!-- Start Feature -->
					<h5 class="features-text">
						<p><span style="color:#934038; font-weight:700" class="text-uppercase">Pairing |</span> Make Bitwonder Your Center</p>
					</h5><!-- Feature Title -->
					<p>Pairing bonus is a reward system given to those who qualify if. Marking Bitwonder as your full time ceaeer will reward your width huge monthly pairing bonus allowing you to make this as your livelyhood.</p><!-- Feature Text -->
				</div><!-- End Feature -->
			</div>
		</div>
	</section>
	<!--End Features -->
	
	<!-- Three Main Points -->
	<section id="three-main" class="section bg-blue-pattern white-text">
		<div class="container-fluid">
	
			<div class="row text-center">
				<div class="col-md-6" id="three-main-left" style="border-right: 1px solid #fff;">
					<div class="main-point">
						<h3 class="text-uppercase text-right">Providing help and gettting help</h3>
						<p class="text-right">This concept has been used by many wealth distribution network which equalize profit <br/> distribution using matching algorithms. </p>
						<button style="float:right; border-radius:7px" type="button" class="btn btn-success text-uppercase">Start providing help now </button>
					</div>
				</div>
				<div class="col-md-6" style="border-right: 1px solid #fff;">
					<div class="main-point">
						<h3 class="text-uppercase text-left">Ask us if you need help</h3>
						<p class="text-left">Feel free email us to ask questions in regards to Bitwonder mechaics or security, <br/> or to just sat hello! Wel will reply you within 24 hours. </p>
						<button style="float:left; border-radius:7px" type="button" class="btn btn-success text-uppercase">support@bitwonder.com</button>
					</div>
				</div>			
			</div>
		</div>
	</section>

		
	<!-- Call To Action -->
	<section id="subscribe" class="section ">
		<div class="container-fluid">
	
			<div class="row">
				<div class="col-md-3 col-sm-12 col-xs-12 col-lg-3">
					<img style="float:right" src="catalog/view/theme/default/images/bg-left.jpg">
				</div>
				<div class="col-md-6 col-sm-12 col-xs-12 col-lg-6">
					<h2 class="text-uppercase text-center">Trust is our middle name & <br/> why you should trust Bitwonder</h2>
					<p style="font-size:18px" class="text-center">At Bitwonder, we serve the community at our highest priorti. We optimized the distributin od financial aid among participants through our ground breaking algorithm. The community are the people, and we are the community.</p>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<ul class="list-group">
							<li class="list-group-item text-center">Builtin PG/GH Aggregation</li>
							<br/>
							<li class="list-group-item text-center">Trusted Wallet Integation</li>
						</ul>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<ul class="list-group">
							<li class="list-group-item text-center">Privacy Transaction</li>
							<br/>

							<li class="list-group-item text-center">Verified Members</li>
						</ul>
					</div>

				</div>
				<div class="col-md-3">
					<img style="float:left" src="catalog/view/theme/default/images/bg-right.jpg">
				</div>
			</div>
		</div>
	</section>
	<!-- End Call To Action -->
	
	<!-- Footer Bottom -->	
	<footer class="footer footer-sub" style="display:none">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-sm-6">
					
				</div>
			</div>
		</div>
	</footer>
	<!-- End Footer Bottom -->
</body>

</html>
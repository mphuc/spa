<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script type="text/javascript" src="../catalog/view/javascript/jquery/jquery-2.1.1.min.js"></script>
</head>
<body>
	<div id="content">
		<h2>Công Ty TNHH SX TM Đầu tư vận tải biển</h2>
		<i>(Thông tin mã code gói đầu tư)</i>
		<div class="content">
			<p><span style="font-size:29px;" class="code">Mã code: <?php echo $code ;?></span></p>
			<p><span class="investment">Gói đầu tư: <?php echo number_format($investment) ;?> VNĐ</span></p>
			<p><span class="username">Họ tên: <?php echo $username ;?></span></p>
			<p><span class="phone">Số điện thoại: <?php echo $telephone ;?></span></p>
			<p><span class="address">Địa chỉ: <?php echo $address ;?></span></p>
		</div>
	</div>
</body>
<!-- <script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('.code').html(localStorage.getItem('code'));
		jQuery('.investment').html(localStorage.getItem('investment'));
		jQuery('.username').html(localStorage.getItem('username'));
		jQuery('.phone').html(localStorage.getItem('phone'));
		jQuery('.address').html(localStorage.getItem('address'));

	})
</script> -->
<style type="text/css" media="print">
	@page {
	    size: auto;   /* auto is the initial value */
	    margin: 0;  /* this affects the margin in the printer settings */
	}
</style>
<style type="text/css">
	body{
		width: 1100px;
		margin: 0 auto;
		padding: 70px;
		font-size: 20px;
		padding-top: 50px;
		background: url('../images/logo.png') no-repeat center 140px;
	}
	#content h2{
		text-transform: uppercase;
		text-align: center;
	}
	#content i{
		width: 100%;
	    float: left;
	    text-align: center;
	    margin-top: -15px;
	}
	.content{
		width: 600px;
		margin: 0 auto;
		margin-top:50px;
	}
	.content p{
		width: 100%;
		float: left;
		margin: 0px;
	}
	.content p span{
		font-size: 22px;
		float: left;
		height: 40px;
	}
	.code{
		font-weight: bold;
		font-size: 20px;
	}
</style>
</html>
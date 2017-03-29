<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<style type="text/css" media="print">
	@page {
	    size: auto;   /* auto is the initial value */
	    margin: 0;  /* this affects the margin in the printer settings */
	}
</style>
	<style type="text/css">
	body {
	  background: rgb(204,204,204); 
	}
	page {
	  background: white;
	  display: block;
	  margin: 0 auto;
	  padding: 10px;
	  margin-bottom: 0.5cm;
	  box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
	  background: #fff url('view/stylesheet/images/logo.png') no-repeat 5px 5px;
	  background-size: 175px;
	}
	page[size="A4"] {  
	  width: 21cm;
	  height: 29.7cm; 
	}
	page[size="A4"][layout="portrait"] {
	  width: 29.7cm;
	  height: 21cm;  
	}
	page[size="A3"] {
	  width: 29.7cm;
	  height: 42cm;
	}
	page[size="A3"][layout="portrait"] {
	  width: 42cm;
	  height: 29.7cm;  
	}
	page[size="A5"] {
	  width: 14.8cm;
	  height: 21cm;
	}
	page[size="A5"][layout="portrait"] {
	  width: 21cm;
	  height: 14.8cm;  
	}
	@media print {
	  body, page {
	    margin: 0;
	    box-shadow: 0;
	  }
	}
	h3{text-align: center;}
	.left{
		float: left;
		width: 396.5px;
	}
	.right{
		width: 396.5px;
		float: right;

	}
	.right h4, .right p{
		text-align: center;
	}
	.left h4, .left p{
		text-align: center;
	}
	.clear{
		clear: both;
    content: ".";
    display: block;
    height: 0;
    line-height: 0;
    visibility: hidden;
	}
	#ch4{
		text-align: center;
	}
	.left h5{
		margin-left: 100px;
	}
	.right h5{
		margin-left: 100px;
	}
	i{
		font-weight: normal;
		font-size: 16px;
	}
	h5 span {
    font-size: 20px;
}
.info{
	padding: 5px 20px;
}
	</style>
</head>
<body>
	<page size="A4">
		<h3>CÔNG TY TNHH SẢN XUẤT THƯƠNG MẠI <BR> VẬN TẢI BIỂN HƯNG THỊNH PHÁT</h3>
		<h4 id="ch4" class="chapter">THÔNG TIN TÀI KHOẢN</h4>
		<div class="left">
			
	   
		<h5>ID: <span><?php echo $info_customer['username'] ?></span></h5>
		<h5>Mật khẩu: <span class="password"><?php echo $pass; ?></span></h5>
	    <h5>Họ và tên: <?php echo $info_customer['firstname'] ?></h5>
	      

	    <h5>Địa chỉ: <?php echo $info_customer['address_cmnd'] ?></h5>
	      

	    <h5>Email: <?php echo $info_customer['email'] ?></h5>
	      
	    <h5 >Số điện thoại: <?php echo $info_customer['telephone'] ?></h5>
	     
	    <h5 id="" class="chapter">Số CMND: <?php echo $info_customer['cmnd'] ?></h5>
	     
	    <h5>Gói đầu tư: <?php echo number_format($info_customer['filled']) ?></h5>
	    <h5>Ngày đầu tư: <?php echo date("d/m/Y H:i:A", strtotime($info_customer['pd_date_added'])); ?></h5>
	    <!-- <h5>Ngày kết thúc: <?php echo date("d/m/Y H:i:A", strtotime($info_customer['pd_date_finish'])); ?></h5> -->
	     
		</div>
		<div class="right">
			

	    <h5 >Tên tài khoản NH: <?php echo $info_customer['account_holder'] ?></h5>
	      

	    <h5 >Số tài khoản: <?php echo $info_customer['account_number'] ?></h5>
	    

	    <h5 >Ngân hàng: <?php echo $info_customer['bank_name'] ?></h5>
	      
	    <h5 >Chi nhánh: <?php echo $info_customer['branch_bank'] ?></h5>
	     
	    <h5>Người bảo trợ: <?php echo $u_p_binary = $self -> get_username($info_customer['p_node']);  ?></h5>
	    
	    <h5 >Nhánh: <?php echo $u_p_binary = $self -> get_username($info_customer['p_binary']) ?></h5>
	    
		</div>
		<div class="clear"></div>
		<div class="info">
			<p>Quý khách vui lòng giữ lại giấy đăng ký này và các chứng từ có liên quan để phục vụ việc tra soát khiếu nại sau này (nếu có).</p>
		<p>Để đảm bảo tính bảo mật của cá nhân, quý khách vui lòng đăng nhập và đổi mật khẩu trong vòng 24h.</p>
		</div>
		
		<div id="signature">
			<div class="left">
				<h3><br>Người ghi số<br><i>(Ký, họ tên)</i></h3>
			</div>
			<div class="right">
				
				<h3><i>Ngày ...tháng ...năm...</i><br>Kế toán<br><i>(Ký, họ tên)</i></h3>
				
			</div>
		</div>
	</page>
</body>
</html>
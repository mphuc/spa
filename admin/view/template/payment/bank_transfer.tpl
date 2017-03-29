<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="container-fluid">
    <?php if (intval($payment_bitcoin) > 0)  { ?>
      <!-- 0.4345 -->
  		<div class="row">

          <div class="col-md-12 col-sm-12 col-xs-12" id="no-more-tables">
          
                <table id="datatable" class="table table-striped table-bordered">
                    <thead>  
                        <tr >
                            <th style="border-bottom:0px; text-align:center">Aount Bitcoin.</th>
                            <th style="border-bottom:0px; text-align:center">QR code.</th>
                            <th style="border-bottom:0px; text-align:center">Note.</th>
                        </tr>
                    </thead>
                    <tbody>
                      <tr style="background:#fff">
                          <td data-title="STT." align="center"><?php 

                          $fee = $payment_bitcoin_send + ($payment_bitcoin_send *0.01);
                          $ajaxAmount = $fee;
                          $fee = doubleval($fee + 20000) / 100000000 ;
                          echo $fee ;

                          ?> BTC</td>
                          <td data-title="STT." align="center">
                            <img src="https://chart.googleapis.com/chart?chs=200x200&amp;chld=L|1&amp;cht=qr&amp;chl=bitcoin:<?php echo $wallet ?>?amount=<?php echo $fee ?>">
                              <br/>
                              <br/> 
                              <?php echo $wallet; ?>
                          </td>
                          <td data-title="STT." align="center">You have 1,5 hours to send <b> <?php echo $fee ?> BTC  </b>to : <?php echo $wallet; ?>.</td>
                      </tr>
                    </tbody>
                </table>
          </div>
  			<div class="col-md-12 col-sm-12 col-xs-12" id="no-more-tables">
  				
                <table id="datatable" class="table table-striped table-bordered">
                    <thead>  
                        <tr>
							<th style="border-bottom:0px">STT</th>
							<th style="border-bottom:0px">tai khoan</th>
							<th style="border-bottom:0px">Lai hoa hong</th>
							<th style="border-bottom:0px">Lai hang ngay</th>
							<th style="border-bottom:0px">Tong so BTC chuyen di</th>
              <th style="border-bottom:0px">So BTC se duoc nhan - 3%</th>
                        </tr>
                    </thead>
                    <tbody>

                    	<?php foreach ($users_payment as $key => $value) {?>
	                        <tr>
	                            <td data-title="STT." align="left"><?php echo $value['id'] ?></td>
	                            <td data-title="username"><?php echo $value['username'] ?></td>
	                            <td data-title="C-Wallet"><?php echo doubleval($value['c_wallet']) / 100000000 ?> BTC</td>
	                            <td data-title="R-Wallet">
                              <?php echo doubleval($value['r_wallet']) / 100000000  ?> x 2.5% = 
                              <?php echo doubleval($value['r_wallet'] * 0.025) / 100000000 ?> BTC

                              </td>
	                            <td data-title="Send BTC">
                                 <?php echo (doubleval($value['total_send']) - 20000) / 100000000 ?> BTC (2%) + 0.0002(transfer) =
	                               <?php echo doubleval($value['total_send']) / 100000000 ?> BTC
	                            </td>
                              <td data-title="User Revice">
                                  <?php echo doubleval($value['total_fee']) / 100000000 ?> BTC (3%)

                              </td>
	                        </tr>
                        <?php } ?>

                    </tbody>
                </table>
            <?php echo $pagination; ?>
  			</div>
  		</div>
      <?php } else {?>
        <div class="row">
            <div class="col-md-12 col-lg-12">
              <?php if(intval($timer) === 1){  ?>
                <button class="pament-btn-blockchian btn btn-button">Thanh toan hang ngay.</button>
                <h1 style="display:none" id="wr-blockchain">Loading ....... Please wait!!!!</h1>
              <?php }else{?>
                <h1>Vui long doi toi ngay mai, hay thanh toan !</h1>
              <?php } ?>
            </div>
        </div>
      <?php }?>
  </div>
</div>
<?php echo $footer; ?>


<?php if(intval($timer) === 1){ ?>
    <script type="text/javascript">
        $('button.pament-btn-blockchian').on('click', function(){
          $('#wr-blockchain').show();
          $(this).hide();
          setTimeout(function() {
          $.ajax({
                url : '<?php echo $link ?>&token=<?php echo $token ?>',
                type : 'GET',
                async : false,
                success : function(result) {
                    location.reload(true);
                }
            });
        },1000);
        });
    </script>
<?php } ?>
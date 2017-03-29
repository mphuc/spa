<?php echo $header; ?>
<div id="content" class="common-login">
  <div class="container-fluid"><br />
    <br />
    <div class="row">
      <div class="col-sm-offset-4 col-sm-4">
        <div class="panel panel-default">
          
          <div class="panel-body">
            <div class="panel panel-color panel-primary" style="background:#fff !important; border:1px solid #cecece;">
            <div class="heade-login text-center">
              <a href="">
                 art-naturalcare.com
               </a>
            </div>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label for="input-username"><?php echo $entry_username; ?></label>
                <div class="input-group"><span class="input-group-addon"><i class="fa fa-user"></i></span>
                  <input type="text" name="username" value="<?php echo $username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label for="input-password"><?php echo $entry_password; ?></label>
                <div class="input-group"><span class="input-group-addon"><i class="fa fa-lock"></i></span>
                  <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
                </div>
                 <label for="input-password" style="margin-top: 16px;">OTP</label>
                 <div class="clearfix"></div>
                <div class="input-group" >

                <span class="input-group-addon">
                <i class="fa fa-lock password-icon"></i>

                </span>

                 <input type="text" name="otp" value="" placeholder="OTP" id="" class="form-control" />

                </div>
              </div>
              <?php if ($success) { ?>
            <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
              <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php } ?>
            <?php if ($error_warning) { ?>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
              <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php } ?>
              <div class="form-group text-right">
                  <div class="">
                <button type="submit" class="btn btn-purple w-md"><i class="fa fa-key"></i> <?php echo $button_login; ?></button>
              </div>
              </div>
              <?php if ($redirect) { ?>
              <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
              <?php } ?>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
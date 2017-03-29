<?php echo $header; ?>
<div class="container">
  <div class="row">
    <div class="col-md-8" style="margin:auto; float:none">
      <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
        <h1>Username</h1>
       
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
          <fieldset>
            <legend>Username</legend>
            <div class="form-group required">
              <label style="text-align:left" class="col-sm-2 control-label" for="input-email">Username</label>
              <div class="col-sm-10">
                  <?php if ($successpassword) { ?>
          <input type="password" name="password" value="<?php echo $successpassword; ?>" placeholder="password" id="input-email" class="form-control" />
        <?php } ?>
                  <?php if ($session_email) { ?>
          <input type="text" name="email" value="<?php echo $session_email; ?>" placeholder="email" id="input-email" class="form-control" />
        <?php } ?>
                <input type="text" name="username" value="" placeholder="username" id="input-email" class="form-control" />
              </div>
            </div>
          </fieldset>
          <div class="buttons clearfix">
            <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default">Back</a></div>
            <div class="pull-right">
              <input type="submit" value="Continue" class="btn btn-primary" />
            </div>
          </div>
        </form>
        <?php if ($error_warning) { ?>
          <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
        <?php } ?>
      
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
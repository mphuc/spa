<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
  <div class="container-fluid">
    <h1>Send Mail</h1>

  </div>
</div>
<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Send Mail</h3>
    </div>
    <div class="panel-body">
        <form action="index.php?route=pd/sendmail/submit&token=<?php echo $_GET['token']; ?>" method="post">
          <div class="form-group">
            <label for="email">Email address:</label>
            <input type="email" name="email" class="form-control" id="email" required="true">
          </div>
          <div class="form-group">
            <label for="pwd">Content:</label>
            <textarea required="true"  style="width: 100%; height: 200px !important;" class="form-control note-codable" id="description1" name="content"></textarea>


          </div>
          
            
          <button type="submit" class="btn btn-default">Submit</button>
        </form>
     	
    </div>
  </div>
</div>
<script type="text/javascript">
   if (location.hash === '#suscces') {
     alert("Send mail complete !")
   }
</script>  
<?php echo $footer; ?>
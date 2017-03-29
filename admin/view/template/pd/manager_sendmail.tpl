<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
  <div class="container-fluid">
    <h1>Manager Mail</h1>

  </div>
</div>
<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Manager Mail</h3>
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>NO</th>
            <th>Username</th>
            <th>Title</th>
            <th>Content</th>
            <th>Date_added</th>
          </tr>
        </thead>
        <tbody>
          <?php $stt = 1; $sum=0;
            foreach ($pin as $key => $value) { ?>
          <tr>
            <td><?php echo $stt; ?></td>
            <td><?php echo $value['username'] ?></td>
            <td><?php echo $value['title'] ?></td>
            <td><?php echo $value['description'] ?></td>
            <td><?php echo  date('d/m/Y H:i:s',strtotime($value['date_added'])) ?></td>
          </tr>

          <?php $stt ++; } ?>
        </tbody>
      </table>
      <?php echo $pagination ?>
     	
    </div>
  </div>
</div>
<script type="text/javascript">
   if (location.hash === '#suscces') {
     alert("Send mail complete !")
   }
</script>  
<?php echo $footer; ?>
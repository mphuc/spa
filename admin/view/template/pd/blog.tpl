<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
  <div class="container-fluid">
    <h1>Manager Blog</h1>

  </div>
</div>
<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Manager Blog</h3>
    </div>
    <div class="panel-body">
    <!-- <a href="index.php?route=pd/blog/create&token=<?php echo $_GET['token'] ;?>">
      <span class="nav-item-text btn btn-success pull-right">Create Blog</span>
    </a> -->
      <ul class="nav nav-tabs" style="margin-bottom: 30px;">
        <li class="active"><a data-toggle="tab" href="#home">Admin</a></li>
        <li><a data-toggle="tab" href="#menu1">Customer</a></li>
        
      </ul>

    <div class="tab-content">
      <div id="home" class="tab-pane fade in active">
        <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>NO</th>
            
            <th>Title</th>
            <th>Content</th>
            <th>Date added</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php $stt = 1; $sum=0;
            foreach ($getBlogById_admin as $key => $value) { ?>
          <tr>
            <td><?php echo $stt; ?></td>
            
            <td><?php echo $value['title'] ?></td>
            <td><?php 
                        if (strlen($value['description']) > 200) $cham = "..."; else $cham = "";
                        echo substr($value['description'],0,200)." ".$cham ?></td>
            <td><?php echo  date('d/m/Y H:i:s',strtotime($value['date_added'])) ?></td>
            <td>
              <?php  if ($value['status'] == 0) { ?>
                <a href="index.php?route=pd/blog/update_status&token=<?php echo $_GET['token'];?>&id=<?php echo $value['id'];?>">
                  <button type="button" class="btn btn-success">Enable</button>
                </a>
              <?php } ?>
              <?php  if ($value['status'] == 1) { ?>
                <a href="index.php?route=pd/blog/update_status&token=<?php echo $_GET['token'];?>&id=<?php echo $value['id'];?>">
                  <button type="button" class="btn btn-danger">Disable</button>
                </a>
              <?php } ?>
            </td>
          </tr>

          <?php $stt ++; } ?>
        </tbody>
      </table>
      <?php echo $pagination ?>
     	</div>
        <div id="menu1" class="tab-pane fade">
          <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>NO</th>
            <th>Username</th>
            <th>Title</th>
            <th>Content</th>
            <th>Date added</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php $stt = 1; $sum=0;
            foreach ($pin as $key => $value) { ?>
          <tr>
            <td><?php echo $stt; ?></td>
            <td><?php echo $value['username'] ?></td>
            <td><?php echo $value['title'] ?></td>
            <td><?php 
                        if (strlen($value['description']) > 200) $cham = "..."; else $cham = "";
                        echo substr($value['description'],0,200)." ".$cham ?></td>
            <td><?php echo  date('d/m/Y H:i:s',strtotime($value['date_added'])) ?></td>
            <td>
              <?php  if ($value['status'] == 0) { ?>
                <a href="index.php?route=pd/blog/update_status&token=<?php echo $_GET['token'];?>&id=<?php echo $value['id'];?>">
                  <button type="button" class="btn btn-success">Enable</button>
                </a>
              <?php } ?>
              <?php  if ($value['status'] == 1) { ?>
                <a href="index.php?route=pd/blog/update_status&token=<?php echo $_GET['token'];?>&id=<?php echo $value['id'];?>">
                  <button type="button" class="btn btn-danger">Disable</button>
                </a>
              <?php } ?>
            </td>
          </tr>

          <?php $stt ++; } ?>
        </tbody>
      </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
   if (location.hash === '#suscces') {
     alert("Send mail complete !")
   }
</script>  
<?php echo $footer; ?>
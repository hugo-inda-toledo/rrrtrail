<!DOCTYPE html>
<html lang="en">
  <head>
      <?php echo $this->Html->charset() ?>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <?php echo $this->fetch('meta') ?>
      <title>
          Zippedi Management :: <?php echo $this->fetch('title') ?>
      </title>

      <?php echo $this->Html->meta('zippedi_favicon.png', '/zippedi_favicon.png', array('type' => 'icon'));?>

      <!-- Core CSS - Include with every page -->
      <?php echo $this->Html->css('management/bootstrap.css') ?>
      <?php echo $this->Html->css('management/sb-admin.css') ?>
      <?php echo $this->Html->css('management/font-awesome/css/font-awesome.min.css') ?>

      <!-- Page-Level Plugin CSS - Dashboard -->
      <?php echo $this->Html->css('http://cdn.oesmith.co.uk/morris-0.4.3.min.css') ?>

      <?php echo $this->fetch('css') ?>
      
      <script type="text/javascript">
          var webroot = '<?php echo $this->request->webroot ?>';
      </script>
  </head>

  <body>

    <div id="wrapper">

      <!-- Sidebar -->
      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <?php echo $this->Html->link($this->Html->image('onlyletterswhite.png', ['class' => 'img-responsive', 'style' => 'width: 130px;margin-top: -14px;']), '/dashboard', ['escape' => false, 'class' => 'navbar-brand']);?>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <ul class="nav navbar-nav side-nav">
            <li class="sidebar-search">
                <!--<button type="button" class="btn btn-danger pull-center" id="generate-button">Generar</button>-->
                
                <?php echo $this->Html->link(__('Back To Client Application'), '/', ['class' => 'btn btn-danger', 'style' => 'position: relative;display: block;padding: 10px 15px;color: #FFF;width: 190px;'])?>
            </li>
            <li class="active">
              <?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-dashboard']).' '.__('Dashboard'), ['controller' => 'Dashboard', 'action' => 'index', 'plugin' => 'management'], ['escape' => false]);?>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-calendar-o"></i> <?php echo __('Robot Sessions');?> <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li>
                  <?php echo $this->Html->link(__('On real Time'), ['controller' => 'RobotSessions', 'action' => 'onRealTime', 'plugin' => 'management']);?>
                </li>
                <li>
                  <?php echo $this->Html->link(__('List all session'), ['controller' => 'RobotSessions', 'action' => 'index', 'plugin' => 'management']);?>
                </li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users"></i> <?php echo __('Users');?> <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li>
                  <?php echo $this->Html->link(__('Add user'), ['controller' => 'Users', 'action' => 'add', 'plugin' => 'management']);?>
                </li>
                <li>
                  <?php echo $this->Html->link(__('List users'), ['controller' => 'Users', 'action' => 'index', 'plugin' => 'management']);?>
                </li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-building-o"></i> <?php echo __('Companies');?> <b class="caret"></b></a>
              <ul class="dropdown-menu">
                  <li>
                      <?php echo $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']);?>
                  </li>
                  <li>
                      <?php echo $this->Html->link(__('Stores'), ['controller' => 'Stores', 'action' => 'index']);?>
                  </li>
                  <li>
                      <?php echo $this->Html->link(__('Aisles'), ['controller' => 'Aisles', 'action' => 'index']);?>
                  </li>
                  <li>
                      <?php echo $this->Html->link(__('Sections'), ['controller' => 'Sections', 'action' => 'index']);?>
                  </li>
                  <li>
                      <?php echo $this->Html->link(__('Categories'), ['controller' => 'Categories', 'action' => 'index']);?>
                  </li>
                  <li>
                      <?php echo $this->Html->link(__('Sub Categories'), ['controller' => 'SubCategories', 'action' => 'index']);?>
                  </li>
              </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-barcode"></i> <?php echo __('Products');?> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li>
                        <?php echo $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']);?>
                    </li>
                    <li>
                        <?php echo $this->Html->link(__('Measurement Units'), ['controller' => 'MeasurementUnits', 'action' => 'index']);?>
                    </li>
                </ul>
            </li>
            <li>
                <?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-truck']).' '.__('Suppliers'), ['controller' => 'Suppliers', 'action' => 'index'], ['escape' => false]);?>
            </li>
            <li>
                <?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-cogs']).' '.__('Robot Reports'), ['controller' => 'RobotReports', 'action' => 'index'], ['escape' => false]);?>
            </li>
          </ul>

          <ul class="nav navbar-nav navbar-right navbar-user">
            <li class="dropdown messages-dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> Messages <span class="badge">7</span> <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li class="dropdown-header">7 New Messages</li>
                <li class="message-preview">
                  <a href="#">
                    <span class="avatar"><img src="http://placehold.it/50x50"></span>
                    <span class="name">John Smith:</span>
                    <span class="message">Hey there, I wanted to ask you something...</span>
                    <span class="time"><i class="fa fa-clock-o"></i> 4:34 PM</span>
                  </a>
                </li>
                <li class="divider"></li>
                <li class="message-preview">
                  <a href="#">
                    <span class="avatar"><img src="http://placehold.it/50x50"></span>
                    <span class="name">John Smith:</span>
                    <span class="message">Hey there, I wanted to ask you something...</span>
                    <span class="time"><i class="fa fa-clock-o"></i> 4:34 PM</span>
                  </a>
                </li>
                <li class="divider"></li>
                <li class="message-preview">
                  <a href="#">
                    <span class="avatar"><img src="http://placehold.it/50x50"></span>
                    <span class="name">John Smith:</span>
                    <span class="message">Hey there, I wanted to ask you something...</span>
                    <span class="time"><i class="fa fa-clock-o"></i> 4:34 PM</span>
                  </a>
                </li>
                <li class="divider"></li>
                <li><a href="#">View Inbox <span class="badge">7</span></a></li>
              </ul>
            </li>
            <li class="dropdown alerts-dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> Alerts <span class="badge">3</span> <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#">Default <span class="label label-default">Default</span></a></li>
                <li><a href="#">Primary <span class="label label-primary">Primary</span></a></li>
                <li><a href="#">Success <span class="label label-success">Success</span></a></li>
                <li><a href="#">Info <span class="label label-info">Info</span></a></li>
                <li><a href="#">Warning <span class="label label-warning">Warning</span></a></li>
                <li><a href="#">Danger <span class="label label-danger">Danger</span></a></li>
                <li class="divider"></li>
                <li><a href="#">View All</a></li>
              </ul>
            </li>


            <li class="dropdown user-dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                  <i class="fa fa-user fa-fw"></i><?php echo $this->request->session()->read('Auth.User.name').' '.$this->request->session()->read('Auth.User.last_name').' ';?><i class="caret"></i>
              </a>
              <ul class="dropdown-menu">
                  <li>
                      <?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-user fa-fw']).' '.__('User Profile'), '/my-profile', ['escape' => false]);?>
                  </li>
                  <li>
                      <?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-gear fa-fw']).' '.__('Settings'), '/my-settings', ['escape' => false]);?>
                  </li>
                  <li class="divider"></li>
                  <li>
                      <?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-sign-out fa-fw']).' '.__('Logout'), '/users/logout', ['escape' => false]);?>
                  </li>
              </ul>
              <!-- /.dropdown-user -->
          </li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </nav>

      <div id="page-wrapper">
        <?php echo $this->Flash->render() ?>
        <?php echo $this->fetch('content') ?>
      </div><!-- /#page-wrapper -->

    </div><!-- /#wrapper -->

    <!-- JavaScript -->

    <?php echo $this->Html->script('management/jquery-1.10.2.js');?>
    <?php echo $this->Html->script('management/bootstrap.js');?>

    <!-- Page Specific Plugins -->
    <?php echo $this->Html->script('http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js');?>
    <?php echo $this->Html->script('http://cdn.oesmith.co.uk/morris-0.4.3.min.js');?>
    <?php echo $this->Html->script('management/morris/chart-data-morris.js');?>
    <?php echo $this->Html->script('management/tablesorter/jquery.tablesorter.js');?>
    <?php echo $this->Html->script('management/tablesorter/tables.js');?>

    <?php echo $this->fetch('scriptBottom')?>
    <?php echo $this->fetch('scriptBottom2')?>

  </body>
</html>
<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <?php echo $this->fetch('meta') ?>
        <title>
            Zippedi :: <?php echo $this->fetch('title') ?>
        </title>

        <?php echo $this->Html->meta('zippedi_favicon.png', '/zippedi_favicon.png', array('type' => 'icon'));?>

        <!-- Core CSS - Include with every page -->
        <?php echo $this->Html->css('application/bootstrap.css') ?>
        <?php echo $this->Html->css('application/font-awesome/css/font-awesome.css') ?>

        <!-- Page-Level Plugin CSS - Dashboard -->
        <?php echo $this->Html->css('application/plugins/morris/morris-0.4.3.min.css') ?>
        <?php echo $this->Html->css('application/plugins/timeline/timeline.css') ?>

        <!-- SB Admin CSS - Include with every page -->
        <?php echo $this->Html->css('application/sb-admin.css') ?>

        <?php echo $this->fetch('css') ?>
        
        <script type="text/javascript">
            var webroot = '<?php echo $this->request->webroot ?>';
        </script>
    </head>

    <body>

        <div id="wrapper">

            <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <?php echo $this->Html->link($this->Html->image('onlyletters2.png', ['class' => 'img-responsive', 'style' => 'width: 130px;margin-top: -14px;']), ['controller' => 'Stores', 'action' => 'map'], ['escape' => false, 'class' => 'navbar-brand']);?>
                </div>
                <!-- /.navbar-header -->

                
                    <ul class="nav navbar-top-links navbar-right">
                        <!--<li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-envelope fa-fw"></i>  <i class="fa fa-caret-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-messages">
                                <li>
                                    <a href="#">
                                        <div>
                                            <strong>John Smith</strong>
                                            <span class="pull-right text-muted">
                                                <em>Yesterday</em>
                                            </span>
                                        </div>
                                        <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="#">
                                        <div>
                                            <strong>John Smith</strong>
                                            <span class="pull-right text-muted">
                                                <em>Yesterday</em>
                                            </span>
                                        </div>
                                        <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="#">
                                        <div>
                                            <strong>John Smith</strong>
                                            <span class="pull-right text-muted">
                                                <em>Yesterday</em>
                                            </span>
                                        </div>
                                        <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a class="text-center" href="#">
                                        <strong>Read All Messages</strong>
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>-->

                        <?php if(isset($processing_robot_sessions) && count($processing_robot_sessions) > 0):?>
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-tasks fa-fw"></i>  <i class="fa fa-caret-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-tasks">
                                    <?php foreach($processing_robot_sessions as $id => $robot_session):?>

                                        <?php
                                            $current_percent = round(($robot_session['current_processing'] * 100) / $robot_session['object']->total_detections, 2);
                                        ?>
                                        <li>
                                            <a href="#">
                                                <div>
                                                    <p>
                                                        <strong style="">
                                                            <?php echo __('[{0}]: {1}', [$robot_session['object']->store->store_code, $robot_session['object']->session_date->format('d-m-Y H:i')]);?>
                                                        </strong>
                                                        <span class="pull-right text-muted"><?php echo $current_percent.'%'; ?> Complete</span>
                                                    </p>
                                                    <div class="progress progress-striped active">
                                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $current_percent;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $current_percent.'%';?>">
                                                            <span class="sr-only"><?php echo $current_percent.'%';?> Complete (success)</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <?php if(count($processing_robot_sessions) != 1):?>
                                                <li class="divider"></li>
                                        <?php endif;?>
                                        
                                    <?php endforeach;?>
                                    <!--<li>
                                        <a href="#">
                                            <div>
                                                <p>
                                                    <strong>Task 2</strong>
                                                    <span class="pull-right text-muted">20% Complete</span>
                                                </p>
                                                <div class="progress progress-striped active">
                                                    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                                                        <span class="sr-only">20% Complete</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="#">
                                            <div>
                                                <p>
                                                    <strong>Task 3</strong>
                                                    <span class="pull-right text-muted">60% Complete</span>
                                                </p>
                                                <div class="progress progress-striped active">
                                                    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                                        <span class="sr-only">60% Complete (warning)</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="#">
                                            <div>
                                                <p>
                                                    <strong>Task 4</strong>
                                                    <span class="pull-right text-muted">80% Complete</span>
                                                </p>
                                                <div class="progress progress-striped active">
                                                    <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                                        <span class="sr-only">80% Complete (danger)</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a class="text-center" href="#">
                                            <strong>See All Tasks</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </li>-->
                                </ul>
                            </li>
                        <?php endif;?>
                        
                        <!--<li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-alerts">
                                <li>
                                    <a href="#">
                                        <div>
                                            <i class="fa fa-comment fa-fw"></i> New Comment
                                            <span class="pull-right text-muted small">4 minutes ago</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="#">
                                        <div>
                                            <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                            <span class="pull-right text-muted small">12 minutes ago</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="#">
                                        <div>
                                            <i class="fa fa-envelope fa-fw"></i> Message Sent
                                            <span class="pull-right text-muted small">4 minutes ago</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="#">
                                        <div>
                                            <i class="fa fa-tasks fa-fw"></i> New Task
                                            <span class="pull-right text-muted small">4 minutes ago</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="#">
                                        <div>
                                            <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                            <span class="pull-right text-muted small">4 minutes ago</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a class="text-center" href="#">
                                        <strong>See All Alerts</strong>
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>-->

                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-user fa-fw"></i><?php echo $this->request->session()->read('Auth.User.name').' '.$this->request->session()->read('Auth.User.last_name').' ';?><i class="fa fa-caret-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <!--<li>
                                    <?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-user fa-fw']).' '.__('User Profile'), '/my-profile', ['escape' => false]);?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-gear fa-fw']).' '.__('Settings'), '/my-settings', ['escape' => false]);?>
                                </li>
                                <li class="divider"></li>-->
                                <li>
                                    <?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-sign-out fa-fw']).' '.__('Logout'), ['controller' => 'Users', 'action' => 'logout'], ['escape' => false]);?>
                                </li>
                            </ul>
                            <!-- /.dropdown-user -->
                        </li>

                    </ul>
                


                <div class="navbar-default navbar-static-side" role="navigation">
                    <div class="sidebar-collapse">
                        <ul class="nav" id="side-menu">
                            <?php if($this->request->session()->read('Auth.User.is_admin') == 1):?>
                                <li class="sidebar-search">
                                    <!--<button type="button" class="btn btn-danger pull-center" id="generate-button">Generar</button>-->
                                    
                                    <?php echo $this->Html->link(__('Environment Management'), ['controller' => 'Dashboard', 'action' => 'index', 'plugin' => 'management'], ['class' => 'btn btn-default btn-block'])?>
                                </li>
                            <?php endif;?>
                            <!--<li>
                                <?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-dashboard fa-fw']).' '.__('Dashboard'), ['controller' => 'Dashboard', 'action' => 'index'], ['escape' => false]);?>
                            </li>-->
                            <li>
                                <?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-map-marker fa-fw']).' '.__('Map'), '/map', ['escape' => false]);?>
                            </li>
                            <li>
                                <?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-file fa-fw']).' '.__('Reports'), ['controller' => 'RobotReports', 'action' => 'index'], ['escape' => false]);?>
                            </li>
                        </ul>
                        <!-- /#side-menu -->
                    </div>
                    <!-- /.sidebar-collapse -->
                </div>
                <!-- /.navbar-static-side -->
            </nav>

            <div id="page-wrapper" style="padding: 10px 30px;min-height: 0px;">
                <?php echo $this->Flash->render() ?>
                <?php echo $this->fetch('content') ?>
            </div>
            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Core Scripts - Include with every page -->
        <?php echo $this->Html->script('application/jquery-1.10.2.js');?>
        <?php echo $this->Html->script('application/bootstrap.min.js');?>
        <?php echo $this->Html->script('application/plugins/metisMenu/jquery.metisMenu.js');?>

        <!-- SB Admin Scripts - Include with every page -->
        <?php echo $this->Html->script('application/sb-admin.js');?>

        <?php echo $this->fetch('scriptBottom')?>
        <?php echo $this->fetch('scriptBottom2')?>


        <!--<pre>
            <?php //print_r($this->request->session()->read());?>
        </pre>-->
        <script>
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            });
        </script>
    </body>
</html>
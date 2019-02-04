<html lang="en">
	<head>

		<?php echo $this->Html->charset('utf-8') ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <?php echo $this->fetch('meta') ?>
        <title>
            Zippedi :: <?php echo $this->fetch('title') ?>
        </title>

        <?php echo $this->Html->meta('zippedi_favicon.png', '/zippedi_favicon.png', array('type' => 'icon'));?>

        <!-- Core CSS - Include with every page -->
         <?php //echo $this->Html->css('http://fonts.googleapis.com/css?family=Roboto:300italic,400italic,300,400,500,700,900') ?>

        <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">


        <?php echo $this->Html->css('theme-zippedi/bootstrap.css?1422823238') ?>
        <?php echo $this->Html->css('theme-zippedi/materialadmin.css?1422823243') ?>
        <?php echo $this->Html->css('theme-zippedi/font-awesome.min.css?1422823239') ?>
        <?php echo $this->Html->css('theme-zippedi/material-design-iconic-font.min.css?1422823240') ?>


		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script type="text/javascript" src="http://www.codecovers.eu/assets/js/modules/materialadmin/libs/utils/html5shiv.js?1422823601"></script>
		<script type="text/javascript" src="http://www.codecovers.eu/assets/js/modules/materialadmin/libs/utils/respond.min.js?1422823601"></script>
	    <![endif]-->

	    <?php echo $this->fetch('css') ?>
        
        <script type="text/javascript">
            var webroot = '<?php echo $this->request->webroot ?>';
        </script>

	</head>	
	

	<body class="menubar-hoverable header-fixed menubar-first" style="font-family: 'Raleway', sans-serif;">
		<!-- BEGIN HEADER-->
		<header id="header">
				
			<div class="headerbar">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="headerbar-left">
					<ul class="header-nav header-nav-options">
						<li class="header-nav-brand">
							<div class="brand-holder">


								<!--<a href="http://www.codecovers.eu/materialadmin/dashboards/dashboard">
									<span class="text-lg text-bold text-primary">MATERIAL ADMIN</span>
								</a>-->
								<?php echo $this->Html->link($this->Html->image('onlyletters2.png', ['class' => 'img-responsive', 'style' => 'margin-top: -14px;']), ['controller' => 'Stores', 'action' => 'map', 'plugin' => false], ['escape' => false, 'class' => 'navbar-brand']);?>
							</div>
						</li>
						<li>
							<a class="btn btn-icon-toggle menubar-toggle" data-toggle="menubar" href="javascript:void(0);">
								<i class="fa fa-bars"></i>
							</a>
						</li>
					</ul>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="headerbar-right">
					<ul class="header-nav header-nav-options">
						<!--<li>
							<form class="navbar-search" role="search">
								<div class="form-group">
									<input type="text" class="form-control" name="headerSearch" placeholder="Enter your keyword">
								</div>
								<button type="submit" class="btn btn-icon-toggle ink-reaction"><i class="fa fa-search"></i></button>
							</form>
						</li>-->
						<?php if($this->request->session()->read('Auth.Suppliers') !== null && count($this->request->session()->read('Auth.Suppliers')) > 0):?>
							
							<li>
								<?php echo $this->Html->link(__('Suppliers Interface'), ['controller' => 'Stores', 'action' => 'map', 'plugin' => 'Suppliers'], ['class' => 'btn btn-info btn-sm', 'style' => 'color: #ffffff;background-color: #00bcd4;border-color: #00bcd4;'])?>
							</li>

						<?php endif;?>
						
						<!--<li class="dropdown hidden-xs">
							<a href="javascript:void(0);" class="btn btn-icon-toggle btn-default" data-toggle="dropdown">
								<i class="fa fa-bell"></i><sup class="badge style-danger">4</sup>
							</a>
							<ul class="dropdown-menu animation-expand">
								<li class="dropdown-header">Today's messages</li>
								<li>
									<a class="alert alert-callout alert-warning" href="javascript:void(0);">
										<img class="pull-right img-circle dropdown-avatar" src="http://www.codecovers.eu/assets/img/modules/materialadmin/avatar2.jpg?1422538624" alt="">
										<strong>Alex Anistor</strong><br>
										<small>Testing functionality...</small>
									</a>
								</li>
								<li>
									<a class="alert alert-callout alert-info" href="javascript:void(0);">
										<img class="pull-right img-circle dropdown-avatar" src="http://www.codecovers.eu/assets/img/modules/materialadmin/avatar3.jpg?1422538624" alt="">
										<strong>Alicia Adell</strong><br>
										<small>Reviewing last changes...</small>
									</a>
								</li>
								<li class="dropdown-header">Options</li>
								<li><a href="http://www.codecovers.eu/materialadmin/pages/login">View all messages <span class="pull-right"><i class="fa fa-arrow-right"></i></span></a></li>
								<li><a href="http://www.codecovers.eu/materialadmin/pages/login">Mark as read <span class="pull-right"><i class="fa fa-arrow-right"></i></span></a></li>
							</ul>
						</li>-->
						<?php if(isset($processing_robot_sessions) && count($processing_robot_sessions) > 0):?>
							<li class="dropdown hidden-xs" id="dropdown-loads">
								<a href="javascript:void(0);" class="btn btn-icon-toggle btn-default" data-toggle="dropdown">
									<i class="fa fa-area-chart"></i>
								</a>
								<ul class="dropdown-menu animation-expand">
									<li class="dropdown-header">Server loads</li>

									<?php foreach($processing_robot_sessions as $id => $robot_session):?>

										<?php
                                            $current_percent = round(($robot_session['current_processing'] * 100) / $robot_session['object']->total_detections, 2);
                                        ?>

                                        
										<li class="dropdown-progress">
											<a href="javascript:void(0);" class="" class="load-server-button" data-robotsessionid = <?php echo $robot_session['object']->id;?>>
												<div class="dropdown-label">
													<span class="text-light">
														<?php echo $robot_session['object']->session_date->format('d-m-Y H:i');?>
														<strong><?php echo $robot_session['object']->store->store_code;?> </strong>
													</span>
													<strong class="pull-right"><?php echo $current_percent;?>%</strong>
												</div>
												<div class="progress"><div class="progress-bar progress-bar-danger" style="width: <?php echo $current_percent.'%';?>;"></div></div>
											</a>
										</li>
									<?php endforeach;?>
									<!--<li class="dropdown-progress">
										<a href="javascript:void(0);">
											<div class="dropdown-label">
												<span class="text-light">Server load <strong>Yesterday</strong></span>
												<strong class="pull-right">30%</strong>
											</div>
											<div class="progress"><div class="progress-bar progress-bar-success" style="width: 30%"></div></div>
										</a>
									</li>
									<li class="dropdown-progress">
										<a href="javascript:void(0);">
											<div class="dropdown-label">
												<span class="text-light">Server load <strong>Lastweek</strong></span>
												<strong class="pull-right">74%</strong>
											</div>
											<div class="progress"><div class="progress-bar progress-bar-warning" style="width: 74%"></div></div>
										</a>
									</li>-->
								</ul>
							</li>
						<?php endif;?>
					</ul>

					<ul class="header-nav header-nav-profile">
						<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle ink-reaction" data-toggle="dropdown">

								<?php echo $this->Html->image('user-shape.png');?>
								<span class="profile-info">
									<?php echo $this->request->session()->read('Auth.User.name').' '.$this->request->session()->read('Auth.User.last_name').' ';?>
									<small><?php echo $this->request->session()->read('Auth.User.email');?></small>
								</span>
							</a>
							<ul class="dropdown-menu animation-dock">
								<!--<li class="dropdown-header">Config</li>
								<li>
									<a href="http://www.codecovers.eu/materialadmin/pages/profile"><i class="fa fa-fw fa-user"></i> My profile</a>
								</li>
								<li>
									<a href="http://www.codecovers.eu/materialadmin/pages/locked"><i class="fa fa-fw fa-cogs"></i> Settings</a>
								</li>-->
								<li>
									<?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-cogs text-default-dark']).' '.__('Settings'), ['controller' => 'Users', 'action' => 'settings', 'plugin' => false], ['escape' => false]);?>
								</li>
								<li class="divider"></li>
								<li>
									<?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-fw fa-power-off text-danger']).' '.__('Logout'), ['controller' => 'Users', 'action' => 'logout', 'plugin' => false], ['escape' => false]);?>
								</li>
							</ul><!--end .dropdown-menu -->
						</li><!--end .dropdown -->
					</ul><!--end .header-nav-profile -->
					<!--<ul class="header-nav header-nav-toggle">
						<li>
							<a class="btn btn-icon-toggle btn-default" href="#offcanvas-search" data-toggle="offcanvas" data-backdrop="false">
								<i class="fa fa-ellipsis-v"></i>
							</a>
						</li>
					</ul>-->
				</div>
			</div>
		</header>
		<!-- END HEADER-->

		<!-- BEGIN BASE-->
		<div id="base">
			<!-- BEGIN OFFCANVAS LEFT -->
			<div class="offcanvas">
			</div><!--end .offcanvas-->
			<!-- END OFFCANVAS LEFT -->

			<!-- BEGIN CONTENT-->
			<div id="content">
				
				<?php echo $this->Flash->render() ?>
                <?php echo $this->fetch('content') ?>

			</div><!--end #content-->		
			<!-- END CONTENT -->

			<!-- BEGIN MENUBAR-->
			<div id="menubar" class="animate">
				<div class="menubar-fixed-panel">
					<div>
						<a class="btn btn-icon-toggle btn-default menubar-toggle" data-toggle="menubar" href="javascript:void(0);">
							<i class="fa fa-bars"></i>
						</a>
					</div>
					<div class="expanded">
						<?php echo $this->Html->link($this->Html->image('onlyletters2.png', ['class' => 'img-responsive', 'style' => 'margin-top: -14px;']), ['controller' => 'Stores', 'action' => 'map', 'plugin' => false], ['escape' => false, 'class' => 'navbar-brand']);?>
					</div>
				</div>
				<div class="nano" style="height: 203px;">
					<div class="nano-content" tabindex="0">
						<div class="menubar-scroll-panel" style="padding-bottom: 33px;">
						<!-- BEGIN MAIN MENU -->

							<ul id="main-menu" class="gui-controls">
								<?php if($this->request->session()->read('Auth.User.is_admin') == 1):?>
	                                <li>
	                                    <?php echo $this->Html->link(
											$this->Html->div('gui-icon', 
												$this->Html->tag('i', '', ['class' => 'fa fa-cogs'])
											).' '.
											$this->Html->tag('span', __('Environment Management'), ['class' => 'title']), ['controller' => 'Dashboard', 'action' => 'index', 'plugin' => 'management'], [
											'escape' => false
										]);?>
	                                </li>
	                            <?php endif;?>
	                            <li>
									<?php echo $this->Html->link(
										$this->Html->div('gui-icon', 
											$this->Html->tag('i', '', ['class' => 'md md-store'])
										).' '.
										$this->Html->tag('span', __('My Stores'), ['class' => 'title']), ['controller' => 'Stores', 'action' => 'map', 'plugin' => 'Retailers'], [
										'escape' => false
									]);?>
								</li>
								
								
								<li class="gui-folder">
									<a>
										<div class="gui-icon"><i class="md md-assignment"></i></div>
										<span class="title"><?php echo __('Reports');?></span>
									</a>
									
									<ul>
										<li>
											<?php echo $this->Html->link($this->Html->tag('span', __('Assortment'), ['class' => 'title']), ['controller' => 'RobotReports', 'action' => 'assortmentReport', 'plugin' => 'Retailers'], ['escape' => false]);?>
										</li>
										<li>
											<?php echo $this->Html->link($this->Html->tag('span', __('Price Differences'), ['class' => 'title']), ['controller' => 'RobotReports', 'action' => 'priceDifferenceReport', 'plugin' => 'Retailers'], ['escape' => false]);?>
										</li>
										<li>
											<?php echo $this->Html->link($this->Html->tag('span', __('Stock Alerts'), ['class' => 'title']), ['controller' => 'RobotReports', 'action' => 'stockOutReport', 'plugin' => 'Retailers'], ['escape' => false]);?>
										</li>
									</ul>
								</li>								
								
								<!-- BEGIN PAGES -->
								<!--<li class="gui-folder">
									<a>
										<div class="gui-icon"><i class="md md-computer"></i></div>
										<span class="title">Pages</span>
									</a>
									
									<ul>
										<li class="gui-folder">
											<a href="javascript:void(0);">
												<span class="title">Contacts</span>
											</a>
											
											<ul>
												<li><a href="http://www.codecovers.eu/materialadmin/pages/contacts/search"><span class="title">Search</span></a></li>

												<li><a href="http://www.codecovers.eu/materialadmin/pages/contacts/details"><span class="title">Contact card</span></a></li>

												<li><a href="http://www.codecovers.eu/materialadmin/pages/contacts/add"><span class="title">Insert contact</span></a></li>

											</ul>
										</li>
										<li class="gui-folder">
											<a href="javascript:void(0);">
												<span class="title">Search</span>
											</a>
											
											<ul>
												<li><a href="http://www.codecovers.eu/materialadmin/pages/search/results-text"><span class="title">Results - Text</span></a></li>

												<li><a href="http://www.codecovers.eu/materialadmin/pages/search/results-text-image"><span class="title">Results - Text and Image</span></a></li>

											</ul>
										</li>
										<li class="gui-folder">
											<a href="javascript:void(0);">
												<span class="title">Blog</span>
											</a>
											
											<ul>
												<li><a href="http://www.codecovers.eu/materialadmin/pages/blog/masonry"><span class="title">Blog masonry</span></a></li>

												<li><a href="http://www.codecovers.eu/materialadmin/pages/blog/list"><span class="title">Blog list</span></a></li>

												<li><a href="http://www.codecovers.eu/materialadmin/pages/blog/post"><span class="title">Blog post</span></a></li>

											</ul>
										</li>
										<li class="gui-folder">
											<a href="javascript:void(0);">
												<span class="title">Error pages</span>
											</a>
											
											<ul>
												<li><a href="http://www.codecovers.eu/materialadmin/pages/404"><span class="title">404 page</span></a></li>

												<li><a href="http://www.codecovers.eu/materialadmin/pages/500"><span class="title">500 page</span></a></li>

											</ul>
										</li>
										<li><a href="http://www.codecovers.eu/materialadmin/pages/profile"><span class="title">User profile<span class="badge style-accent">42</span></span></a></li>

										<li><a href="http://www.codecovers.eu/materialadmin/pages/invoice"><span class="title">Invoice</span></a></li>

										<li><a href="http://www.codecovers.eu/materialadmin/pages/calendar"><span class="title">Calendar</span></a></li>

										<li><a href="http://www.codecovers.eu/materialadmin/pages/pricing"><span class="title">Pricing</span></a></li>

										<li><a href="http://www.codecovers.eu/materialadmin/pages/timeline"><span class="title">Timeline</span></a></li>

										<li><a href="http://www.codecovers.eu/materialadmin/pages/maps"><span class="title">Maps</span></a></li>

										<li><a href="http://www.codecovers.eu/materialadmin/pages/locked"><span class="title">Lock screen</span></a></li>

										<li><a href="http://www.codecovers.eu/materialadmin/pages/login"><span class="title">Login</span></a></li>

										<li><a href="http://www.codecovers.eu/materialadmin/pages/blank"><span class="title">Blank page</span></a></li>

									</ul>
								</li>-->
								<!-- END Pages -->
								
								
								
								<!-- BEGIN LEVELS -->
								<!--<li class="gui-folder">
									<a>
										<div class="gui-icon"><i class="fa fa-folder-open fa-fw"></i></div>
										<span class="title">Menu levels demo</span>
									</a>
									
									<ul>
										<li><a href="#"><span class="title">Item 1</span></a></li>
										<li><a href="#"><span class="title">Item 1</span></a></li>
										<li class="gui-folder">
											<a href="javascript:void(0);">
												<span class="title">Open level 2</span>
											</a>
											
											<ul>
												<li><a href="#"><span class="title">Item 2</span></a></li>
												<li class="gui-folder">
													<a href="javascript:void(0);">
														<span class="title">Open level 3</span>
													</a>
													
													<ul>
														<li><a href="#"><span class="title">Item 3</span></a></li>
														<li><a href="#"><span class="title">Item 3</span></a></li>
														<li class="gui-folder">
															<a href="javascript:void(0);">
																<span class="title">Open level 4</span>
															</a>
															
															<ul>
																<li><a href="#"><span class="title">Item 4</span></a></li>
																<li class="gui-folder">
																	<a href="javascript:void(0);">
																		<span class="title">Open level 5</span>
																	</a>
																	
																	<ul>
																		<li><a href="#"><span class="title">Item 5</span></a></li>
																		<li><a href="#"><span class="title">Item 5</span></a></li>
																	</ul>
																</li>
															</ul>
														</li>
													</ul>
												</li>
											</ul>
										</li>
									</ul>
								</li>-->
								<!-- END LEVELS -->
								
							</ul><!--end .main-menu -->
							<!-- END MAIN MENU -->

							<div class="menubar-foot-panel">
								<small class="no-linebreak hidden-folded">
									<span class="opacity-75">Copyright © 2018</span> <strong>Zippedi Inc.</strong>
								</small>
							</div>
						</div>
					</div>
				</div><!--end .menubar-scroll-panel-->
			</div><!--end #menubar-->
			<!-- END MENUBAR -->

			<!-- BEGIN OFFCANVAS RIGHT -->
			<div class="offcanvas">
				
				<!-- BEGIN OFFCANVAS SEARCH -->
				<div id="offcanvas-search" class="offcanvas-pane width-8">
					<div class="offcanvas-head">
						<header class="text-primary">Search</header>
						<div class="offcanvas-tools">
							<a class="btn btn-icon-toggle btn-default-light pull-right" data-dismiss="offcanvas">
								<i class="md md-close"></i>
							</a>
						</div>
					</div>

					<div class="offcanvas-body no-padding">
						<ul class="list ">
							<li class="tile divider-full-bleed">
								<div class="tile-content">
									<div class="tile-text"><strong>A</strong></div>
								</div>
							</li>
							<li class="tile">
								<a class="tile-content ink-reaction" href="#offcanvas-chat" data-toggle="offcanvas" data-backdrop="false">
									<div class="tile-icon">
										<img src="http://www.codecovers.eu/assets/img/modules/materialadmin/avatar4.jpg?1422538625" alt="">
									</div>
									<div class="tile-text">
										Alex Nelson
										<small>123-123-3210</small>
									</div>
								</a>
							</li>
							<li class="tile">
								<a class="tile-content ink-reaction" href="#offcanvas-chat" data-toggle="offcanvas" data-backdrop="false">
									<div class="tile-icon">
										<img src="http://www.codecovers.eu/assets/img/modules/materialadmin/avatar9.jpg?1422538626" alt="">
									</div>
									<div class="tile-text">
										Ann Laurens
										<small>123-123-3210</small>
									</div>
								</a>
							</li>
							<li class="tile divider-full-bleed">
								<div class="tile-content">
									<div class="tile-text"><strong>J</strong></div>
								</div>
							</li>
							<li class="tile">
								<a class="tile-content ink-reaction" href="#offcanvas-chat" data-toggle="offcanvas" data-backdrop="false">
									<div class="tile-icon">
										<img src="http://www.codecovers.eu/assets/img/modules/materialadmin/avatar2.jpg?1422538624" alt="">
									</div>
									<div class="tile-text">
										Jessica Cruise
										<small>123-123-3210</small>
									</div>
								</a>
							</li>
							<li class="tile">
								<a class="tile-content ink-reaction" href="#offcanvas-chat" data-toggle="offcanvas" data-backdrop="false">
									<div class="tile-icon">
										<img src="http://www.codecovers.eu/assets/img/modules/materialadmin/avatar8.jpg?1422538626" alt="">
									</div>
									<div class="tile-text">
										Jim Peters
										<small>123-123-3210</small>
									</div>
								</a>
							</li>
							<li class="tile divider-full-bleed">
								<div class="tile-content">
									<div class="tile-text"><strong>M</strong></div>
								</div>
							</li>
							<li class="tile">
								<a class="tile-content ink-reaction" href="#offcanvas-chat" data-toggle="offcanvas" data-backdrop="false">
									<div class="tile-icon">
										<img src="http://www.codecovers.eu/assets/img/modules/materialadmin/avatar5.jpg?1422538625" alt="">
									</div>
									<div class="tile-text">
										Mabel Logan
										<small>123-123-3210</small>
									</div>
								</a>
							</li>
							<li class="tile">
								<a class="tile-content ink-reaction" href="#offcanvas-chat" data-toggle="offcanvas" data-backdrop="false">
									<div class="tile-icon">
										<img src="http://www.codecovers.eu/assets/img/modules/materialadmin/avatar11.jpg?1422538623" alt="">
									</div>
									<div class="tile-text">
										Mary Peterson
										<small>123-123-3210</small>
									</div>
								</a>
							</li>
							<li class="tile">
								<a class="tile-content ink-reaction" href="#offcanvas-chat" data-toggle="offcanvas" data-backdrop="false">
									<div class="tile-icon">
										<img src="http://www.codecovers.eu/assets/img/modules/materialadmin/avatar3.jpg?1422538624" alt="">
									</div>
									<div class="tile-text">
										Mike Alba
										<small>123-123-3210</small>
									</div>
								</a>
							</li>
							<li class="tile divider-full-bleed">
								<div class="tile-content">
									<div class="tile-text"><strong>N</strong></div>
								</div>
							</li>
							<li class="tile">
								<a class="tile-content ink-reaction" href="#offcanvas-chat" data-toggle="offcanvas" data-backdrop="false">
									<div class="tile-icon">
										<img src="http://www.codecovers.eu/assets/img/modules/materialadmin/avatar6.jpg?1422538626" alt="">
									</div>
									<div class="tile-text">
										Nathan Peterson
										<small>123-123-3210</small>
									</div>
								</a>
							</li>
							<li class="tile divider-full-bleed">
								<div class="tile-content">
									<div class="tile-text"><strong>P</strong></div>
								</div>
							</li>
							<li class="tile">
								<a class="tile-content ink-reaction" href="#offcanvas-chat" data-toggle="offcanvas" data-backdrop="false">
									<div class="tile-icon">
										<img src="http://www.codecovers.eu/assets/img/modules/materialadmin/avatar7.jpg?1422538626" alt="">
									</div>
									<div class="tile-text">
										Philip Ericsson
										<small>123-123-3210</small>
									</div>
								</a>
							</li>
							<li class="tile divider-full-bleed">
								<div class="tile-content">
									<div class="tile-text"><strong>S</strong></div>
								</div>
							</li>
							<li class="tile">
								<a class="tile-content ink-reaction" href="#offcanvas-chat" data-toggle="offcanvas" data-backdrop="false">
									<div class="tile-icon">
										<img src="http://www.codecovers.eu/assets/img/modules/materialadmin/avatar10.jpg?1422538623" alt="">
									</div>
									<div class="tile-text">
										Samuel Parsons
										<small>123-123-3210</small>
									</div>
								</a>
							</li>
						</ul>
					</div><!--end .offcanvas-body -->
				</div><!--end .offcanvas-pane -->
				<!-- END OFFCANVAS SEARCH -->


				<!-- BEGIN OFFCANVAS CHAT -->
				<div id="offcanvas-chat" class="offcanvas-pane style-default-light width-12">
					<div class="offcanvas-head style-default-bright">
						<header class="text-primary">Chat with Ann Laurens</header>
						<div class="offcanvas-tools">
							<a class="btn btn-icon-toggle btn-default-light pull-right" data-dismiss="offcanvas">
								<i class="md md-close"></i>
							</a>
							<a class="btn btn-icon-toggle btn-default-light pull-right" href="#offcanvas-search" data-toggle="offcanvas" data-backdrop="false">
								<i class="md md-arrow-back"></i>
							</a>
						</div>
						<form class="form">
							<div class="form-group floating-label">
								<textarea name="sidebarChatMessage" id="sidebarChatMessage" class="form-control autosize" rows="1"></textarea>
								<label for="sidebarChatMessage">Leave a message</label>
							</div>
						</form>
					</div>

					<div class="offcanvas-body">
						<ul class="list-chats">
							<li>
								<div class="chat">
									<div class="chat-avatar"><img class="img-circle" src="http://www.codecovers.eu/assets/img/modules/materialadmin/avatar1.jpg?1422538623" alt=""></div>
									<div class="chat-body">
										Yes, it is indeed very beautiful.
										<small>10:03 pm</small>
									</div>
								</div><!--end .chat -->
							</li>
							<li class="chat-left">
								<div class="chat">
									<div class="chat-avatar"><img class="img-circle" src="http://www.codecovers.eu/assets/img/modules/materialadmin/avatar9.jpg?1422538626" alt=""></div>
									<div class="chat-body">
										Did you see the changes?
										<small>10:02 pm</small>
									</div>
								</div><!--end .chat -->
							</li>
							<li>
								<div class="chat">
									<div class="chat-avatar"><img class="img-circle" src="http://www.codecovers.eu/assets/img/modules/materialadmin/avatar1.jpg?1422538623" alt=""></div>
									<div class="chat-body">
										I just arrived at work, it was quite busy.
										<small>06:44pm</small>
									</div>
									<div class="chat-body">
										I will take look in a minute.
										<small>06:45pm</small>
									</div>
								</div><!--end .chat -->
							</li>
							<li class="chat-left">
								<div class="chat">
									<div class="chat-avatar"><img class="img-circle" src="http://www.codecovers.eu/assets/img/modules/materialadmin/avatar9.jpg?1422538626" alt=""></div>
									<div class="chat-body">
										The colors are much better now.
									</div>
									<div class="chat-body">
										The colors are brighter than before.
										I have already sent an example.
										This will make it look sharper.
										<small>Mon</small>
									</div>
								</div><!--end .chat -->
							</li>
							<li>
								<div class="chat">
									<div class="chat-avatar"><img class="img-circle" src="http://www.codecovers.eu/assets/img/modules/materialadmin/avatar1.jpg?1422538623" alt=""></div>
									<div class="chat-body">
										Are the colors of the logo already adapted?
										<small>Last week</small>
									</div>
								</div><!--end .chat -->
							</li>
						</ul>
					</div><!--end .offcanvas-body -->
				</div><!--end .offcanvas-pane -->
				<!-- END OFFCANVAS CHAT -->

			</div><!--end .offcanvas-->
			<!-- END OFFCANVAS RIGHT -->

		</div><!--end #base-->	
		<!-- END BASE -->


		<!-- BEGIN JAVASCRIPT -->
			
		<?php echo $this->Html->script('theme-zippedi/libs/jquery/jquery-1.11.2.min.js');?>
        <?php echo $this->Html->script('theme-zippedi/libs/jquery/jquery-migrate-1.2.1.min.js');?>
        <?php echo $this->Html->script('theme-zippedi/libs/bootstrap/bootstrap.min.js');?>
        <?php echo $this->Html->script('theme-zippedi/libs/spin.js/spin.min.js');?>
        <?php echo $this->Html->script('theme-zippedi/libs/autosize/jquery.autosize.min.js');?>
        <?php echo $this->Html->script('theme-zippedi/libs/nanoscroller/jquery.nanoscroller.min.js');?>

        <?php echo $this->Html->script('theme-zippedi/core/source/App.js');?>
        <?php echo $this->Html->script('theme-zippedi/core/source/AppNavigation.js');?>
        <?php echo $this->Html->script('theme-zippedi/core/source/AppOffcanvas.js');?>
        <?php echo $this->Html->script('theme-zippedi/core/source/AppCard.js');?>
        <?php echo $this->Html->script('theme-zippedi/core/source/AppForm.js');?>
        <?php echo $this->Html->script('theme-zippedi/core/source/AppNavSearch.js');?>
        <?php echo $this->Html->script('theme-zippedi/core/source/AppVendor.js');?>


        <?php echo $this->Html->script('theme-zippedi/core/demo/Demo.js');?>
        <?php echo $this->Html->script('theme-zippedi/core/demo/DemoLayouts.js');?>
        <?php echo $this->Html->script('zippedi/layout_functions.js');?>
        
		
		<!-- END JAVASCRIPT -->


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
<?php echo $this->Html->script('https://maps.googleapis.com/maps/api/js?key=AIzaSyDjb3YLZVorbujhYh9NkXO5WbSSViAbMk8&;sensor=false');?>

<style>
.map_canvas {
    border-top: 0px solid #fff;
    border-bottom: 0px solid #fff;
    height: 220px;
    width: 100%;
}
</style>

<div class="row">
    <div class="col-lg-12">
        <h1>
            <?php echo __('Realtime Sessions');?> <small><?php echo __('Showing all active sessions');?></small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <?php echo $this->Html->link($this->Html->tag('span', '', ['class' => 'fa fa-building-o']).' '.__('Robot Sessions'), ['controller' => 'RobotSessions', 'action' => 'index', 'plugin' => 'management'], ['escape' => false]);?>
            </li>
            <li class="active">
                <i class="icon-file-alt"></i> <?php echo __('On real time');?>
            </li>
        </ol>
    </div>
</div>

<div class="row">
    <?php foreach ($robot_sessions as $robot_session): ?>
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                    	<div class="col-sm-12">
                    		<?php echo $this->Html->image('companies/'.$robot_session->store->company->company_logo, ['style' => 'width:40px;margin: 0px 10px 0px 0px;', 'class' => 'pull-left']); ?>
                    		<h4 style="margin-bottom: 0px;"><?php echo __('Session of {0} for {1} {2} ({3}) [Session code: {4}]', [$robot_session->session_date->format('d-m-Y H:i:s'), $robot_session->store->company->company_name, $robot_session->store->store_name, $robot_session->store->store_code, $robot_session->session_code]);?></h4>

                    		<?php if($robot_session->robot_end != null):?>

                    			<small><?php echo __("Robot's journey ended {0}", $robot_session->robot_end->timeAgoInWords(['accuracy' => ['month' => 'month'], 'end' => '1 day']));?></small>

                    		<?php else:?>
                    			<?php if($robot_session->robot_start != null):?>
                    				
                    				<small><?php echo __("Robot's journey started {0}", $robot_session->robot_start->timeAgoInWords(['accuracy' => ['month' => 'month'], 'end' => '1 day']));?></small>

                    			<?php else:?>
                    				
                    				<small><?php echo __("Session started {0}", $robot_session->session_date->timeAgoInWords(['accuracy' => ['month' => 'month'], 'end' => '1 day']));?></small>

                    			<?php endif;?>
                    		<?php endif;?>
                    		<br>
                    		<br>
                    	</div>
                    	<div class="col-sm-8">
                            <div class="row">
                            	<div class="col-sm-4">
                            		<div class="panel panel-default">
										<div class="panel-heading"><?php echo $this->Html->tag('strong', __('Assortment Report'));?></div>
										<div class="panel-body">

											<?php if($robot_session->assortment_ignore_session != 1):?>
												<?php if($robot_session->assortment_finished == 1):?>
													<div class="alert alert-success pull-center"><?php echo $this->Html->tag('i', '', ['class' => 'fa fa-check']).' '.__('Loaded data at {0}', $robot_session->assortment_finished_date->timeAgoInWords(['accuracy' => ['month' => 'month'], 'end' => '1 day']));?></div>


													<ul style="list-style:none;margin-left: -40px;">
	                                                	<li>
	                                                  		<?php echo __('Start process date');?>: <?php echo $this->Html->tag('strong', $robot_session->assortment_processing_date->format('d-m-Y H:i:s'));?>
	                                                  	</li>
	                                                   	<li>
	                                                   		<?php echo __('End process date');?>: <?php echo $this->Html->tag('strong', $robot_session->assortment_finished_date->format('d-m-Y H:i:s'));?>
	                                                   	</li>
	                                                   	<li>
	                                                   		<?php echo __('Total catalogs');?>: <?php echo $this->Html->tag('span', $robot_session->total_catalogs, ['class' => 'badge']);?>
	                                                   	</li>
														<li>
															<?php echo __('Total readed products');?>: <?php echo $this->Html->tag('span', $robot_session->total_catalog_readed_products, ['class' => 'badge']);?>
														</li>
														<li>
															<?php echo __('Total not readed products');?>: <?php echo $this->Html->tag('span', $robot_session->total_catalog_unreaded_products, ['class' => 'badge']);?>
														</li>
														<li>
															<?php echo __('Total readed and blocked products');?>: <?php echo $this->Html->tag('span', $robot_session->total_catalog_readed_and_blocked_products, ['class' => 'badge']);?>
														</li>
														<li>
															<?php echo __('Total not readed and blocked products');?>: <?php echo $this->Html->tag('span', $robot_session->total_catalog_unreaded_and_blocked_products, ['class' => 'badge']);?>
														</li>
	                                                </ul>


												<?php else:?>

													<?php if($robot_session->assortment_processing == 1):?>
														<h5><?php echo __('Processing report right now');?></h5>

														<div class="bs-example">
															<div class="progress progress-striped active">
																<div class="progress-bar progress-bar-warning" style="width: 100%"></div>
															</div>
														</div>

														<ul style="list-style:none;margin-left: -40px;">
		                                                	<li>
		                                                  		<?php echo __('Start process date');?>: <?php echo $this->Html->tag('strong', $robot_session->assortment_processing_date->format('d-m-Y H:i:s'));?>
		                                                  	</li>
		                                                </ul>

													<?php else:?>

														<?php if(isset($robot_session->assortment_load_attemps) && $robot_session->assortment_load_attemps != 0):?>

															<div class="alert alert-error pull-center"><?php echo $this->Html->tag('i', '', ['class' => 'fa fa-times']).' '.__('Not Loaded Yet... {0} empty data attemps', $robot_session->assortment_load_attemps);?></div>


														<?php else:?>

															<div class="alert alert-warning pull-center"><?php echo $this->Html->tag('i', '', ['class' => 'fa fa-spinner']).' '.__('Not Loaded Yet... Waiting');?></div>
														<?php endif;?>

														<?php echo $this->Form->button(__('Ignore report'), ['type' => 'button', 'class' => 'btn btn-block btn-default', 'data-toggle' => 'modal', 'data-target' => '#ignoreReportModal', 'data-robotsessionid' => $robot_session->id, 'data-typeReport' => 'assortmentReport', 'data-sessioncode' => $robot_session->session_code, 'data-storename' => $robot_session->store->store_name, 'data-storecode' => $robot_session->store->store_code, 'data-companyname' => $robot_session->store->company->company_name]);?>

													<?php endif;?>

												<?php endif;?>

											<?php else:?>

												<div class="alert alert-danger pull-center"><?php echo $this->Html->tag('i', '', ['class' => 'fa fa-ban']).' '.__('Report Ignored');?></div>


												<?php echo $this->Form->button(__('Reactive report'), ['type' => 'button', 'class' => 'btn btn-block btn-default', 'data-toggle' => 'modal', 'data-target' => '#reactiveReportModal', 'data-robotsessionid' => $robot_session->id, 'data-typeReport' => 'assortmentReport', 'data-sessioncode' => $robot_session->session_code, 'data-storename' => $robot_session->store->store_name, 'data-storecode' => $robot_session->store->store_code, 'data-companyname' => $robot_session->store->company->company_name]);?>
											<?php endif;?>
										</div>
									</div>
                            	</div>
                            	<div class="col-sm-4">
                            		<div class="panel panel-default">
										<div class="panel-heading"><?php echo $this->Html->tag('strong', __('Price Differences Report'));?></div>
										<div class="panel-body">

											<?php if($robot_session->price_differences_ignore_session != 1):?>

												<?php if($robot_session->price_differences_labels_finished == 1):?>
													<div class="alert alert-success pull-center"><?php echo $this->Html->tag('i', '', ['class' => 'fa fa-check']).' '.__('Loaded data at {0}', $robot_session->price_differences_labels_finished_date->timeAgoInWords(['accuracy' => ['month' => 'month'], 'end' => '1 day']));?></div>

													<ul style="list-style:none;margin-left: -40px;">
	                                                	<li>
	                                                  		<?php echo __('Start process date');?>: <?php echo $this->Html->tag('strong', $robot_session->price_differences_labels_processing_date->format('d-m-Y H:i:s'));?>
	                                                  	</li>
	                                                   	<li>
	                                                   		<?php echo __('End process date');?>: <?php echo $this->Html->tag('strong', $robot_session->price_differences_labels_finished_date->format('d-m-Y H:i:s'));?>
	                                                   	</li>
	                                                   	<li>
	                                                   		<?php echo __('Total labels with price differences');?>: <?php echo $this->Html->tag('span', $robot_session->total_price_difference_detections, ['class' => 'badge']);?>
	                                                   	</li>
														<li>
															<?php echo __('Total products with price differences');?>: <?php echo $this->Html->tag('span', $robot_session->total_price_difference_products, ['class' => 'badge']);?>
														</li>
	                                                </ul>


												<?php else:?>

													<?php if($robot_session->price_differences_labels_processing == 1):?>
														<h5><?php echo __('Processing report right now');?></h5>

														<div class="bs-example">
															<div class="progress progress-striped active">
																<div class="progress-bar progress-bar-warning" style="width: 100%"></div>
															</div>
														</div>

														<ul style="list-style:none;margin-left: -40px;">
		                                                	<li>
		                                                  		<?php echo __('Start process date');?>: <?php echo $this->Html->tag('strong', $robot_session->price_differences_labels_processing_date->format('d-m-Y H:i:s'));?>
		                                                  	</li>
		                                                </ul>

													<?php else:?>

														<?php if(isset($robot_session->price_differences_load_attemps) && $robot_session->price_differences_load_attemps != 0):?>

															<div class="alert alert-error pull-center"><?php echo $this->Html->tag('i', '', ['class' => 'fa fa-times']).' '.__('Not Loaded Yet... {0} empty data attemps', $robot_session->price_differences_load_attemps);?></div>

														<?php else:?>

															<div class="alert alert-warning pull-center"><?php echo $this->Html->tag('i', '', ['class' => 'fa fa-spinner']).' '.__('Not Loaded Yet... Waiting');?></div>
														<?php endif;?>

														<?php echo $this->Form->button(__('Ignore report'), ['type' => 'button', 'class' => 'btn btn-block btn-default', 'data-toggle' => 'modal', 'data-target' => '#ignoreReportModal', 'data-robotsessionid' => $robot_session->id, 'data-typeReport' => 'priceDifferenceReport', 'data-sessioncode' => $robot_session->session_code, 'data-storename' => $robot_session->store->store_name, 'data-storecode' => $robot_session->store->store_code, 'data-companyname' => $robot_session->store->company->company_name]);?>

													<?php endif;?>

												<?php endif;?>

											<?php else:?>

												<div class="alert alert-danger pull-center"><?php echo $this->Html->tag('i', '', ['class' => 'fa fa-ban']).' '.__('Report Ignored');?></div>


												<?php echo $this->Form->button(__('Reactive report'), ['type' => 'button', 'class' => 'btn btn-block btn-default', 'data-toggle' => 'modal', 'data-target' => '#reactiveReportModal', 'data-robotsessionid' => $robot_session->id, 'data-typeReport' => 'priceDifferenceReport', 'data-sessioncode' => $robot_session->session_code, 'data-storename' => $robot_session->store->store_name, 'data-storecode' => $robot_session->store->store_code, 'data-companyname' => $robot_session->store->company->company_name]);?>

											<?php endif;?>
										</div>
									</div>
                            	</div>
                            	<div class="col-sm-4">
                            		<div class="panel panel-default">
										<div class="panel-heading"><?php echo $this->Html->tag('strong', __('Stock Alerts Report'));?></div>
										<div class="panel-body">
											
											<?php if($robot_session->facing_ignore_session != 1):?>
												<?php if($robot_session->facing_labels_finished == 1):?>
													<div class="alert alert-success pull-center"><?php echo $this->Html->tag('i', '', ['class' => 'fa fa-check']).' '.__('Loaded data at {0}', $robot_session->facing_labels_finished_date->timeAgoInWords(['accuracy' => ['month' => 'month'], 'end' => '1 day']));?></div>

													<ul style="list-style:none;margin-left: -40px;">
	                                                	<li>
	                                                  		<?php echo __('Start process date');?>: <?php echo $this->Html->tag('strong', $robot_session->facing_labels_processing_date->format('d-m-Y H:i:s'));?>
	                                                  	</li>
	                                                   	<li>
	                                                   		<?php echo __('End process date');?>: <?php echo $this->Html->tag('strong', $robot_session->facing_labels_finished_date->format('d-m-Y H:i:s'));?>
	                                                   	</li>
	                                                   	<li>
	                                                   		<?php echo __('Total detections with stock alert');?>: <?php echo $this->Html->tag('span', $robot_session->total_stock_alert_detections, ['class' => 'badge']);?>
	                                                   	</li>
														<li>
															<?php echo __('Total products with stock alert');?>: <?php echo $this->Html->tag('span', $robot_session->total_stock_alert_products, ['class' => 'badge']);?>
														</li>
	                                                </ul>


												<?php else:?>

													<?php if($robot_session->facing_labels_processing == 1):?>
														<h5><?php echo __('Processing report right now');?></h5>

														<div class="bs-example">
															<div class="progress progress-striped active">
																<div class="progress-bar progress-bar-warning" style="width: 100%"></div>
															</div>
														</div>

														<ul style="list-style:none;margin-left: -40px;">
		                                                	<li>
		                                                  		<?php echo __('Start process date');?>: <?php echo $this->Html->tag('strong', $robot_session->facing_labels_processing_date->format('d-m-Y H:i:s'));?>
		                                                  	</li>
		                                                </ul>

													<?php else:?>

														<?php if(isset($robot_session->facing_load_attemps) && $robot_session->facing_load_attemps != 0):?>

															<div class="alert alert-danger pull-center"><?php echo $this->Html->tag('i', '', ['class' => 'fa fa-times']).' '.__('Not Loaded Yet...<br>{0} empty data attemps from Zippedi API', $robot_session->facing_load_attemps);?></div>

														<?php else:?>

															<div class="alert alert-warning pull-center"><?php echo $this->Html->tag('i', '', ['class' => 'fa fa-spinner']).' '.__('Not Loaded Yet... Waiting');?></div>
														<?php endif;?>

														<?php echo $this->Form->button(__('Ignore report'), ['type' => 'button', 'class' => 'btn btn-block btn-default', 'data-toggle' => 'modal', 'data-target' => '#ignoreReportModal', 'data-robotsessionid' => $robot_session->id, 'data-typeReport' => 'stockOutReport', 'data-sessioncode' => $robot_session->session_code, 'data-storename' => $robot_session->store->store_name, 'data-storecode' => $robot_session->store->store_code, 'data-companyname' => $robot_session->store->company->company_name]);?>

													<?php endif;?>

												<?php endif;?>
											
											<?php else:?>

												<div class="alert alert-danger pull-center"><?php echo $this->Html->tag('i', '', ['class' => 'fa fa-ban']).' '.__('Report Ignored');?></div>


												<?php echo $this->Form->button(__('Reactive report'), ['type' => 'button', 'class' => 'btn btn-block btn-default', 'data-toggle' => 'modal', 'data-target' => '#reactiveReportModal', 'data-robotsessionid' => $robot_session->id, 'data-typeReport' => 'stockOutReport', 'data-sessioncode' => $robot_session->session_code, 'data-storename' => $robot_session->store->store_name, 'data-storecode' => $robot_session->store->store_code, 'data-companyname' => $robot_session->store->company->company_name]);?>

											<?php endif;?>
										</div>
									</div>
                            	</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="well">
                            	<div class="row">
                            		<div class="col-sm-5">
                            			<h5 class="text-left">
		                                    <?php 
		                                        echo $this->Html->link(__('[{0}] {1} - {2}', [$robot_session->store->store_code, $robot_session->store->company->company_name, $robot_session->store->store_name]), ['controller' => 'stores', 'action' => 'view', $robot_session->store->id, 'plugin' => 'management']);
		                                    ?>
		                                </h5>
		                                <ul class="list-unstyled">
		                                    <li>
		                                        <?php echo __('{0}', $this->Html->tag('strong', $robot_session->store->location->street_name.' '.$robot_session->store->location->street_number.($robot_session->store->location->complement != null ? ' '.$robot_session->store->location->complement : '.')))?>
		                                    </li>
		                                    <li>
		                                        <?php echo __('{0}', $this->Html->tag('strong', $robot_session->store->location->commune->commune_name))?>
		                                    </li>
		                                    <li>
		                                        <?php echo __('{0}', $this->Html->tag('strong', $robot_session->store->location->region->region_name))?>
		                                    </li>
		                                    <li>
		                                        <?php echo __('{0}', $this->Html->tag('strong', $robot_session->store->location->country->country_name))?>
		                                    </li>
		                                </ul>
                            		</div>
                            		<div class="col-sm-7">
                            			<div id="store_map_<?php echo $robot_session->store->id?>" class="map_canvas"></div>
                            		</div>
                            	</div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->Html->scriptStart(array('block' => 'scriptBottom', 'inline' => false)); ?>

                $(document).ready(function(){

                    var map_<?php echo $robot_session->store->id?> = new google.maps.Map(document.getElementById('store_map_<?php echo $robot_session->store->id?>'), {
                        center: {lat: <?php echo $robot_session->store->location->latitude?>, lng: <?php echo $robot_session->store->location->longitude?>},
                        zoom: 15,
                        zoomControl: true,
                        fullscreenControl: false,
                        streetViewControl: false,
                        mapTypeControl: false,
                        rotateControl: false,
                        scaleControl: false,
                        scrollwheel: false,
                        disableDoubleClickZoom: true,
                        draggable: false,
                    });

                    var marker_<?php echo $robot_session->store->id?> = new google.maps.Marker({
                        position: {lat: <?php echo $robot_session->store->location->latitude?>, lng: <?php echo $robot_session->store->location->longitude?>},
                        map: map_<?php echo $robot_session->store->id?>,
                        animation: google.maps.Animation.DROP,
                        title: 'Hello World!',
                    });

                    var infoWindow = new google.maps.InfoWindow({map: map_<?php echo $robot_session->store->id?>});
                });

            <?php $this->Html->scriptEnd(); ?>
        </div>
    <?php endforeach;?>
</div>


<div class="modal fade" id="ignoreReportModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<?php echo $this->Form->create(null, ['url' => ['controller' => 'RobotSessions', 'action' => 'ignoreSession', 'plugin' => 'Management']]) ?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel"><?php echo __('Ignore report');?></h4>
				</div>
				<div class="modal-body">
					
					<div id="main-message">
						<?php echo __('Are you sure to ignore the next information for load process').'?';?>

						<ul style="list-style:none;">
							<li><?php echo __('Report');?> : <strong id="report-name"></strong></li>
							<li><?php echo __('Session');?> : <strong id="session-code"></strong></li>
							<li><?php echo __('Store');?> : <strong id="store-long-name"></strong></li>
						</ul>
					</div>
					<div id="ignoreInputsDiv"></div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close');?></button>
					<button type="submit" class="btn btn-primary"><?php echo __('Ignore now');?></button>
				</div>
			<?php echo $this->Form->end();?>
		</div>
	</div>
</div>

<div class="modal fade" id="reactiveReportModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			
			<?php echo $this->Form->create(null, ['url' => ['controller' => 'RobotSessions', 'action' => 'reactiveSession', 'plugin' => 'Management']]) ?>
				
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel"><?php echo __('Reactive report');?></h4>
				</div>
				<div class="modal-body text-center">

					<div id="main-message">
						<?php echo __('Are you sure to ignore the next information for load process').'?';?>

						<ul style="list-style:none;">
							<li><?php echo __('Report');?> : <strong id="report-name"></strong></li>
							<li><?php echo __('Session');?> : <strong id="session-code"></strong></li>
							<li><?php echo __('Store');?> : <strong id="store-long-name"></strong></li>
						</ul>
					</div>
					<div id="ignoreInputsDiv"></div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close');?></button>
					<button type="submit" class="btn btn-primary"><?php echo __('Reactive now');?></button>
				</div>

			<?php echo $this->Form->end();?>
		</div>
	</div>
</div>



<?php $this->Html->scriptStart(array('block' => 'scriptBottom', 'inline' => false)); ?>
	
	$(document).ready(function(){

		$('#ignoreReportModal').on('show.bs.modal', function (event) {
			
			var button = $(event.relatedTarget);
			var robot_session_id = button.data('robotsessionid');
			var type_report = button.data('typereport');
			var type_report_name = '';

			switch(type_report) {
			    case 'assortmentReport':
			        type_report_name = '<?php echo __('Assortment report');?>';
			        break;
			    case 'priceDifferenceReport':
			        type_report_name = '<?php echo __('Price differences report');?>';
			        break;
			    case 'stockOutReport':
			        type_report_name = '<?php echo __('Stock alerts report');?>';
			        break;
			    default:
			        type_report_name = '<?php echo __('Unknown report');?>';
			}

			var store_name = button.data('storename');
			var store_code = button.data('storecode');
			var company_name = button.data('companyname');
			var session_code = button.data('sessioncode');

			var modal = $(this);
			modal.find('.modal-body #ignoreInputsDiv').html('<input name="robot_session_id" type="hidden" value="'+robot_session_id+'"></input><input name="type_report" type="hidden" value="'+type_report+'"></input>');

			modal.find('.modal-body #ignoreInputsDiv').html('<input name="robot_session_id" type="hidden" value="'+robot_session_id+'"></input><input name="type_report" type="hidden" value="'+type_report+'"></input>');

			modal.find('.modal-body #main-message #report-name').text(type_report_name);
			modal.find('.modal-body #main-message #session-code').text(session_code);
			modal.find('.modal-body #main-message #store-long-name').html('['+store_code+'] '+ company_name +' '+store_name);
		});

		$('#reactiveReportModal').on('show.bs.modal', function (event) {
			
			var button = $(event.relatedTarget);
			var robot_session_id = button.data('robotsessionid');
			var type_report = button.data('typereport');
			var type_report_name = '';

			switch(type_report) {
			    case 'assortmentReport':
			        type_report_name = '<?php echo __('Assortment report');?>';
			        break;
			    case 'priceDifferenceReport':
			        type_report_name = '<?php echo __('Price differences report');?>';
			        break;
			    case 'stockOutReport':
			        type_report_name = '<?php echo __('Stock alerts report');?>';
			        break;
			    default:
			        type_report_name = '<?php echo __('Unknown report');?>';
			}

			var store_name = button.data('storename');
			var store_code = button.data('storecode');
			var company_name = button.data('companyname');
			var session_code = button.data('sessioncode');

			var modal = $(this);
			modal.find('.modal-body #ignoreInputsDiv').html('<input name="robot_session_id" type="hidden" value="'+robot_session_id+'"></input><input name="type_report" type="hidden" value="'+type_report+'"></input>');

			modal.find('.modal-body #ignoreInputsDiv').html('<input name="robot_session_id" type="hidden" value="'+robot_session_id+'"></input><input name="type_report" type="hidden" value="'+type_report+'"></input>');

			modal.find('.modal-body #main-message #report-name').text(type_report_name);
			modal.find('.modal-body #main-message #session-code').text(session_code);
			modal.find('.modal-body #main-message #store-long-name').html('['+store_code+'] '+ company_name +' '+store_name);
		});

	});

<?php $this->Html->scriptEnd(); ?>
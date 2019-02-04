<?php if($type == 'list'):?>

	<!--<link rel="stylesheet" type="text/css" href="http://my.zippedi.com/css/theme-zippedi/libs/morris/morris.core.css?1420463396">
	<link rel="stylesheet" type="text/css" href="http://my.zippedi.com/css/theme-zippedi/libs/rickshaw/rickshaw.css?1422792967">-->

	<div class="row">
		<div class="col-md-12">
			<table class="table">
	            <thead>
	                <tr>
	                    <th class="text-left" style="border: 0px;">
	                    	<?php //echo $this->Html->image('onlyletters2.png', ['style' => 'width: 190px;', 'fullBase' => true]); ?>
	                    	<img src="http://my.zippedi.com/img/onlyletters2.png" style="width: 190px;"/>
	                    </th>
	                    <th class="text-right" style="border: 0px;">
	                    	<?php //echo $this->Html->image('companies/'.$store_data->company->company_logo, ['style' => 'width:90px;', 'fullBase' => true]); ?>
	                    	<img src="<?php echo 'http://my.zippedi.com/img/companies/'.$store_data->company->company_logo;?>" style="width: 90px;"/>
	                    </th>
	                </tr>
	            </thead>
	        </table>
	    </div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<table class="table table-bordered">
	            <thead>
	                <tr>
	                    <th class="text-center" style="background-color: #5c5c5c !important;">
	                    	<?php echo $this->Html->tag('h4', __('Price Difference Report'), ['style' => 'color:#FFF !important;']); ?>
	                    </th>
	                </tr>
	                <tr>
	                    <th class="text-center" style="background-color: #dedede !important; color: #545454 !important;">
	                    	<?php echo __('[{0}] {1} {2} - {3}',[$store_data->store_code, $store_data->company->company_name, $store_data->store_name, $robot_session->session_date->format('d-m-Y H:i:s')]); ?>
	                    </th>
	                </tr>
	            </thead>
	        </table>
	    </div>
	</div>

	<!--<div class="row" onload="init">
		<div class="col-md-12">
			<div class="panel panel-default">
			    <div class="panel-heading">
			        <?php echo $this->Html->tag('h5', __('Overview'));?>
			    </div>
				<div class="panel-body">
					<div class="row">
						<table>
							<tr>
								<!--<td>
									<!--<div id="donut-detections-differences" class="height-6" data-colors="#0fbb06,#d5cc0e"></div>
									<div id="donutchart" style="width: 900px; height: 500px;"></div>
								</td>
								<td>
									<table class="table table-condensed text-center">
			                            <tr>
			                                <td style="border: 0px !important;">
			                                    <?php echo $this->Html->tag('h5', __('Readed labels'));?>
			                                    <?php echo $this->Html->tag('h2', $data['stats']['total_detections']);?>
			                                </td>
			                                <td style="border: 0px !important;">
			                                    <?php echo $this->Html->tag('h5', __('Readed labels with difference price'));?>
			                                    <?php echo $this->Html->tag('h2', $data['stats']['total_detections_differences']);?>
			                                </td>
			                            </tr>
			                            <tr>
			                            	<?php if($data['stats']['total_labels_with_deal'] == 0):?>
				                                <td colspan="2">
				                            <?php else:?>
				                            	<td>
				                            <?php endif;?>

			                                    <?php echo $this->Html->tag('h5', __('Products with difference price'));?>
			                                    <?php echo $this->Html->tag('h2', $data['stats']['total_products']);?>
			                                </td>
			                                
		                                	<?php if($data['stats']['total_labels_with_deal'] > 0):?>
		                                		<td>
				                                    <?php //echo $this->Html->tag('h5', __('Products without price'));?>
				                                    <?php //echo $this->Html->tag('h2', $data['stats']['total_products_without_price']);?>

				                                    <?php echo $this->Html->tag('h5', __('Readed labels excluded by offers'));?>
				                                    <?php echo $this->Html->tag('h2', $data['stats']['total_labels_with_deal']);?>
				                                </td>
			                                <?php endif;?>
			                            </tr>
			                        </table>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>-->

	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-danger">
			    <div class="panel-heading">
			        <?php echo $this->Html->tag('h5', __('Labels with difference pricing'));?>
			    </div>
				<div class="panel-body">
					<div class="row">
	    				<div class="col-md-12">
		    				<?php $x=0;?>
		    				<?php foreach($data['products'] as $section_id => $collection):?>
		    					<?php if($x == 0):?>
									<div class="card">
									<?php $x = 1;?>
								<?php else:?>
									<div class="card" style="page-break-before:always;">
								<?php endif;?>

									<div class="card-head card-head-xs style-default-dark">
										<header>
											<?php echo $this->Html->tag('h4', __('{0} Section ({1} products)', [$collection['section']['section_name'], $collection['section']['count_products']]));?>
										</header>
									</div>
									<div class="card-body">
										<?php foreach($collection['data'] as $aisle => $info):?>

											<?php echo $this->Html->tag('h5', __('Aisle: {0} ({1} products)', [$aisle, count($info)]), ['style' => 'margin-left: 10px;margin-top: 10px;']);?>

											<table class="table table-hover table-condensed nowrap" cellspacing="0" width="100%" style="font-size: 11px;margin-bottom: 0px;">
												
												<?php //foreach($info as $internal_code => $product):?>
													<thead>
														<tr>
								                        	<?php
								                        		$first_column = '';
								                        		$second_column = '';

								                        		switch ($store_data->company->company_keyword) {
								            						case 'lider':
								            							$first_column = __('Int. Code');
								            							$second_column = __('EAN');                                				
								            							break;
								            						
								            						default:
								            							$first_column = __('EAN');
								            							$second_column = __('Int. Code');
								            							break;
								            					}
								                        	?>


								                        	<th><?php echo $first_column;?></th>
								                            <th><?php echo $second_column;?></th>
								                            <th><?php echo __('Product description');?></th>
								                            <th><?php echo __('Detected price');?></th>
								                            <th><?php echo __('Master price');?></th>
								                            <th><?php echo __('Lineal meter');?></th>
						                                    <th><?php echo __('Height tray');?></th>
								                            <th><?php echo __('Last price change');?></th>
								                            <th><?php echo __('Days with difference');?></th>
								                        </tr>
													</thead>
													<tbody>
														<?php foreach($info as $internal_code => $product):?>
															<?php foreach($product['detections'] as $detection):?>
																<tr class="gradeA">
																	<td style="font-size: 10px;" class="text-left">
									                        			<?php
									                    					$barcode_html = '';
									                    					$barcode_code = __('No available');

									                    					switch ($store_data->company->company_keyword) {
									                    						case 'lider':
									                    							$barcode_code = $this->Walmart->codeFormat($store_data->company->company_keyword, $product['internal_code']);
									                    							$barcode_html = $barcode->getBarcode($barcode_code, $barcode::TYPE_EAN_13, 1);
									                    							break;
									                    						
									                    						default:
									                    							if($product['ean13'] != ''){
									                    								$barcode_code = $this->Ean->format($product['ean13']);
									                    								$barcode_html = $barcode->getBarcode($barcode_code, $barcode::TYPE_EAN_13, 1);
									                    							}
									                    							
									                    							break;
									                    					}

									                        				//echo $barcode_html;

									                        				if($barcode_html != ''){
									                                           echo '<img style="width: 77px;" src="data:image/png;base64,'.base64_encode($barcode_html).'"><br>';
									                                        }

									                        				echo $this->Html->link($barcode_code, 'javascript:void(0);', ['style' => 'color: #000;']);
									                        			?>
									                        		</td>
									                        		<td>
									                        			<?php 
									                        				$second_code = '';
									                        				switch ($store_data->company->company_keyword) {
									                    						case 'lider':
									                    							$second_code = $this->Ean->format($product['ean13']);
									                    							break;
									                    						
									                    						default:
									                    							$second_code = $product['internal_code'];
									                    							break;
									                    					}

									                        				echo $second_code; 
									                        			?>
									                        		</td>
																	<td>
									                        			<?php
									                        				echo $this->Html->tag('strong', $this->Html->link($product['description'], 'javascript:void(0);'));
									                        			?>
									                        		</td>
																	<td>
							                                			<?php echo $this->Number->currency($detection['label_price'], 'CLP', ['precision' => 2]); ?>
							                                		</td>
									                        		<td>
									                        			<?php 
									                        				echo $this->Html->tag('h5', $this->Number->currency($product['price_update']['price'], 'CLP', ['precision' => 2]), ['style' => 'margin-top: 0px;margin-bottom: 0px;']);
									                        			?>
									                        		</td>

									                        		<td>
							                                			<?php echo $this->Number->precision($detection['location_x'], 2).' mts'; 
							                                			?>
							                                		</td>
							                                		<td>
							                                			<?php echo $this->Number->precision($detection['location_z'], 2).' mts'; 
							                                			?>
							                                		</td>
									                        		<td><?php echo $product['price_update']['company_updated']->format('d-m-Y H:i:s'); ?></td>
									                        		
									                        		<?php if($product['price_update']['days_with_difference'] > 2):?>
							                                			<td class="danger">
							                                		<?php else:?>
							                                			<td>
							                                		<?php endif;?>
							                                			<?php echo __($product['price_update']['days_with_difference']); ?>
							                                		</td>
																</tr>
															<?php endforeach;?>
														<?php endforeach;?>
													
													
													<?php //if(count($product['detections']) > 0):?>	
														<!--<thead style="border-left: 1px solid #8e8c8c;border-right: 1px solid #8e8c8c;">
															<tr>
							                                    <th ><?php //echo __('Lineal meter');?></th>
							                                    <th colspan="2"><?php //echo __('Height tray');?></th>
							                                    <th colspan="2"><?php //echo __('Detected price');?></th>
							                                    <th><?php //echo __('Aisle');?></th>
							                                    
															</tr>
														</thead>
														<tbody style="border-left: 1px solid #8e8c8c;border-right: 1px solid #8e8c8c;">
															<?php //foreach($product['detections'] as $detection):?>
																
																<tr class="info">
							                                		<td>
							                                			<?php //echo $this->Number->precision($detection['location_x'], 2).' mts'; 
							                                			?>
							                                		</td>
							                                		<td colspan="2">
							                                			<?php //echo $this->Number->precision($detection['location_z'], 2).' mts'; 
							                                			?>
							                                		</td>
							                                		<td colspan="2">
							                                			<?php //echo $this->Number->currency($detection['label_price'], 'CLP', ['precision' => 2]); ?>
							                                		</td>
							                                		<td><?php //echo $detection['aisle']; ?></td>
																</tr>
															<?php //endforeach;?>
														</tbody>-->
													<?php //endif;?>

													<!---<tr><td colspan=6 style="border: 1px solid #8e8c8c;border-top: none;">&nbsp;</td></tr>-->
													<!--<tr><td colspan=6 style="border: none;border-bottom: 1px solid #8e8c8c;">&nbsp;</td></tr>-->
													</tbody>
												<?php //endforeach;?>

											</table>
										<?php endforeach;?>
									</div>
								</div>

							<?php endforeach;?>
	    				</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="http://www.google.com/jsapi"></script> 
    <script type="text/javascript">
		google.charts.load("current", {packages:["corechart"]});
		google.charts.setOnLoadCallback(drawChart);
		function drawChart() {
			var data = google.visualization.arrayToDataTable([
				['Task', 'Hours per Day'],
				['Work',     11],
				['Eat',      2],
				['Commute',  2],
				['Watch TV', 2],
				['Sleep',    7]
			]);

			var options = {
			 	title: 'My Daily Activities',
				pieHole: 0.4,
			};

			var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
			chart.draw(data, options);
		}

		google.visualization.events.addListener(tableChart, 'ready', myReadyHandler); 
		
		function myReadyHandler(){ 
			window.status = "ready"; 
		} 
    </script>

<?php else:?>
	<section style="margin-top: -35px;">
		<div class="section-header">
			<ol class="breadcrumb">
				<li style="opacity: 1;">
					<?php echo $this->Html->link(__('Reports'), '/robot-reports');?>
				</li>
				<li class="active" style="font-size: 16px;opacity: 1;">
					<?php echo __('Price difference report');?>
				</li>
			</ol>
			<?php echo $this->Html->image('onlyletters2.png', ['fullBase' => true, 'style' => 'width: 115px;margin: -40px 0px;', 'class' => 'pull-right']);?>
		</div>
		<div class="section-body">
			<div class="card">

				<div class="card-head style-default-light">
					<?php echo $this->Html->image('companies/'.$store_data->company->company_logo, ['style' => 'width:40px;margin-left: 12px;', 'fullBase' => true]).' '.$this->Html->tag('strong', __('Report information: [{0}] {1} - {2}',[$store_data->store_code, $store_data->store_name, $robot_session->session_date->format('d-m-Y H:i:s')]), ['style' => 'font-size: 14px;margin-left: 15px;']); ?>
				</div>

				<div class="card-body">
					<div class="row">

						<div class="col-sm-12 col-md-12 col-lg-12">

							<?php $x=0;?>
							<?php foreach($data['products'] as $section_id => $collection):?>
								
								<?php if($x == 0):?>
									<div class="results-class" style="padding: 20px 30px;">
									<?php $x = 1;?>
								<?php else:?>
									<div class="results-class" style="padding: 20px 30px;page-break-before:always;">
								<?php endif;?>

									<div class="margin-bottom-xxl">
										<span class="text-light text-lg">
											<strong>
												<?php echo $collection['section']['section_name'];?>
											</strong>
											<?php echo '('.$collection['section']['count_products'].' '.__('products').')';?> 
										</span>
									</div>
									<?php foreach($collection['data'] as $aisle => $info):?>

										<?php echo $this->Html->tag('h3', __('Aisle: {0} ({1} products)', [$aisle, count($info)]), ['style' => 'margin-left: 10px;margin-top: 10px;']);?>
										
										<div class="list-results">
											
											<?php foreach($info as $internal_code => $product):?>

												<?php
													$first_column = '';
			                                		$second_column = '';
			                                		$barcode_html = '';
		                        					$barcode_code = __('No available');
		                        					$second_code = '';

			                                		switch ($store_data->company->company_keyword){
		                        						case 'lider':
		                        							$first_column = __('Int. Code');
		                        							$second_column = __('EAN');

		                        							$barcode_code = $this->Walmart->codeFormat($store_data->company->company_keyword, $product['internal_code']);
		                        							$barcode_html = $barcode->getBarcode($barcode_code, $barcode::TYPE_EAN_13, 1);
		                        							$second_code = $this->Ean->format($product['ean13']);
		                        							break;
		                        						
		                        						default:
		                        							$first_column = __('EAN');
		                        							$second_column = __('Int. Code');
		                        							$second_code = $product['internal_code'];

		                        							if($product['ean13'] != '' && $product['ean13'] != 'None'){
		                        								$barcode_code = $this->Ean->format($product['ean13']);
		                        								$barcode_html = $barcode->getBarcode($barcode_code, $barcode::TYPE_EAN_13, 1);
		                        							}

		                        							break;
		                        					}
												?>

												<div class="col-xs-12 col-lg-12 hbox-xs">
													<table class="table">
														<tr>
															<th class="text-left" style="border: 0px;">
																<div class="row">
																	<div class="col-sm-3 text-left" style="margin-left: 10px;">
																		<?php
						                                					if($barcode_html != ''){
						                                                       echo '<img class="img-responsive text-left" style="" src="data:image/png;base64,'.base64_encode($barcode_html).'">';
						                                                    }

							                                				echo $this->Html->tag('span', $barcode_code, ['style' => 'color: #000;font-size: 13px;']);
								                                	?>
																	</div>
																	<div class="col-sm-9">
																		<div class="clearfix">
																			<div class="col-lg-12 margin-bottom-lg">
																				<?php echo $this->Html->tag('span', $product['description'], ['class' => 'text-lg text-medium']);?>
																			</div>
																		</div>
																		<div class="clearfix">
																			<div class="col-md-6">
																				<span class="fa fa-barcode text-sm"></span> &nbsp;
																				<?php echo $second_code;?>
																			</div>
																			<div class="col-md-6">
																				<span class="fa fa-money text-sm"></span> &nbsp;
																				<?php echo $this->Html->tag('strong', $this->Number->currency($product['price_update']['price'], 'CLP', ['precision' => 2]), ['class' => 'text-danger']);?>
																			</div>
																		</div>
																		<div class="clearfix">
																			<div class="col-lg-12">
																				<span>
																					<span class="fa fa-exchange text-sm"></span> &nbsp;
																					<?php echo $product['price_update']['company_updated']->format('d-m-Y H:i:s'); ?>


																					<?php 
																						if($product['price_update']['days_with_difference'] > 2):?>
																							(<span class="text-primary">
																								<?php echo $product['price_update']['days_with_difference'].' '.__('days with difference');?>
																							</span>)
																					<?php else:?>
																						<?php if($product['price_update']['days_with_difference'] != null):?>
																							(<span class="text-default">
																								<?php echo $product['price_update']['days_with_difference'].' '.__('days with difference');?>
																							</span>)
																						<?php endif;?>
																					<?php endif;?>
																				</span>
																			</div>
																		</div>
																	</div>
																</div>
															</th>
															<th class="text-center" style="border: 0px;">
																<?php if(count($product['detections']) > 0):?>	
																	<div class="row">
																		<div class="col-sm-12">	
																			<?php if($product['price_update']['days_with_difference'] > 2):?>
																				<div class="alert alert-warning">
																					
																					<strong onclick="Javascript:alert_off();">
																						<i class="fa fa-exclamation-triangle fa-fw text-warning timeAlert text-center" data-toggle="tooltip" data-placement="right" data-original-title="<?php echo __('Price alert');?>" style="font-size: 16px;"></i> 
																					</strong><?php echo __('This product has a price difference greater than 2 days').'!';?>
																				</div>

																			<?php endif;?>

																			
																			<table class="table table-condensed nowrap" cellspacing="0" width="100%" style="font-size: 12px;margin-bottom: 0px;">
																				<thead>
																					<tr class="text-center">
																						<td colspan="5">
																							<?php echo __('Detections');?>
													                                    </td>
																					</tr>
																					<tr>
													                                    <td>
													                                    	<?php echo __('Aisle');?>
													                                    </td>
													                                    <td>
													                                    	<?php echo __('Detected price');?>
													                                    </td>
													                                    <td>
													                                    	<?php echo __('Lineal meter');?>
													                                    </td>
													                                    <td>
													                                    	<?php echo __('Height tray');?>
													                                    </td>
																					</tr>
																				</thead>
																				<tbody>
																					
																					<?php foreach($product['detections'] as $detection):?>
																						
																						<tr class="info">
													                                		<td> 
													                                			<?php echo $detection['aisle']; ?>
													                                		</td>
													                                		<td> 
													                                			<?php echo $this->Number->currency($detection['label_price'], 'CLP', ['precision' => 2]); ?>
													                                		</td>
													                                		<td>
													                                			<?php echo $this->Number->precision($detection['location_x'], 2).' mts'; 
													                                			?>
													                                		</td>
													                                		<td>
													                                			<?php echo $this->Number->precision($detection['location_z'], 2).' mts'; 
													                                			?>
													                                		</td>
																						</tr>
																					<?php endforeach;?>
																				</tbody>
																			</table>
																			
																		</div>
																	</div>
																<?php endif;?>
															</th>
														</tr>
													</table>
												</div>
											<?php endforeach;?>

											<!--<div class="text-center">
												<ul class="pagination">
													<li class="disabled"><a href="#">«</a></li>
													<li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
													<li><a href="#">2</a></li>
													<li><a href="#">3</a></li>
													<li><a href="#">4</a></li>
													<li><a href="#">5</a></li>
													<li><a href="#">»</a></li>
												</ul>
											</div>-->
										</div>
									<?php endforeach;?>
								</div>
							<?php endforeach;?>
						</div><!--end .col -->
					</div><!--end .row -->
				</div><!--end .card-body -->
				<!-- END SEARCH RESULTS -->

			</div><!--end .card -->
		</div><!--end .section-body -->
	</section>
<?php endif;?>
<?php if($type == 'list'):?>
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
	                    	<?php echo $this->Html->tag('h4', __('Stock Alert Report'), ['style' => 'color:#FFF !important;']); ?>
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

	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-danger">
			    <div class="panel-heading">
			        <?php echo $this->Html->tag('h5', __('Products with stock out alert'));?>
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
						                                    <th><?php echo __('Lineal meter');?></th>
							                                <th><?php echo __('Height tray');?></th>
							                                <th><?php echo __('Detected price');?></th>
						                                    <th><?php echo __('Stock In Room');?></th>
						                                    <th><?php echo __('Stock In Warehouse');?></th>
						                                    <th><?php echo __('Stock In Transit');?></th>
						                                    <th><?php echo __('Alerts last 30 days');?></th>
								                        </tr>
													</thead>
													<tbody>
														<?php foreach($info as $internal_code => $product):?>
															<?php foreach($product['detections'] as $detection):?>
																<tr class="gradeA">
																	<td style="font-size: 11px;" class="text-left">
									                        			<?php
									                    					$barcode_html = '';
									                    					$barcode_code = __('No available');

									                    					switch ($store_data->company->company_keyword) {
									                    						case 'lider':
									                    							$barcode_code = $this->Walmart->codeFormat($store_data->company->company_keyword, $product['internal_code']);
									                    							$barcode_html = $barcode->getBarcode($barcode_code, $barcode::TYPE_EAN_13, 1);
									                    							break;
									                    						
									                    						default:
									                    							if($product['ean13'] != '' && $product['ean13'] != 'None'){
									                    								$barcode_code = $this->Ean->format($product['ean13']);
									                    								$barcode_html = $barcode->getBarcode($barcode_code, $barcode::TYPE_EAN_13, 1);
									                    							}
									                    							
									                    							break;
									                    					}

									                        				//echo $barcode_html;

									                        				if($barcode_html != ''){
									                                           echo '<img style="width: 80px;" src="data:image/png;base64,'.base64_encode($barcode_html).'"><br>';
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

									                        				if(isset($product['catalog_update']['enabled']) && $product['catalog_update']['enabled'] == 0){
							                                					echo '<br>'.$this->Html->div('label label-danger', __('Blocked'));
							                                				}
									                        			?>
									                        		</td>
																	<td>
									                        			<?php
									                        				echo $this->Html->tag('strong', $this->Html->link($product['description'], 'javascript:void(0);'));
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
							                                		<td>
							                                			<?php echo $this->Number->currency($detection['label_price'], 'CLP', ['precision' => 2]); ?>
							                                		</td>
																	<td>
							                                			<?php 
							                                				if(isset($product['stock_update'])){

							                                					if($product['stock_update']['stock'] > $product['stock_update']['last_stock']){

							                                						echo $this->Html->tag('h5', $this->Html->tag('i', '', ['class' => 'md md-trending-up text-success']).' '.$product['stock_update']['stock'], ['escape' => false, 'class' => 'pointer', 'data-toggle' => 'tooltip', 'data-placement' => 'left', 'title' => __('Last Stock: {0} ({1})', [$product['stock_update']['last_stock'], $product['stock_update']['company_updated']])]);	
							                                					}
							                                					else{
							                                						echo $this->Html->tag('h5', $this->Html->tag('i', '', ['class' => 'md md-trending-down text-danger']).' '.$product['stock_update']['stock'], ['escape' => false, 'class' => 'pointer','data-toggle' => 'tooltip', 'data-placement' => 'left', 'title' => __('Last Stock: {0} ({1})', [$product['stock_update']['last_stock'], $product['stock_update']['company_updated']])]);	
							                                					}

							                                				}
							                                				else{
							                                					echo $this->Html->tag('small', __('No data'), ['style' => 'margin-top: 0px;margin-bottom: 0px;']);
							                                				}
							                                			?>
							                                		</td>
							                                		<td>
							                                			<?php 
							                                				if(isset($product['stock_update'])){
							                                					echo $this->Html->tag('small', $product['stock_update']['stock_warehouse'], ['style' => 'margin-top: 0px;margin-bottom: 0px;']);
							                                				}
							                                				else{
							                                					echo $this->Html->tag('small', __('No data'), ['style' => 'margin-top: 0px;margin-bottom: 0px;']);
							                                				}
							                                			?>
							                                		</td>
							                                		<td>
							                                			<?php 
							                                				if(isset($product['stock_update'])){
							                                					echo $this->Html->tag('small', $product['stock_update']['stock_in_transit'], ['style' => 'margin-top: 0px;margin-bottom: 0px;']);
							                                				}
							                                				else{
							                                					echo $this->Html->tag('small', __('No data'), ['style' => 'margin-top: 0px;margin-bottom: 0px;']);
							                                				}
							                                			?>
							                                		</td>
							                                		<td>
							                                			<?php 
							                                				if(isset($product['stock_update'])){

							                                					if($product['stock_update']['30_days_alerts'] > 0){
							                                						
							                                						if($product['stock_update']['30_days_alerts'] == 1){
							                                							
							                                							$alert_text = __('alert');
							                                						}
							                                						else{
							                                							$alert_text = __('alerts');
							                                						}

							                                						echo $this->Html->tag('h5', $this->Html->tag('i', '', ['class' => 'fa fa-exclamation-triangle text-warning']).' '.$product['stock_update']['30_days_alerts'].' '.$alert_text, ['escape' => false]);
							                                					}
							                                					else{
							                                						echo $this->Html->tag('small', $this->Html->tag('i', '', ['class' => 'fa fa-thumbs-up text-success']).' '.__('No alerts'), ['escape' => false]);
							                                					}
							                                				}
							                                				else{
							                                					echo $this->Html->tag('small', __('No data'), ['style' => 'margin-top: 0px;margin-bottom: 0px;']);
							                                				}
							                                			?>
							                                		</td>
																</tr>
															<?php endforeach;?>
														<?php endforeach;?>
													</tbody>
													
													<?php //if(count($product['detections']) > 0):?>	
														<!--<thead style="border-left: 1px solid #8e8c8c;border-right: 1px solid #8e8c8c;">
															<tr>
							                                    <th colspan="2"><?php //echo __('Lineal meter');?></th>
							                                    <th colspan="2"><?php //echo __('Height tray');?></th>
							                                    <th colspan="2"><?php //echo __('Detected price');?></th>
							                                    <th><?php //echo __('Aisle');?></th>
							                                    
															</tr>
														</thead>
														<tbody style="border-left: 1px solid #8e8c8c;border-right: 1px solid #8e8c8c;">
															<?php //foreach($product['detections'] as $detection):?>
																
																<tr class="info">
							                                		<td colspan="2">
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
																	<div class="col-sm-3 text-left pointer" data-toggle="tooltip" title="<?php echo $first_column;?>" data-placement="right" style="margin-top: 7px;margin-left: 10px;">
																		<?php
						                                					if($barcode_html != ''){
						                                                       echo '<img class="text-left" style="" src="data:image/png;base64,'.base64_encode($barcode_html).'">';
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
																			<div class="col-md-6 opacity-75 pointer" data-toggle="tooltip" title="<?php echo $second_column;?>" data-placement="right">
																				<span class="fa fa-tags text-sm"></span> &nbsp;
																				<?php echo $second_code;?>
																			</div>
																			<?php if(isset($product['stock_update'])):?>

						                                						<?php if($product['stock_update']['stock'] > $product['stock_update']['last_stock']):?>

						                                							<div class="col-md-6 opacity-75 pointer" data-toggle="tooltip" title="<?php echo __('Stock Update: {0}', $product['stock_update']['company_updated']->format('d-m-Y H:i'));?>" data-placement="bottom">
																						<span class="md md-trending-up text-sm text-success"></span> &nbsp;
																						<?php echo $this->Html->tag('strong', $product['stock_update']['stock']);?>
																					</div>
						                                						<?php else:?>
						                                							<div class="col-md-6 opacity-75 pointer" data-toggle="tooltip" title="<?php echo __('Stock Update: {0}', $product['stock_update']['company_updated']->format('d-m-Y H:i'));?>" data-placement="right">
																						<span class="md md-trending-down text-sm text-danger"></span> &nbsp;
																						<?php echo $this->Html->tag('strong', $product['stock_update']['stock']);?>
																					</div>
						                                						<?php endif;?>
						                                					
						                                					<?php else:?>

						                                						<div class="col-md-6 opacity-75 pointer" data-toggle="tooltip" title="<?php echo __('Stock');?>" data-placement="right">
																					<span class="md md-dnd-on text-sm"></span> &nbsp;
																					<?php echo $this->Html->tag('strong', __('No data'));?>
																				</div>
						                                					<?php endif;?>
																			
																		</div>
																		<div class="clearfix">
																			<?php if(isset($product['stock_update'])):?>
																					
																				<div class="col-md-6 opacity-75 pointer" data-toggle="tooltip" title="<?php echo __('Stock In Warehouse');?>" data-placement="bottom">
																					<span class="fa fa-building text-sm"></span> &nbsp;
																					<?php echo $this->Html->tag('strong', $product['stock_update']['stock_warehouse']);?>
																				</div>

																			<?php else:?>
																				<div class="col-md-6 opacity-75 pointer" data-toggle="tooltip" title="<?php echo __('Stock In Warehouse');?>" data-placement="bottom">
																					<span class="fa fa-building text-sm"></span> &nbsp;
																					<?php echo $this->Html->tag('strong', __('No data'));?>
																				</div>
																			<?php endif;?>

																			<?php if(isset($product['stock_update'])):?>
																					
																				<div class="col-md-6 opacity-75 pointer" data-toggle="tooltip" title="<?php echo __('Stock In Transit');?>" data-placement="bottom">
																					<span class="fa fa-truck text-sm"></span> &nbsp;
																					<?php echo $this->Html->tag('strong', $product['stock_update']['stock_in_transit']);?>
																				</div>

																			<?php else:?>
																				<div class="col-md-6 opacity-75 pointer" data-toggle="tooltip" title="<?php echo __('Stock In Transit');?>" data-placement="bottom">
																					<span class="fa fa-truck text-sm"></span> &nbsp;
																					<?php echo $this->Html->tag('strong', __('No data'));?>
																				</div>
																			<?php endif;?>
																		</div>
																	</div>
																</div>
															</th>
															<th class="text-center" style="border: 0px;">
																<?php if(count($product['detections']) > 0):?>	
																	<div class="row">
																		<div class="col-sm-12">	
																			<?php if(isset($product['stock_update']) && $product['stock_update']['30_days_alerts'] > 0):?>
																				<div class="alert alert-warning text-center hidden-xs">
																					
																					<strong onclick="Javascript:alert_off();">
																						<i class="fa fa-exclamation-triangle fa-fw text-warning timeAlert text-center" data-toggle="tooltip" data-placement="right" data-original-title="<?php echo __('Stock alert');?>" style="font-size: 16px;"></i> 
																					</strong><?php echo ($product['stock_update']['30_days_alerts'] == 1) ? __('This product has {0} stock alert in the last 30 days', $product['stock_update']['30_days_alerts']) : __('This product has {0} stock alerts in the last 30 days', $product['stock_update']['30_days_alerts']).'!';?>
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
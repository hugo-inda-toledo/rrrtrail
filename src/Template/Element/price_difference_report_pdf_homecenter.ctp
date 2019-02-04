<style>
	thead { display: table-header-group }
	tfoot { display: table-row-group }
	tr { page-break-inside: avoid }
	body { font-family: sans-serif; }
</style>
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

<div class="row">
	<div class="col-sm-12">
		<table class="table table-condensed text-center">
			<?php if($robot_session->total_detections != ''):?>
				<tr>
	        		<td colspan="2" style="border: 0px !important; text-align:center;">
	                    <?php echo $this->Html->tag('h5', __('Readed labels'));?>
	                    <?php echo $this->Html->tag('h2', number_format($robot_session->total_detections, 0, '', '.'));?>
	                </td>
	            </tr>
        	<?php endif;?>
            <tr>
                <td style="border: 0px !important; text-align:center;">
                    <?php echo $this->Html->tag('h5', __('Readed labels with difference price'));?>
                    <?php echo $this->Html->tag('h2', $robot_session->total_price_difference_detections);?>
                </td>
                <td style="border: 0px !important; text-align:center;">
                    <?php echo $this->Html->tag('h5', __('Products with difference price'));?>
                    <?php echo $this->Html->tag('h2', $robot_session->total_price_difference_products);?>
                </td>
            </tr>
        </table>
	</div>
</div>


<div class="row">
	<div class="col-md-12">
		<div class="panel panel-danger">
		    <div class="panel-heading">
		        <?php 
		        	echo $this->Html->tag('h5', __('Labels with difference pricing'));

		        	$first_column = __('EAN');
					$second_column = __('SKU');
		        ?>
		    </div>
			<div class="panel-body">
				<div class="row">
    				<div class="col-md-12">
	    				<?php $y=0;?>
	    				<?php foreach($data['products'] as $section_id => $collection):?>
	    					<?php if($y == 0):?>
								<div class="card">
								<?php $y = 1;?>
							<?php else:?>
								<div class="card" style="page-break-before:always;">
							<?php endif;?>

								<div class="card-head card-head-xs style-default">
									<header class="pull-right">
										<?php echo $this->Html->tag('h4', __('{0} Section ({1} products)', [$collection['section']['section_name'], $collection['section']['count_products']]));?>
									</header>
									<br>
								</div>
								<div class="card-body">
									<?php foreach($collection['data'] as $aisle => $info):?>

										<br>
										<?php echo $this->Html->tag('h5', __('Aisle: {0} ({1} products)', [$aisle, count($info)]), [/*'style' => 'margin-left: 10px;margin-top: 10px;', */'class' => 'text-right']);?>

										<table class="table table-bordered table-condensed nowrap" cellspacing="0" width="100%" style="font-size: 12px;margin-bottom: 0px;">
											<thead>
												<tr>
									            	
									            	<th><?php echo $first_column;?></th>
									                <th><?php echo $second_column;?></th>
									                <th><?php echo __('Product description');?></th>
									                <th><?php echo __('Master price');?></th>
									                <th><?php echo __('Last price change');?></th>
									                <th><?php echo __('Days with difference');?></th>
									                <th><?php echo __('Alerts in last 30 days');?></th>
									                <th><?php echo __('Detected price');?></th>
									                <th><?php echo __('Lineal meter');?></th>
									                <th><?php echo __('Height tray');?></th>
									            </tr>
									        </thead>

											
												
											<tbody>
												<?php foreach($info as $internal_code => $product):?>
													
													

													<?php $x = 0;?>
													<?php $rowspan_tag= '';?>
													<?php foreach($product['detections'] as $detection):?>



															<?php if(count($product['detections']) >= 2 && $x == 0):?>
																<tr>

																	<td style="font-size: 13px;" class="text-left" rowspan="<?php echo count($product['detections']);?>">
								                            			<?php
								                        					$barcode_html = '';
								                        					$barcode_code = __('No available');

								                        					switch ($store_data->company->company_keyword) {
								                        						case 'lider':
								                        							$barcode_code = $this->Walmart->codeFormat($store_data->company->company_keyword, $product['internal_code']);
								                        							$barcode_html = $barcode->getBarcode($barcode_code, $barcode::TYPE_EAN_13, 1);
								                        							break;
								                        						
								                        						default:
								                        							if($product['ean13'] != '' && strlen($product['ean13']) <= 13){
								                        								$barcode_code = $this->Ean->format($product['ean13']);
								                        								$barcode_html = $barcode->getBarcode($barcode_code, $barcode::TYPE_EAN_13, 1);
								                        							}
								                        							
								                        							break;
								                        					}


								                            				//echo $barcode_html;

								                            				if($barcode_html != ''){
								                                               echo '<img style="" src="data:image/png;base64,'.base64_encode($barcode_html).'"><br>';
								                                            }

								                            				echo $this->Html->link($barcode_code, 'javascript:void(0);', ['style' => 'color: #000;']);
								                            			?>
								                            		</td>
								                            		<td rowspan="<?php echo count($product['detections']);?>">
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
																	<td rowspan="<?php echo count($product['detections']);?>">
								                            			<?php
								                            				echo $this->Html->tag('strong', $this->Html->link($product['description'], 'javascript:void(0);'));
								                            			?>
								                            		</td>
								                            		<td style="color: #8e0417;" rowspan="<?php echo count($product['detections']);?>">
								                            			<?php 
								                            				echo $this->Html->tag('h4', $this->Number->currency($product['price_update']['price'], 'CLP', ['precision' => 2]));
								                            			?>
								                            		</td>
								                            		<td rowspan="<?php echo count($product['detections']);?>"><?php echo $product['price_update']['company_updated']->format('d-m-Y H:i:s'); ?></td>

								                            		<td class="text-center" rowspan="<?php echo count($product['detections']);?>">
								                            			<?php 
								                            				if($product['price_update']['days_with_difference'] == 0){
								                            					echo __('Less than 24 hours');
								                            				}
								                            				else{
								                            					if($product['price_update']['days_with_difference'] > 2){

								                            						echo ' '.$this->Html->tag('span', $this->Html->tag('i', '', ['class' => 'fa fa-exclamation-triangle fa-2x text-danger']), ['class' => 'parpadea']).' ';
								                            					}

								                            					if($product['price_update']['days_with_difference'] == 2){

								                            						echo ' '.$this->Html->tag('span', $this->Html->tag('i', '', ['class' => 'fa fa-exclamation-triangle fa-2x text-warning']), ['class' => 'parpadea']).' ';
								                            					}

								                            					echo $this->Html->tag('span', __($product['price_update']['days_with_difference']), ['style' => 'font-size:17px;']);
								                            				}
								                            			?>
								                            		</td>
								                            		<td class="text-center" rowspan="<?php echo count($product['detections']);?>">
							                                			<?php 
						                                					if($product['price_update']['30_days_alerts'] > 0){
						                                						
						                                						if($product['price_update']['30_days_alerts'] == 1){
						                                							
						                                							$alert_text = __('alert');
						                                						}
						                                						else{
						                                							$alert_text = __('alerts');
						                                						}

						                                						echo $this->Html->tag('h5', $this->Html->tag('i', '', ['class' => 'fa fa-exclamation-triangle text-warning']).' '.$product['price_update']['30_days_alerts'].' '.$alert_text, ['escape' => false]);
						                                					}
						                                					else{
						                                						echo $this->Html->tag('h5', $this->Html->tag('i', '', ['class' => 'fa fa-thumbs-up text-success']).' '.__('No alerts'), ['escape' => false]);
						                                					}
							                                			?>
							                                		</td>
																	<td> 
								                            			<?php echo $this->Html->tag('h4', $this->Number->currency($detection['label_price'], 'CLP', ['precision' => 2])); ?>
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
																<?php $x++;?>
															<?php else:?>

																<tr>
																	<?php if($x == 0):?>									
																		<td style="font-size: 13px;" class="text-left">
									                            			<?php
									                        					$barcode_html = '';
									                        					$barcode_code = __('No available');

									                        					switch ($store_data->company->company_keyword) {
									                        						case 'lider':
									                        							$barcode_code = $this->Walmart->codeFormat($store_data->company->company_keyword, $product['internal_code']);
									                        							$barcode_html = $barcode->getBarcode($barcode_code, $barcode::TYPE_EAN_13, 1);
									                        							break;
									                        						
									                        						default:
									                        							if($product['ean13'] != '' && strlen($product['ean13']) <= 13){
									                        								$barcode_code = $this->Ean->format($product['ean13']);
									                        								$barcode_html = $barcode->getBarcode($barcode_code, $barcode::TYPE_EAN_13, 1);
									                        							}
									                        							
									                        							break;
									                        					}


									                            				//echo $barcode_html;

									                            				if($barcode_html != ''){
									                                               echo '<img style="" src="data:image/png;base64,'.base64_encode($barcode_html).'"><br>';
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
									                            		<td style="color: #8e0417;">
									                            			<?php 
									                            				echo $this->Html->tag('h4', $this->Number->currency($product['price_update']['price'], 'CLP', ['precision' => 2]));
									                            			?>
									                            		</td>
									                            		<td><?php echo $product['price_update']['company_updated']->format('d-m-Y H:i:s'); ?></td>

									                            		<td class="text-center">
									                            			<?php 
									                            				if($product['price_update']['days_with_difference'] == 0){
									                            					echo __('Less than 24 hours');
									                            				}
									                            				else{
									                            					if($product['price_update']['days_with_difference'] > 2){

									                            						echo ' '.$this->Html->tag('span', $this->Html->tag('i', '', ['class' => 'fa fa-exclamation-triangle fa-2x text-danger']), ['class' => 'parpadea']).' ';
									                            					}

									                            					if($product['price_update']['days_with_difference'] == 2){

									                            						echo ' '.$this->Html->tag('span', $this->Html->tag('i', '', ['class' => 'fa fa-exclamation-triangle fa-2x text-warning']), ['class' => 'parpadea']).' ';
									                            					}

									                            					echo $this->Html->tag('span', __($product['price_update']['days_with_difference']), ['style' => 'font-size:17px;']);
									                            				}
									                            			?>
									                            		</td>
									                            		<td class="text-center">
								                                			<?php 
							                                					if($product['price_update']['30_days_alerts'] > 0){
							                                						
							                                						if($product['price_update']['30_days_alerts'] == 1){
							                                							
							                                							$alert_text = __('alert');
							                                						}
							                                						else{
							                                							$alert_text = __('alerts');
							                                						}

							                                						echo $this->Html->tag('h5', $this->Html->tag('i', '', ['class' => 'fa fa-exclamation-triangle text-warning']).' '.$product['price_update']['30_days_alerts'].' '.$alert_text, ['escape' => false]);
							                                					}
							                                					else{
							                                						echo $this->Html->tag('h5', $this->Html->tag('i', '', ['class' => 'fa fa-thumbs-up text-success']).' '.__('No alerts'), ['escape' => false]);
							                                					}
								                                			?>
								                                		</td>
									                            	<?php endif;?>

																	<td> 
								                            			<?php echo $this->Html->tag('h4', $this->Number->currency($detection['label_price'], 'CLP', ['precision' => 2])); ?>
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
															<?php endif;?>
													<?php endforeach;?>
												<?php endforeach;?>
											</tbody>
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
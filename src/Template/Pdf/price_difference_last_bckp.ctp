<div class="row">
	<div class="col-md-12">
		<table class="table">
            <thead>
                <tr>
                    <th class="text-left" style="border: 0px;">
                    	<?php echo $this->Html->image('onlyletters2.png', ['style' => 'width: 190px;', 'fullBase' => true]); ?>
                    </th>
                    <th class="text-right" style="border: 0px;">
                    	<?php echo $this->Html->image('companies/'.$store_data->company->company_logo, ['style' => 'width:90px;', 'fullBase' => true]); ?>
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
                    	<?php echo __('[{0}] {1} {2} - {3}',[$store_data->store_code, $store_data->company->company_name, $store_data->store_name, $session_date->format('d-m-Y H:i:s')]); ?>
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
		        <?php echo $this->Html->tag('h5', __('Products with difference pricing'));?>
		    </div>
			<div class="panel-body">
				<div class="row">
    				<div class="col-md-12">
	    				
	    				<?php foreach($data['products'] as $section_id => $collection):?>
							<div class="card">
								<div class="card-head card-head-xs style-default-dark">
									<header>
										<?php echo $this->Html->tag('h4', __('{0} Section ({1} products)', [$collection['section']['section_name'], count($collection['data'])]));?>
									</header>
								</div>
								<div class="card-body">
									
									<table class="table table-hover table-condensed nowrap" cellspacing="0" width="100%" style="font-size: 12px;margin-bottom: 0px;">
										
										<?php foreach($collection['data'] as $internal_code => $product):?>
											<thead style="border-top: 1px solid #8e8c8c; border: 1px solid #8e8c8c; border-bottom: none;">
												<tr>
						                        	<?php
						                        		$first_column = '';
						                        		$second_column = '';

						                        		switch ($store_data->company->company_keyword) {
						            						case 'lider':
						            							$first_column = __('Int. code');
						            							$second_column = __('EAN');                                				
						            							break;
						            						
						            						default:
						            							$first_column = __('EAN');
						            							$second_column = __('Int. code');
						            							break;
						            					}
						                        	?>
						                        	<th><?php echo $first_column;?></th>
						                            <th><?php echo $second_column;?></th>
						                            <th><?php echo __('Product description');?></th>
						                            <!--<th><?php //echo __('Detected price');?></th>-->
						                            <th><?php echo __('Master price');?></th>
						                            <!--<th><?php //echo __('Aisle');?></th>-->
						                            <th><?php echo __('Last price change');?></th>
						                            <th><?php echo __('Days with difference');?></th>
						                        </tr>
											</thead>
											<tbody style="border-left: 1px solid #8e8c8c;border-right: 1px solid #8e8c8c;">
												<tr class="gradeA">
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
					                    							if($product['ean13'] != ''){
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
													<!--<td>
					                        			<?php 
					                        				//echo $this->Number->currency($product[]['price'], 'CLP', ['precision' => 2]);
					                        			?>
					                        		</td>-->
					                        		<td>
					                        			<?php 
					                        				echo $this->Html->tag('h4', $this->Number->currency($product['price_update']['price'], 'CLP', ['precision' => 2]), ['style' => 'margin-top: 0px;margin-bottom: 0px;']);
					                        			?>
					                        		</td>
					                        		<!--<td><?php //echo $product['aisle']; ?></td>-->
					                        		<td><?php echo $product['price_update']['company_updated']->format('d-m-Y H:i:s'); ?></td>
					                        		<td><?php echo __($product['price_update']['days_with_difference']); ?></td>
												</tr>
											</tbody>
											
											<?php if(count($product['detections']) > 0):?>	
												<thead style="border-left: 1px solid #8e8c8c;border-right: 1px solid #8e8c8c;">
													<tr>
					                                    <th ><?php echo __('Lineal meter');?></th>
					                                    <th colspan="2"><?php echo __('Height tray');?></th>
					                                    <th colspan="2"><?php echo __('Detected price');?></th>
					                                    <th><?php echo __('Aisle');?></th>
					                                    
													</tr>
												</thead>
												<tbody style="border-left: 1px solid #8e8c8c;border-right: 1px solid #8e8c8c;">
													<?php foreach($product['detections'] as $detection):?>
														
														<tr class="info">
					                                		<td>
					                                			<?php echo $this->Number->precision($detection['location_x'], 2).' mts'; 
					                                			?>
					                                		</td>
					                                		<td colspan="2">
					                                			<?php echo $this->Number->precision($detection['location_z'], 2).' mts'; 
					                                			?>
					                                		</td>
					                                		<td colspan="2">
					                                			<?php echo $this->Number->currency($detection['label_price'], 'CLP', ['precision' => 2]); ?>
					                                		</td>
					                                		<td><?php echo $detection['aisle']; ?></td>
														</tr>
													<?php endforeach;?>
												</tbody>
											<?php endif;?>

											<!---<tr><td colspan=6 style="border: 1px solid #8e8c8c;border-top: none;">&nbsp;</td></tr>-->
											<tr><td colspan=6 style="border: none;border-bottom: 1px solid #8e8c8c;">&nbsp;</td></tr>
										<?php endforeach;?>
									</table>
								</div>
							</div>

						<?php endforeach;?>
    				</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->layout = null;?>

<?php if(isset($data['products']) && count($data['products']) > 0):?>

	<?php echo $this->Html->css([
		'theme-zippedi/libs/morris/morris.core.css?1420463396',
		'theme-zippedi/libs/rickshaw/rickshaw.css?1422792967'
	]);?>

	<style>
	    .active-zippedi{
	    	color: #ffffff;
    		background-color: #929292;
	    }
	    .pointer {
	    	cursor: pointer;
	    }
	</style>

	<section style="margin-top: -35px;">
		<div class="section-header">
			<ol class="breadcrumb">
				<li>
					<?php echo $this->Html->link(__('Reports'), '/robot-reports');?>
				</li>
				<li class="active" style="font-size: 16px;">
					<?php echo __('Stock Alert Report');?>
				</li>
			</ol>
			<?php echo $this->Html->image('onlyletters2.png', ['style' => 'width: 115px;margin: -40px 0px;', 'class' => 'pull-right hidden-xs']);?>
		</div>
		<div class="section-body">
			<div class="card">

				<div class="card-head style-default-light">
					<div class="tools pull-left" style="margin-left: 12px;">
						<?php echo $this->Html->image('companies/'.$store_data->company->company_logo, ['style' => 'width:40px;', 'fullBase' => true]).' '.$this->Html->tag('strong', __('Report overview: [{0}] {1} - {2}',[$store_data->store_code, $store_data->store_name, $robot_session->session_date->format('d-m-Y H:i:s')]), ['style' => 'font-size: 14px;margin-left: 15px;']); ?>
					</div>
					<div class="btn-group pull-right" role="group" style="padding: 15px;">
						
					</div>
				</div>

				<div class="card-body">
					<div class="row">
						<!-- Products -->
						<div class="col-sm-12">
							<div class="row">
								<?php if($robot_session->total_detections != null):?>
									<div class="col-sm-6">
										<!--<div class="row">
											<div class="col-sm-12">
											</div>

											<div class="col-sm-12">
											</div>
										</div>-->
										<div id="donut-detections-stock" class="height-6" data-colors="#0fbb06,#d5cc0e"></div>

										<!--<div id="morris-area-graph" class="height-5" data-colors="#9C27B0,#0aa89e"></div>-->
									</div>
									<div class="col-sm-6">
										<table class="table table-condensed text-center">
				                            <tr>
				                                <td style="border: 0px !important;">
				                                    <?php echo $this->Html->tag('h5', __('Total read strips'));?>
				                                    <?php echo $this->Html->tag('h2', $robot_session->total_detections);?>
				                                </td>
				                                <td style="border: 0px !important;">
				                                    <?php echo $this->Html->tag('h5', __('Detections with stock alert'));?>
				                                    <?php echo $this->Html->tag('h2', $data['stats']['total_detections']);?>
				                                </td>
				                            </tr>
				                            <tr>
				                                <td>
				                                    <?php echo $this->Html->tag('h5', __('Products with stock alert'));?>
				                                    <?php echo $this->Html->tag('h2', $data['stats']['total_products_in_alerts']);?>
				                                </td>
				                                <td>
				                                    <?php echo $this->Html->tag('h5', __('Products with more than 1 alert in the last 30 days'));?>
				                                    <?php echo $this->Html->tag('h2', $data['stats']['30_days_alert_detections']);?>
				                                </td>
				                            </tr>
				                        </table>
									</div>
								<?php else:?>

									<div class="col-sm-12">
										<table class="table table-condensed text-center">
				                            <tr>
				                                <td colspan="2" style="border: 0px !important;">
				                                	<?php echo $this->Html->tag('h5', __('Products with stock alert'));?>
				                                    <?php echo $this->Html->tag('h2', $data['stats']['total_products_in_alerts']);?>
				                                </td>
				                            </tr>
				                            <tr>
				                                <td>
				                                    <?php echo $this->Html->tag('h5', __('Detections with stock alert'));?>
				                                    <?php echo $this->Html->tag('h2', $data['stats']['total_detections']);?>
				                                </td>
				                                <td>
				                                    <?php echo $this->Html->tag('h5', __('Products with more than 1 alert in the last 30 days'));?>
				                                    <?php echo $this->Html->tag('h2', $data['stats']['30_days_alert_detections']);?>
				                                </td>
				                            </tr>
				                        </table>
									</div>

								<?php endif;?>

							</div>
						</div>
						
							
					</div>
				</div>
			</div>

			<div class="card">

				<div class="card-head style-default-light">
					<div class="tools pull-left" style="margin-left: 12px;">
						<?php echo $this->Html->image('companies/'.$store_data->company->company_logo, ['style' => 'width:40px;', 'fullBase' => true]).' '.$this->Html->tag('strong', __('Report details'), ['style' => 'font-size: 14px;margin-left: 15px;']); ?>
					</div>
					<div class="btn-group pull-right" role="group" style="padding: 15px;">
						<?php 
							/*echo $this->Form->postLink(
								$this->Html->tag('i', '', ['class' => 'fa fa-file-excel-o']),
                                '/excels/priceDifferenceReport',
								[
                                    'data' => [
                                        'company_id' =>$store_data->company->id,
                                        'store_id' => $store_data->id, 
                                        'session_id' => $session_code, 
                                    ],
									'escape' => false,
									'class' => 'btn btn-success',
									'data-toggle' => 'tooltip', 
									'title' => __('Download Excel'), 
									'data-placement' => 'bottom'
								]
							);*/
							echo $this->Html->link(
								$this->Html->tag('i', '', ['class' => 'fa fa-file-excel-o']),
								'/reports/stockAlert/download/xlsx/'.$robot_session->id,
								[
									'escape' => false,
									'class' => 'btn btn-success',
									//'target' => '_blank',
									'data-toggle' => 'tooltip', 
									'title' => __('Download Excel'), 
									'data-placement' => 'bottom'
								]
							);
						?>
						<?php 
							//if(file_exists(ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'pdfs' .  DIRECTORY_SEPARATOR . 'price_difference_'.$store_data->company->company_keyword.'_'.$store_data->store_code.'_'.$session_code.'.pdf')){

								echo $this->Html->link(
									$this->Html->tag('i', '', ['class' => 'fa fa-file-pdf-o']),
									'/reports/stockAlert/download/pdf/'.$robot_session->id,
									[
										'escape' => false,
										'class' => 'btn btn-danger',
										'target' => '_blank',
										'data-toggle' => 'tooltip', 
										'title' => __('Download PDF'), 
										'data-placement' => 'bottom',
									]
								);
							//}
						?>
					</div>
				</div>

				<div class="card-body">
					<div class="row">

						<!-- BEGIN SEARCH NAV -->
						<div class="col-sm-4 col-md-3 col-lg-2 hidden-xs">
							<ul class="nav nav-pills nav-stacked" style="font-size: 11px;" id="filters-ul">
								<li>
									<small><?php echo __('Sections');?></small>
								</li>
								<li id="filter-section-all" class="active-zippedi list-class">
									<?php echo $this->Html->link(__('All').' '.$this->Html->tag('small', $data['stats']['total_products_in_alerts'], ['class' => 'pull-right text-bold opacity-75']), 'javascript:void(0);', ['escape' => false, 'onclick' => "javascript:doFilter('all')"]);?>
								</li>

								<?php foreach($data['products'] as $section_id => $collection):?>
									<li id="filter-section-<?php echo $section_id;?>" class="list-class">

										<?php echo $this->Html->link($collection['section']['section_name'].' '.$this->Html->tag('small', $collection['section']['count_products'], ['class' => 'pull-right text-bold opacity-75']), 'javascript:void(0);', ['escape' => false, 'onclick' => 'javascript:doFilter('.$section_id.');', 'data-toggle' => 'tooltip', 'title' => __('{0} products in {1} labels', [$collection['section']['count_products'], $collection['section']['count_labels']]), 'data-placement' => 'right']);?>
									</li>
								<?php endforeach;?>
							</ul>
						</div><!--end .col -->
						<!-- END SEARCH NAV -->

						<div class="col-sm-8 col-md-9 col-lg-10">

							<?php
								echo $this->Form->hidden('storecode', ['value' => $store_data->store_code, 'id' => 'storecode']);
								echo $this->Form->hidden('sessionid', ['value' => $session_code, 'id' => 'sessionid']);

								$first_column = '';
								$second_column = '';

								switch ($store_data->company->company_keyword) {
									case 'jumbo':
										$first_column = __('EAN');
										$second_column = __('Material code');                                				
										break;

									case 'lider':
										$first_column = __('Item');
										$second_column = __('EAN');                                				
										break;

									case 'homecenter':
										$first_column = __('EAN');
										$second_column = __('SKU');                                				
										break;
									
									default:
										$first_column = __('EAN');
										$second_column = __('Int. Code');
										break;
								}
							?>
							<?php foreach($data['products'] as $section_id => $collection):?>
								<div class="results-class" id="section-block-<?php echo $section_id;?>" style="padding: 20px 30px;">
									<div class="margin-bottom-xxl text-right">
										<span class="text-light text-lg">
											<h3>
												<?php echo $collection['section']['section_name'];?>
											</h3>
											<?php echo '('.$collection['section']['count_products'].' '.__('products').')';?> 
										</span>
									</div>
									<div class="card">
										<div class="card-head">
											<ul class="nav nav-tabs nav-justified" data-toggle="tabs" id="tabs-<?php echo $section_id?>">
												<li class="active">
													<a href="#list-<?php echo $section_id;?>" data-toggle="tab" id="list" data-text="<?php echo __('list');?>" onclick="javascript:doFilterTab(<?php echo $section_id;?>);">
													<?php echo __('List');?>
													</a>
												</li>
												<li>
													<a href="#grid-<?php echo $section_id;?>" data-toggle="tab" id="grid" data-text="<?php echo __('grid');?>" onclick="javascript:doFilterTab(<?php echo $section_id;?>);">
														<?php echo __('Grid');?>
													</a>
												</li>
											</ul>
										</div><!--end .card-head -->
										<div class="card-body tab-content">
											<div class="tab-pane active" id="list-<?php echo $section_id;?>">

												<?php foreach($collection['data'] as $aisle => $info):?>
													
													<?php echo $this->Html->tag('h4', __('Aisle: {0} ({1} products)', [$aisle, count($info)]), ['style' => 'margin-left: 10px;margin-top: 10px;', 'class' => 'text-right']);?>

													<div class="table-responsive">
														


														<table id="datatable-<?php echo $section_id;?>" class="table table-bordered table-condensed nowrap" cellspacing="0" width="100%" style="font-size: 12px;margin-bottom: 0px;">

															<thead>
																<tr>
													            	
													            	<th><?php echo $first_column;?></th>
								                                    <th><?php echo $second_column;?></th>
								                                    <th><?php echo __('Product description');?></th>
								                                    <th><?php echo __('Stock In Room');?></th>
								                                    <th><?php echo __('Stock In Warehouse');?></th>
								                                    <th><?php echo __('Stock In Transit');?></th>
								                                    <th><?php echo __('Alerts last 30 days');?></th>
								                                    <!--<th><?php //echo __('Detected price');?></th>-->
													                <th><?php echo __('Lineal meter');?></th>
													                <th><?php echo __('Height tray');?></th>
													                <th><?php echo __('Actions');?></th>
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
											                            		<td rowspan="<?php echo count($product['detections']);?>">
										                                			<?php 
										                                				if(isset($product['stock_update']) && $product['stock_update']['stock'] === null){

										                                					echo $this->Html->tag('small', __('No data'), ['style' => 'margin-top: 0px;margin-bottom: 0px;']);

										                                				}
										                                				else{
										                                					echo $this->Html->tag('h4', $product['stock_update']['stock'], ['escape' => false, 'class' => 'pointer', 'data-toggle' => 'tooltip', 'data-placement' => 'left', 'title' => __('Updated on {0} (Last stock: {1})', [($product['stock_update']['company_updated'] != null) ? $product['stock_update']['company_updated']->format('d-m-Y H:i') : __('No data'), $product['stock_update']['last_stock']])]);
										                                				}
										                                			?>
										                                		</td>
										                                		<td rowspan="<?php echo count($product['detections']);?>">
										                                			<?php 
										                                				if(isset($product['stock_update']) && $product['stock_update']['stock_warehouse'] === null){
										                                					echo $this->Html->tag('small', __('No data'), ['style' => 'margin-top: 0px;margin-bottom: 0px;']);
										                                				}
										                                				else{
										                                					echo $this->Html->tag('small', $product['stock_update']['stock_warehouse'], ['style' => 'margin-top: 0px;margin-bottom: 0px;']);
										                                				}
										                                			?>
										                                		</td>
										                                		<td rowspan="<?php echo count($product['detections']);?>">
										                                			<?php 
										                                				if(isset($product['stock_update']) && $product['stock_update']['stock_in_transit'] === null){
										                                					echo $this->Html->tag('small', __('No data'), ['style' => 'margin-top: 0px;margin-bottom: 0px;']);
										                                				}
										                                				else{
										                                					echo $this->Html->tag('small', $product['stock_update']['stock_in_transit'], ['style' => 'margin-top: 0px;margin-bottom: 0px;']);
										                                				}
										                                			?>
										                                		</td>
										                                		<td rowspan="<?php echo count($product['detections']);?>">
										                                			<?php 
										                                				if(isset($product['stock_update'])){

										                                					if($product['stock_update']['30_days_alerts'] > 0){
										                                						
										                                						if($product['stock_update']['30_days_alerts'] == 1){
										                                							
										                                							$alert_text = __('alert');
										                                						}
										                                						else{
										                                							$alert_text = __('alerts');
										                                						}

										                                						echo $this->Html->tag('h4', $this->Html->tag('i', '', ['class' => 'fa fa-exclamation-triangle text-warning']).' '.$product['stock_update']['30_days_alerts'].' '.$alert_text, ['escape' => false]);
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
																				<!--<td> 
											                            			<?php //echo $this->Html->tag('h4', $this->Number->currency($detection['label_price'], 'CLP', ['precision' => 2])); ?>
											                            		</td>-->
											                            		<td>
											                            			<?php echo $this->Number->precision($detection['location_x'], 2).' mts'; 
											                            			?>
											                            		</td>
											                            		<td>
											                            			<?php echo $this->Number->precision($detection['location_z'], 2).' mts'; 
											                            			?>
											                            		</td>
											                            		<td>
																					<?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-search']), 'javascript:void(0);', ['data-toggle' => 'modal', 'data-target' => '#details-modal', 'data-detectionid' => $detection['detection_code'], 'escape' => false, 'class' => 'btn btn-sm btn-block btn-warning']); ?>
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
												                            		<td>
											                                			<?php 
											                                				if(isset($product['stock_update']) && $product['stock_update']['stock'] === null){

											                                					echo $this->Html->tag('small', __('No data'), ['style' => 'margin-top: 0px;margin-bottom: 0px;']);

											                                				}
											                                				else{
											                                					echo $this->Html->tag('h4', $product['stock_update']['stock'], ['escape' => false, 'class' => 'pointer', 'data-toggle' => 'tooltip', 'data-placement' => 'left', 'title' => __('Updated on {0} (Last stock: {1})', [($product['stock_update']['company_updated'] != null) ? $product['stock_update']['company_updated']->format('d-m-Y H:i') : __('No data'), $product['stock_update']['last_stock']])]);
											                                				}
											                                			?>
											                                		</td>
											                                		<td>
											                                			<?php 
											                                				if(isset($product['stock_update']) && $product['stock_update']['stock_warehouse'] === null){
											                                					echo $this->Html->tag('small', __('No data'), ['style' => 'margin-top: 0px;margin-bottom: 0px;']);
											                                				}
											                                				else{
											                                					echo $this->Html->tag('small', $product['stock_update']['stock_warehouse'], ['style' => 'margin-top: 0px;margin-bottom: 0px;']);
											                                				}
											                                			?>
											                                		</td>
											                                		<td>
											                                			<?php 
											                                				if(isset($product['stock_update']) && $product['stock_update']['stock_in_transit'] === null){
											                                					echo $this->Html->tag('small', __('No data'), ['style' => 'margin-top: 0px;margin-bottom: 0px;']);
											                                				}
											                                				else{
											                                					echo $this->Html->tag('small', $product['stock_update']['stock_in_transit'], ['style' => 'margin-top: 0px;margin-bottom: 0px;']);
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

											                                						echo $this->Html->tag('h4', $this->Html->tag('i', '', ['class' => 'fa fa-exclamation-triangle text-warning']).' '.$product['stock_update']['30_days_alerts'].' '.$alert_text, ['escape' => false]);
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
												                            	<?php endif;?>

																				<!--<td> 
											                            			<?php //echo $this->Html->tag('h4', $this->Number->currency($detection['label_price'], 'CLP', ['precision' => 2])); ?>
											                            		</td>-->
											                            		<td>
											                            			<?php echo $this->Number->precision($detection['location_x'], 2).' mts'; 
											                            			?>
											                            		</td>
											                            		<td>
											                            			<?php echo $this->Number->precision($detection['location_z'], 2).' mts'; 
											                            			?>
											                            		</td>
											                            		<td>
																					<?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-search']), 'javascript:void(0);', ['data-toggle' => 'modal', 'data-target' => '#details-modal', 'data-detectionid' => $detection['detection_code'], 'escape' => false, 'class' => 'btn btn-sm btn-block btn-warning']); ?>
										                                		</td>
																			</tr>
																		<?php endif;?>


																	<?php endforeach;?>
																<?php endforeach;?>
															</tbody>
														</table>
													</div>
												<?php endforeach;?>
											</div>
											<div class="tab-pane" id="grid-<?php echo $section_id;?>">
												<?php foreach($collection['data'] as $aisle => $info):?>
													<div class="list-results">
												
														<?php echo $this->Html->tag('h3', __('Aisle: {0} ({1} products)', [$aisle, count($info)]), ['style' => 'margin-left: 10px;margin-top: 10px;']);?>
														
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
																<div class="row">
																	<div class="col-sm-6">
																		<div class="row">
																			<div class="col-sm-3 text-left pointer" data-toggle="tooltip" title="<?php echo $first_column;?>" data-placement="right" style="margin-top: 7px;margin-left: 10px;">
																				<?php
								                                					if($barcode_html != ''){
								                                                       echo '<img class="text-left" style="" src="data:image/png;base64,'.base64_encode($barcode_html).'"><br>';
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

								                                							<div class="col-md-6 opacity-75 pointer" data-toggle="tooltip" title="<?php echo __('Updated on {0} (Last stock: {1})', [($product['stock_update']['company_updated'] != null) ? $product['stock_update']['company_updated']->format('d-m-Y H:i') : __('No data'), $product['stock_update']['last_stock']]);?>" data-placement="bottom">
																								<span class="md md-trending-up text-sm text-success"></span> &nbsp;
																								<?php echo $this->Html->tag('strong', $product['stock_update']['stock']);?>
																							</div>
								                                						<?php else:?>
								                                							<div class="col-md-6 opacity-75 pointer" data-toggle="tooltip" title="<?php echo __('Updated on {0} (Last stock: {1})', [($product['stock_update']['company_updated'] != null) ? $product['stock_update']['company_updated']->format('d-m-Y H:i') : __('No data'), $product['stock_update']['last_stock']]);?>" data-placement="right">
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
																	</div>
																	<div class="col-sm-6">
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

																					<div class="table-responsive">
																						<table class="table table-hover table-condensed nowrap" cellspacing="0" width="100%" style="font-size: 12px;margin-bottom: 0px;">
																							<thead>
																								<tr class="text-center">
																									<td colspan="5">
																										<?php echo __('Detections');?>
																                                    </td>
																								</tr>
																								<tr>
																									<td>
																                                    </td>
																                                    <td>
																                                    	<?php echo __('Aisle');?>
																                                    </td>
																                                    <!--<td>
																                                    	<?php //echo __('Detected price');?>
																                                    </td>-->
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
																									
																									<tr class="warning">
																                                		<td>
																											<?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-search']), 'javascript:void(0);', ['data-toggle' => 'modal', 'data-target' => '#details-modal', 'data-detectionid' => $detection['detection_code'], 'escape' => false, 'class' => 'btn btn-xs btn-warning']); ?>
																                                		</td>
																                                		<td> 
																                                			<?php echo $detection['aisle']; ?>
																                                		</td>
																                                		<!--<td> 
																                                			<?php //echo $this->Number->currency($detection['label_price'], 'CLP', ['precision' => 2]); ?>
																                                		</td>-->
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
																			</div>
																		<?php endif;?>
																	</div>
																</div>
															</div>
														<?php endforeach;?>
													</div>
												<?php endforeach;?>
											</div>
										</div><!--end .card-body -->
									</div><!--end .card -->
								</div>
							<?php endforeach;?>
						</div><!--end .col -->
					</div><!--end .row -->
				</div><!--end .card-body -->
				<!-- END SEARCH RESULTS -->

			</div><!--end .card -->
		</div><!--end .section-body -->
	</section>

    <div class="modal fade" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel"><?php echo __('Photos');?></h4>
				</div>
				<div class="modal-body">

					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-default">
		                        <div class="panel-heading">
		                            <?php echo __('Facing Image');?>
		                        </div>
		                        <div class="panel-body text-center" id="facing-div">
									<?php echo $this->Html->image('ajax-loader.gif', ['style' => 'width:90px;', 'class' => 'loader', 'id' => 'loading-facing']);?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close');?></button>
				</div>
			</div>
		</div>
	</div>

	<?php echo $this->Html->script([
		'theme-zippedi/libs/raphael/raphael-min.js',
		'theme-zippedi/libs/morris.js/morris.min.js',
	]);?>

   	<script>
	    $(document).ready(function(){

	    	<?php if($robot_session->total_detections != null):?>
		    	Morris.Donut({
					element: 'donut-detections-stock',
					data: [
						{label: "<?php echo __('Total read strips');?>", value: <?php echo $robot_session->total_detections;?>},
						{label: "<?php echo __('Read strips with stock alert');?>", value: <?php echo $data['stats']['total_detections'];?>}
					],
					colors: $('#donut-detections-stock').data('colors').split(',')
				});

		    	var labelColor = $('#morris-area-graph').css('color');
		    <?php endif;?>

			/*var labelColor = $('#morris-area-graph').css('color');
				Morris.Area({
				element: 'morris-area-graph',
				behaveLikeLine: true,
				data: [
					{x: '2011 Q1', y: 3, z: 3},
					{x: '2011 Q2', y: 2, z: 1},
					{x: '2011 Q3', y: 2, z: 4},
					{x: '2011 Q4', y: 3, z: 3}
				],
				xkey: 'x',
				ykeys: ['y', 'z'],
				labels: ['Y', 'Z'],
				gridTextColor: labelColor,
				lineColors: $('#morris-area-graph').data('colors').split(',')
			});*/

	    	$('[data-toggle="tooltip"]').tooltip();
			$('#details-modal').on('show.bs.modal', function (event) {

	            var modal = $(this);


            	var button = $(event.relatedTarget);
            	var detection_id = button.data('detectionid');
	            var store_code = $('#storecode').val();
	            var session_id = $('#sessionid').val();

            	$.ajax({
	                url: webroot + 'robotReports/getFacingCrop/'+store_code+'/'+session_id+'/'+detection_id+'/image_tag',
	                cache: false,
	                type: 'GET',
	                dataType: 'json',
	                success: function (response) 
	                {
	                    console.log(response);
	                    modal.find('.modal-body #facing-div').hide();
	                    modal.find('.modal-body #facing-div').html(response.data.image_html_label);
	                    modal.find('.modal-body #facing-div').fadeIn(400);
	                }
	            });
	        });

	        $('#details-modal').on('hidden.bs.modal', function (e) {
	        	var modal = $(this);
	        	modal.find('.modal-body #facing-div').html(<?php echo "'".$this->Html->image('ajax-loader.gif', ['style' => 'width:90px;'])."'";?>);
			});
	    });

	    function doFilter(section_id){
	    	
	    	if(section_id != ''){
	    		if(section_id == 'all'){
		    		$('.list-class').removeClass('active-zippedi');
			    	$('#filter-section-'+section_id).addClass('active-zippedi');
			    	$('.results-class').show();

					if(type != ''){
			    		$('#pdf-download-buttom').attr("href", webroot + "reports/stockAlert/download/list/<?php echo $robot_session->id;?>/");
			    		$('#pdf-download-buttom').attr('data-original-title', '<?php echo __("Download PDF");?> <?php echo __('list');?>');
					}

			    	
		    	}
		    	else{
		    		$('.list-class').removeClass('active-zippedi');
			    	$('#filter-section-'+section_id).addClass('active-zippedi');
			    	$('.results-class').hide();
			    	$('#section-block-'+section_id).show();

			    	var listItems = $("#tabs-"+section_id+" li");
			    	var type = '';
			    	var type_text = '';
					listItems.each(function(idx, li) {
					    var tab = $(li);
					    if(tab.hasClass('active')){
					    	var link = tab.find('a');
					    	type = link.attr('id');
					    	type_text = link.attr('data-text');
					    }
					});

					var texts = $('#section-block-'+section_id+" strong:first" ).text();

					if(type != ''){
						$('#pdf-download-buttom').attr("href", webroot + "reports/stockAlert/download/"+type+"/<?php echo $robot_session->id;?>/"+section_id);
			    		$('#pdf-download-buttom').attr('data-original-title', '<?php echo __("Download PDF");?> '+texts+' '+type_text);
					}
		    	}
	    	}
	    }

	    function doFilterTab(section_id){
	    	
	    	if(section_id != ''){

	    		var listFilters = $("#filters-ul li");
	    		var filter_id = '';
	    		var is_global = false;
	    		listFilters.each(function(idx, li) {
				    var tab = $(li);
				    if(tab.hasClass('active-zippedi')){
				    	if(tab.attr('id') == 'filter-section-all'){
				    		is_global = true;
				    	}
				    }
				});

	    		if(is_global == true){
	    			return false;
	    		}
	    		else{
	    			$('.list-class').removeClass('active-zippedi');
			    	$('#filter-section-'+section_id).addClass('active-zippedi');
			    	$('.results-class').hide();
			    	$('#section-block-'+section_id).show();

			    	var listItems = $("#tabs-"+section_id+" li");
			    	var type = '';
			    	var type_text = '';
					listItems.each(function(idx, li) {
					    var tab = $(li);
					    if(tab.hasClass('active')){
					    	var link = tab.find('a');
					    	type = link.attr('id');
					    	type_text = link.attr('data-text');
					    }
					});

					var texts = $('#section-block-'+section_id+" strong:first" ).text();

					if(type == 'list'){
						type = 'grid';
						type_text = '<?php echo __("grid");?>';
					}
					else{
						type = 'list';
						type_text = '<?php echo __("list")?>';
					}

					if(type != ''){
						$('#pdf-download-buttom').attr("href", webroot + "reports/stockAlert/download/"+type+"/<?php echo $robot_session->id;?>/"+section_id);
			    		$('#pdf-download-buttom').attr('data-original-title', '<?php echo __("Download PDF");?> '+texts+' '+type_text);
					}
	    		}
	    	}
	    }

		function drawBasic() {

			var data = new google.visualization.DataTable();
			data.addColumn('number', 'X');
			data.addColumn('number', 'Products');

			data.addRows([
				[0, 0],   [1, 10],  [2, 23],  [3, 17],  [4, 18],  [5, 9],
				[6, 11],  [7, 27],  [8, 33],  [9, 40],  [10, 32], [11, 35],
				[12, 30], [13, 40], [14, 42], [15, 47], [16, 44], [17, 48],
				[18, 52], [19, 54], [20, 42], [21, 55], [22, 56], [23, 57],
				[24, 60], [25, 50], [26, 52], [27, 51], [28, 49], [29, 53],
				[30, 55], [31, 60], [32, 61], [33, 59], [34, 62], [35, 65],
				[36, 62], [37, 58], [38, 55], [39, 61], [40, 64], [41, 65],
				[42, 63], [43, 66], [44, 67], [45, 69], [46, 69], [47, 70],
				[48, 72], [49, 68], [50, 66], [51, 65], [52, 67], [53, 70],
				[54, 71], [55, 72], [56, 73], [57, 75], [58, 70], [59, 68],
				[60, 64], [61, 60], [62, 65], [63, 67], [64, 68], [65, 69],
				[66, 70], [67, 72], [68, 75], [69, 80]
			]);

			var options = {
				hAxis: {
				  title: 'Date'
				},
				vAxis: {
				  title: 'Quantity'
				}
			};

			var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

			chart.draw(data, options);
	    }

	    function alert_on(){
			$('.timeAlert').fadeIn(900).delay(600).fadeOut(900, alert_on);
		}

		function alert_off(){
			$('.timeAlert').stop(true);
			$('.timeAlert').css({'display':'block','opacity':1});
		}


	</script>

<?php else:?>

	<div class="row">
		<div class="col-md-12">
			<div class="alert alert-warning">
				<?php echo __('No data for "{0}" session to generate the report', $session_code);?>
			</div>
		</div>
	</div>

<?php endif;?>
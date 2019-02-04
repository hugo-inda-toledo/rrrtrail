<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default content">
		    <div class="panel-body">
		        <div class="row">
		            <div class="col-md-12 text-center">
		               	<div class="btn-group" role="group">
							<?php 
								/*echo $this->Form->postLink(
									$this->Html->tag('i', '', ['class' => 'fa fa-file-excel-o']).' '.__('Download Excel'),
                                    '/excels/priceDifferenceReport',
									[
                                        'data' => [
                                            'company_id' =>$store_data->company->id,
                                            'store_id' => $store_data->id, 
                                            'session_id' => $session_code, 
                                        ],
										'escape' => false,
										'class' => 'btn btn-success'
									]
								);*/
							?>
							<?php 
								if(file_exists(ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'pdfs' .  DIRECTORY_SEPARATOR . 'stock_out_'.$store_data->company_id.$store_data->id.$session_code.'.pdf')){

									echo $this->Html->link(
									$this->Html->tag('i', '', ['class' => 'fa fa-file-pdf-o']).' '.__('Download PDF'),
										'/files' . DIRECTORY_SEPARATOR . 'pdfs' .  DIRECTORY_SEPARATOR . 'stock_out_'.$store_data->company_id.$store_data->id.$session_code.'.pdf',
										[
											'escape' => false,
											'class' => 'btn btn-danger',
											'download' => 'stock_out_'.$store_data->company_id.$store_data->id.$session_code.'.pdf'
										]
									);
								}
								
							?>
						</div>
		            </div>
		        </div>                
		    </div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 text-right">
		<?php echo $this->Html->div('label label-default', __('Last Update: {0}', date('d-m-Y H:i:s')), ['style' => 'font-size: 15px;']);?>
	</div>
</div>

<br>

<div class="row hidden-xs hidden-sm">
	<div class="col-md-6 text-left">
		<?php echo $this->Html->image('onlyletters2.png', ['style' => 'width: 130px;']); ?>
	</div>
	<div class="col-md-6 text-right">
		<?php echo $this->Html->image('companies/'.$store_data->company->company_logo, ['style' => 'width: 90px;']); ?>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center" style="background-color: #5c5c5c;">
                        	<?php echo $this->Html->tag('h4', __('Stock Alert Report'), ['style' => 'color:#FFF;']); ?>
                        </th>
                    </tr>
                    <tr>
                        <th class="text-center" style="background-color: #dedede; color: #545454;">
                        	<?php echo $session_date->format('d-m-Y H:i:s'); ?>
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!--<div class="row">
	<div class="col-md-12">
		<div id="chart_div"></div>
    </div>
</div>-->

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-danger content">
		    <div class="panel-heading">
		        <?php echo $this->Html->tag('h5', __('Products with stock out alert'));?>
		    </div>
			<div class="panel-body">
				<div class="row">
    				<?php
						echo $this->Form->hidden('storecode', ['value' => $store_data->store_code, 'id' => 'storecode']);
						echo $this->Form->hidden('sessionid', ['value' => $session_code, 'id' => 'sessionid']);
					?>
    				<div class="col-md-12">
	    				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">	
                        	<?php $y=1;?>
                        	<?php foreach($stock_outs as $stock_out):?>
                        		<?php if(count($stock_out['data']) > 0):?>
	                                <div class="panel panel-default">    
	                                    <div class="panel-heading" role="tab" id="heading-<?php echo $y?>">
	                                        <h4 class="panel-title">
	                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#section-<?php echo $y?>" aria-expanded="true" aria-controls="section-<?php echo $y?>" class="collapsed">
	                                            	<?php echo __('{0} Section ({1} products)', $stock_out['section']->section_name, count($stock_out['data']))?>
	                                            </a>
	                                        </h4>
	                                    </div>
	                                    <div id="section-<?php echo $y?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-<?php echo $y?>">
	                                        <div class="panel-body">
	                                            <?php if(count($stock_out['data']) > 0 && !isset($stock_out['data'][0]['message'])):?>
						    						<?php echo $this->Html->tag('h4', __('{0} Section ({1} products)', $stock_out['section']->section_name, count($stock_out['data']))); ?>
						    						<div class="table-responsive">
							    						<table class="table table-striped table-bordered table-hover">
								                            <thead>
								                                <tr>
								                                	<th>#</th>
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
								                                    <th><?php echo __('Aisle');?></th>
								                                    <th><?php echo __('Lineal meter');?></th>
								                                    <th><?php echo __('Height tray');?></th>
								                                    <th><?php echo __('Detected price');?></th>
								                                    <th><?php echo __('Stock');?></th>
								                                    <th><?php echo __('Status');?></th>
								                                </tr>
								                            </thead>
								                            <tbody>
								                            	<?php $x=1;?>
								                                <?php foreach($stock_out['data'] as $product):?>

								                                	<tr>
								                                		<td><?php echo $x; ?></td>
								                                		<td style="font-size: 13px;" class="text-left">
								                                			<?php
								                                				/*$barcode_html = '';
								                                				if($product['ean'] != null){*/

								                                					$barcode_html = '';
								                                					$barcode_code = __('No available');

								                                					switch ($store_data->company->company_keyword) {
								                                						case 'lider':
								                                							$barcode_code = $this->Walmart->codeFormat($store_data->company->company_keyword, $product['item']);
								                                							$barcode_html = $barcode->getBarcode($barcode_code, $barcode::TYPE_EAN_13, 1);
								                                							break;
								                                						
								                                						default:
								                                							if($product['ean'] != ''){
								                                								$barcode_code = $this->Ean->format($product['ean']);
								                                								$barcode_html = $barcode->getBarcode($barcode_code, $barcode::TYPE_EAN_13, 1);
								                                							}

								                                							
								                                							break;
								                                					}

									  
								                                					
									                                				//echo $barcode_html;

									                                				if($barcode_html != ''){
                                                                                       echo '<img style="" src="data:image/png;base64,'.base64_encode($barcode_html).'"><br>';
                                                                                    }

									                                				echo $this->Html->link($barcode_code, 'javascript:void(0);', ['style' => 'color: #000;', 'data-toggle' => 'modal', 'data-target' => '#details-modal-'.$product['ean'], 'data-storecode' => $store_data->store_code, 'data-sessionid' => $session_code, 'data-detectionid' => $product['detection_id'], 'data-type' => 'image_tag', 'data-companyinternalcode' => $product['item'], 'data-ean' => $this->Ean->format($product['ean']), 'data-productdescription' => $product['description'], 'data-aisle' => $product['aisle'], 'data-linealposition' => $this->Number->precision($product['location_x'], 2).' mts', 'data-heightposition' => $this->Number->precision($product['location_z'], 2).' mts', 'data-detectedprice' => $this->Number->currency($product['price'], 'CLP', ['precision' => 2]), 'data-barcodehtml' => $barcode_html]);
								                                				/*}
								                                				else{
								                                					echo __('No info');
								                                				}*/

								                                				
								                                			?>
								                                		</td>
								                                		<td>
								                                			<?php 
								                                				$second_code = '';
								                                				switch ($store_data->company->company_keyword) {
							                                						case 'lider':
							                                							$second_code = $this->Ean->format($product['ean']);
							                                							break;
							                                						
							                                						default:
							                                							$second_code = $product['item'];
							                                							break;
							                                					}


								                                				echo $second_code; 
								                                			?>
								                                		</td>
								                                		<td>
								                                			<?php 
								                                				echo $this->Html->tag('strong', $this->Html->link($product['description'], 'javascript:void(0);', ['data-toggle' => 'modal', 'data-target' => '#details-modal-'.$product['ean'], 'data-storecode' => $store_data->store_code, 'data-sessionid' => $session_code, 'data-detectionid' => $product['detection_id'], 'data-type' => 'image_tag', 'data-companyinternalcode' => $product['item'], 'data-ean' => $this->Ean->format($product['ean']), 'data-productdescription' => $product['description'], 'data-aisle' => $product['aisle'], 'data-linealposition' => $this->Number->precision($product['location_x'], 2).' mts', 'data-heightposition' => $this->Number->precision($product['location_z'], 2).' mts', 'data-detectedprice' => $this->Number->currency($product['price'], 'CLP', ['precision' => 2]), 'data-barcodehtml' => $barcode_html]));
								                                			?>
								                                		</td>
								                                		<td><?php echo $product['aisle']; ?></td>
								                                		<td>
								                                			<?php echo $this->Number->precision($product['location_x'], 2).' mts'; 
								                                			?>
								                                		</td>
								                                		<td>
								                                			<?php echo $this->Number->precision($product['location_z'], 2).' mts'; 
								                                			?>
								                                		</td>
								                                		<td>
								                                			<?php echo $this->Number->currency($product['price'], 'CLP', ['precision' => 2]); ?>
								                                		</td>

								                                		<?php if(!is_null($product['stock_quantity'])): ?>

	                                                                        <td>
	                                                                            <?php echo $product['stock_quantity'];?>
	                                                                        </td>

	                                                                    <?php else:?>

	                                                                        <td class="text-center">
	                                                                            <?php echo $this->Html->tag('span', __('No data'), ['class' => 'label label-default']);?>
	                                                                        </td>
	                                                                    <?php endif;?>


	                                                                    <?php if(is_null($product['enabled'])):?>
	                                                                    	
	                                                                    	<td class="text-center">
	                                                                            <?php echo $this->Html->tag('span', __('No data'), ['class' => 'label label-default']);?>
	                                                                        </td>

	                                                                    <?php else:?>

	                                                                    	<?php if($product['enabled'] == 0):?>

	                                                                    		<td class="text-center">
		                                                                            <?php echo $this->Html->tag('span', __('Blocked'), ['class' => 'label label-danger']);?>
		                                                                        </td>

	                                                                    	<?php else:?>

	                                                                    		<?php if($product['enabled'] == 1 && count($product_states) > 0 && $product['status'] == null): ?>

			                                                                    	<td class="text-center" id="status-td-<?php echo $product['product_store_id'];?>">
			                                                                    		<?php echo $this->Html->link(__('Set status'), 'javascript:void(0);', ['data-toggle' => 'modal', 'data-target' => '#details-modal-'.$product['ean'], 'class' => 'btn btn-sm btn-default']);?>
			                                                                    	</td>

			                                                                    <?php else:?>

			                                                                    	<td class="text-center">
			                                                                            <?php echo $this->Html->tag('span', __($product['status']), ['class' => 'label label-'.$product['status_class']]);?>
			                                                                        </td>

			                                                                    <?php endif;?>

	                                                                    	<?php endif;?>

		                                                                    

		                                                                <?php endif;?>
								                                	</tr>

								                                	<?php $x++;?>
								                                <?php endforeach;?>
								                            </tbody>
								                        </table>
							    					</div>
							    				<?php else:?>

							    					<?php if(isset($info['data'][0]['message'])):?>
							    						<?php echo $this->Html->tag('h4', __('{0} Section', $stock_out['section']->section_name)); ?>
														<div class="alert alert-warning">
															<?php echo __('No stock out for "{0}" section. API Message: {1}', [strtolower($stock_out['section']->section_name), $info['data'][0]['message']]);?>
														</div>
							    					<?php else:?>
							    						<?php echo $this->Html->tag('h4', __('{0} Section', $stock_out['section']->section_name)); ?>
														<div class="alert alert-warning">
															<?php echo __('No stock out for "{0}" section.', strtolower($stock_out['section']->section_name));?>
														</div>
							    					<?php endif;?>
							    					
							    				<?php endif;?>
	                                        </div>
	                                    </div>
	                                </div>
	                                <?php $y++;?>
	                            <?php endif;?>
                            <?php endforeach;?>
                        </div>
    				</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php if(count($stock_outs) > 0):?>
	<?php foreach($stock_outs as $stock_out):?>
		<?php if(count($stock_out['data']) > 0):?>
			<?php foreach($stock_out['data'] as $product):?>
				<div class="modal fade details-class" id="details-modal-<?php echo $product['ean'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					<a class="left carousel-control btn-prev" href="#carousel-example-generic" role="button" data-slide="prev">
				    	<span class="glyphicon glyphicon-chevron-left fa-2x" aria-hidden="true"></span>
				    	<span class="sr-only">Previous</span>
				  	</a>
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="myModalLabel"><?php echo __('Product analysis by Zippedi');?></h4>
							</div>
							<div class="modal-body">
								<?php echo $this->Form->hidden('detection_id', ['value' => $product['detection_id'], 'id' => 'detection_id']);?>

								<div class="row">
									<div class="col-sm-5">
										<div class="row">
											<div class="col-sm-12 text-center">
												<div class="panel panel-default">
							                        <div class="panel-heading">
							                            <?php echo __('Facing Image');?>
							                        </div>
							                        <div class="panel-body" id="facing-div">
														<?php echo $this->Html->image('ajax-loader.gif', ['style' => 'width:90px;', 'class' => 'loader', 'id' => 'loading-facing']);?>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-7">
										<?php if($product['enabled'] == 1 && count($product_states) > 0 && $product['status'] == null && $product['product_store_id'] != null): ?>
											<div class="col-sm-12 text-center" id="button-group-div-<?php echo $product['product_store_id'];?>">
												<div class="btn-group" role="group" aria-label="...">
													<?php foreach($product_states as $product_state):?>
														<button type="button" data-toggle="tooltip" data-placement="top" data-title="<?php echo __($product_state->state_description);?>" class="btn btn-xs btn-<?php echo $product_state->state_class;?>" onclick="Javascript:markProduct('<?php echo $product_state->state_keyword;?>', '<?php echo $product['product_store_id'];?>');">
															<?php echo __($product_state->state_name);?>
														</button>
													<?php endforeach;?>
												</div>
											</div>
											<br>
											<br>
										<?php endif;?>
										<div class="col-sm-12 text-left">
											<div class="panel panel-default">
						                        <div class="panel-heading">
						                            <?php echo __('Product Information');?>
						                        </div>
						                        <div class="panel-body">
						                            <ul class="list-unstyled text-left">
						                                <?php
						                            		$barcode_html = '';
		                                					$barcode_code = __('No available');

		                                					switch ($store_data->company->company_keyword) {
		                                						case 'lider':
		                                							$barcode_code = $this->Walmart->codeFormat($store_data->company->company_keyword, $product['item']);
		                                							$barcode_html = $barcode->getBarcode($barcode_code, $barcode::TYPE_EAN_13, 1);

		                                							echo '<li>'.$this->Html->tag('strong', __('Int. Code')).': '.$this->Html->div('text-left', '<img style="" src="data:image/png;base64,'.base64_encode($barcode_html).'"><br>'.$barcode_code, ['id' => 'company-internal-code-div', 'style' => 'font-size: 13px;']).'</li>';

		                                							echo '<li>'.$this->Html->tag('strong', __('EAN')).': '.$this->Html->div('text-left', $product['ean'], ['id' => 'ean-div', 'style' => 'font-size: 13px;']).'</li>';


		                                							break;
		                                						
		                                						default:
		                                							if($product['ean'] != ''){
		                                								$barcode_code = $this->Ean->format($product['ean']);
		                                								$barcode_html = $barcode->getBarcode($barcode_code, $barcode::TYPE_EAN_13, 1);
		                                							}
		                                							
		                                							echo '<li>'.$this->Html->tag('strong', __('EAN')).': '.$this->Html->div('text-left', '<img style="" src="data:image/png;base64,'.base64_encode($barcode_html).'"><br>'.$barcode_code, ['id' => 'company-internal-code-div', 'style' => 'font-size: 13px;']).'</li>';

		                                							echo '<li>'.$this->Html->tag('strong', __('Int. Code')).': '.$this->Html->div('text-left', $product['item'], ['id' => 'ean-div', 'style' => 'font-size: 13px;']).'</li>';
		                                							break;
		                                					}
						                            	?>
						                                <li>
						                                	<?php echo $this->Html->tag('strong', __('Description')).': '.$this->Html->div(null, $product['description'], ['id' => 'product-description-div']);?>
						                                </li>
						                                <li>
						                                	<?php echo $this->Html->tag('strong', __('Aisle')).': '.$this->Html->div(null, $product['aisle'], ['id' => 'aisle-div']);?>
						                                </li>
						                                <li>
						                                	<?php echo $this->Html->tag('strong', __('Lineal meter')).': '.$this->Html->div(null, $this->Number->precision($product['location_x'], 2), ['id' => 'lineal-position-div']);?>
						                                </li>
						                                <li>
						                                	<?php echo $this->Html->tag('strong', __('Height tray')).': '.$this->Html->div(null, $this->Number->precision($product['location_z'], 2), ['id' => 'height-position-div']);?>
						                                </li>
						                                <li>
						                                	<?php echo $this->Html->tag('strong', __('Detected price')).': '.$this->Html->div(null, $this->Number->currency($product['price'], 'CLP', ['precision' => 2]), ['id' => 'detected-price-div']);?>
						                                </li>
						                                <li>
						                                	<?php if(!is_null($product['stock_quantity'])): ?>

                                                                <?php echo $this->Html->tag('strong', __('Stock')).': '.$product['stock_quantity'];?>

                                                            <?php else:?>

                                                                <?php echo $this->Html->tag('strong', __('Stock')).': '.$this->Html->tag('span', __('No data'), ['class' => 'label label-default']);?>

                                                            <?php endif;?>
						                                </li>
						                                
					                                	<?php if($product['enabled'] == 0 && !is_null($product['enabled'])):?>
						                                	<li>	
						                                		<div class="label label-danger">
						                                			<?php echo __('Blocked');?>
						                                		</div>
						                                	</li>
					                                	<?php else:?>

					                                		<?php if(!is_null($product['status'])):?>
					                                			<li>	
							                                		<div class="label label-danger">
							                                			<?php echo __($product['status']);?>
							                                		</div>
							                                	</li>
							                                <?php else:?>
							                                	<li id="list-product-<?php echo $product['product_store_id'];?>">	
							                                		
							                                	</li>	
					                                		<?php endif;?>



					                                	<?php endif;?>
						                            </ul>
						                        </div>
						                        <!-- /.panel-body -->
						                    </div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-5 text-center">
										
									</div>
									<div class="col-md-7 text-left">
										
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
					<a class="right carousel-control btn-next" href="#carousel-example-generic" role="button" data-slide="next">
				    	<span class="glyphicon glyphicon-chevron-right fa-2x" aria-hidden="true"></span>
				    	<span class="sr-only">Next</span>
				  	</a>
				</div>
			<?php endforeach;?>
		<?php endif;?>
	<?php endforeach;?>
<?php endif;?>




<script>
    $(document).ready(function(){

        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });

        google.charts.load('current', {packages: ['corechart', 'line']});
		google.charts.setOnLoadCallback(drawBasic);

		$('.details-class').on('show.bs.modal', function (event) {

			var modal = $(this);
			var detection_id = modal.find('.modal-body #detection_id').val();

            if(detection_id != ''){

            	var button = $(event.relatedTarget);
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
	                    modal.find('.modal-body #facing-div').html(response.data.image_html_label);
	                }
	            });
            }
            
        });

        $('.details-class').on('hidden.bs.modal', function (e) {
        	var modal = $(this);
        	modal.find('.modal-body #detection_id').val('');
        	/*modal.find('.modal-body #facing-div').html(<?php echo "'".$this->Html->image('ajax-loader.gif', ['style' => 'width:90px;'])."'";?>);

        	modal.find('.modal-body #company-internal-code-div').html('');
            modal.find('.modal-body #ean-div').html('');
            modal.find('.modal-body #product-description-div').html('');
            modal.find('.modal-body #aisle-div').html('');
            modal.find('.modal-body #lineal-position-div').html('');
            modal.find('.modal-body #height-position-div').html('');
            modal.find('.modal-body #detected-price-div').html('');*/
		});

		$("div[id^='details-modal']").each(function(){
  
		  	var currentModal = $(this);
		  
		  	//click next
		  	currentModal.find('.btn-next').click(function(){
		    	currentModal.modal('hide');
		    	currentModal.closest("div[id^='details-modal']").nextAll("div[id^='details-modal']").first().modal('show'); 
		  	});
		  
		  	//click prev
		  	currentModal.find('.btn-prev').click(function(){
		    	currentModal.modal('hide');
		    	currentModal.closest("div[id^='details-modal']").prevAll("div[id^='details-modal']").first().modal('show'); 
		  	});

		  	currentModal.keydown(function(e) {
			  	if(e.keyCode == 37) { // left
			    	currentModal.modal('hide');
		    		currentModal.closest("div[id^='details-modal']").prevAll("div[id^='details-modal']").first().modal('show');
			  	}
			  	else if(e.keyCode == 39) { // right
			    	currentModal.modal('hide');
		    		currentModal.closest("div[id^='details-modal']").nextAll("div[id^='details-modal']").first().modal('show'); 
			  	}
			});

		});
    });

    

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

    function markProduct(keyword, product_store_id){
    	if(keyword != '' && product_store_id != ''){
    		
    		$('#button-group-div-'+product_store_id).html(<?php echo "'".$this->Html->image('ajax-loader.gif', ['style' => 'width:90px;', 'class' => 'loader', 'id' => 'loading-facing'])."'";?>);

    		$.ajax({
                url: webroot + 'ajax/productsStores/setStatus/',
                data: {
                    state_keyword: keyword,
                    product_store_id: product_store_id
                },
                cache: false,
                type: 'POST',
                dataType: 'json',
                success: function (response) 
                {
                    console.log(response);

                    if(response.status == true){
                    	$('#list-product-'+product_store_id).html('<div class="label label-'+response.data.product_state.state_class+'">'+response.data.product_state.state_name+'</div>');

                    	$('#button-group-div-'+product_store_id).slideUp();
                    	$('#button-group-div-'+product_store_id).html('');
                    	$('#status-td-'+product_store_id).html('<div class="label label-'+response.data.product_state.state_class+'">'+response.data.product_state.state_name+'</div>');
                    }
                    
                    //modal.find('.modal-body #facing-div').html(response.data.image_html_label);
                }
            });
    	}
    }

</script>
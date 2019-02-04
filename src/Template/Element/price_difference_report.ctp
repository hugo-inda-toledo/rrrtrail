<?php if($exist == true):?>

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
									if(file_exists(ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'pdfs' .  DIRECTORY_SEPARATOR . 'price_difference_'.$store_data->company_id.$store_data->id.$session_code.'.pdf')){

										echo $this->Html->link(
										$this->Html->tag('i', '', ['class' => 'fa fa-file-pdf-o']).' '.__('Download PDF'),
											'/files' . DIRECTORY_SEPARATOR . 'pdfs' .  DIRECTORY_SEPARATOR . 'price_difference_'.$store_data->company_id.$store_data->id.$session_code.'.pdf',
											[
												'escape' => false,
												'class' => 'btn btn-danger',
												'download' => 'price_difference_'.$store_data->company_id.$store_data->id.$session_code.'.pdf'
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
			<?php echo $this->Html->image('onlyletters2.png', ['style' => 'width: 190px;']); ?>
		</div>
		<div class="col-md-6 text-right">
			<?php echo $this->Html->image('companies/'.$store_data->company->company_logo, ['style' => 'width:90px;']); ?>
		</div>
	</div>

	<br>

	<div class="row">
		<div class="col-md-12">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover">
	                <thead>
	                    <tr>
	                        <th class="text-center" style="background-color: #5c5c5c;">
	                        	<?php echo $this->Html->tag('h4', __('Price Difference Report'), ['style' => 'color:#FFF;']); ?>
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
			        <?php echo $this->Html->tag('h5', __('Products with difference pricing'));?>
			    </div>
				<div class="panel-body">
					<div class="row">
						<?php
							echo $this->Form->hidden('storecode', ['value' => $store_data->store_code, 'id' => 'storecode']);
							echo $this->Form->hidden('sessionid', ['value' => $session_code, 'id' => 'sessionid']);
						?>
	    				<div class="col-md-12">
		    				
		    				<?php foreach($products_differences as $section_name => $info):?>
		    						
		    					<?php if(count($info['data']) > 0 && !isset($info['data'][0]['message'])):?>
		    						<?php echo $this->Html->tag('h4', __('{0} Section ({1} products)', $info['section']->section_name, count($info['data']))); ?>
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
				                                    <th><?php echo __('Detected price');?></th>
				                                    <th><?php echo __('Master price');?></th>
				                                    <th><?php echo __('Aisle');?></th>
				                                    <th><?php echo __('Last price change');?></th>
				                                    <th><?php echo __('Days with difference');?></th>
				                                </tr>
				                            </thead>
				                            <tbody>
				                            	<?php $x=1;?>
				                                <?php foreach($info['data'] as $product):?>

				                                	<tr>
				                                		<td><?php echo $x; ?></td>
				                                		
				                                		<td style="font-size: 13px;" class="text-left">
				                                			<?php
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

				                                				echo $this->Html->link($barcode_code, 'javascript:void(0);', ['style' => 'color: #000;', 'data-toggle' => 'modal', 'data-target' => '#details-modal-'.$product['item']]);
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
				                                				echo $this->Html->tag('strong', $this->Html->link($product['description'], 'javascript:void(0);', ['data-toggle' => 'modal', 'data-target' => '#details-modal-'.$product['item']]));
				                                			?>
				                                		</td>
				                                		<td>
				                                			<?php 
				                                				echo $this->Number->currency($product['price'], 'CLP', ['precision' => 2]);
				                                			?>
				                                		</td>
				                                		<td>
				                                			<?php 
				                                				echo $this->Html->tag('h4', $this->Number->currency($product['price_pos'], 'CLP', ['precision' => 2]), ['style' => 'margin-top: 0px;margin-bottom: 0px;']);
				                                			?>
				                                		</td>
				                                		<td><?php echo $product['aisle']; ?></td>
				                                		<td><?php echo __('No available'); ?></td>
				                                		<td><?php echo __('No available'); ?></td>
				                                	</tr>

				                                	<?php $x++;?>
				                                <?php endforeach;?>
				                            </tbody>
				                        </table>
			    					</div>
			    					
			    				<?php endif;?>

		    				<?php endforeach;?>
	    				</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php if(count($products_differences) > 0):?>
		<?php foreach($products_differences as $section_name => $info):?>
			<?php if(count($info['data']) > 0):?>
				<?php foreach($info['data'] as $product):?>
					<div class="modal fade details-class" id="details-modal-<?php echo $product['item'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
										<div class="col-md-5">
											<div class="row">

												<?php if(isset($zippedi_images[$product['detection_id']]['price_image_base64'])):?>
		                                			
		                                			<div class="col-md-12 text-center">
														<div class="panel panel-default">
									                        <div class="panel-heading">
									                            <?php echo __('Code Image');?>
									                        </div>
									                        <div class="panel-body" id="code-div">
									                        	<?php echo $this->Html->image('ajax-loader.gif', ['style' => 'width:90px;']);?>
																
															</div>
														</div>
													</div>

													<?php //unlink(WWW_ROOT.'img/'.$zippedi_images[$product['detection_id']]['price_image_path']);?>

	                                			<?php endif;?>

												<?php if(isset($zippedi_images[$product['detection_id']]['sap_image_base64']) && $store_data->company->company_keyword == 'jumbo'):?>
				                                	
				                                	<div class="col-md-12 text-center">
														<div class="panel panel-default">
									                        <div class="panel-heading">
									                            <?php echo __('Price Image');?>
									                        </div>
									                        <div class="panel-body" id="price-div">
																<?php echo $this->Html->image('ajax-loader.gif', ['style' => 'width:90px;']);?>
															</div>
														</div>
													</div>

													<?php //unlink(WWW_ROOT.'img/'.$zippedi_images[$product['detection_id']]['sap_image_path']);?>
	                                				
	                                			<?php endif;?>
												
											</div>
										</div>
										<div class="col-md-7 text-left">
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
						                                	<?php echo $this->Html->tag('strong', __('Detected price')).': '.$this->Html->div(null, $this->Number->currency($product['price'], 'CLP', ['precision' => 2]), ['id' => 'detected-price-div']);?>
						                                </li>
						                                <li>
						                                	<?php echo $this->Html->tag('strong', __('Master price')).': '.$this->Html->div(null, $this->Number->currency($product['price_pos'], 'CLP', ['precision' => 2]), ['id' => 'master-price-div', 'style' => 'font-size: 20px;color: #ad0404;font-weight: 600;']);?>
						                                </li>
						                                <li>
						                                	<?php echo $this->Html->tag('strong', __('Aisle')).': '.$this->Html->div(null, $product['aisle'], ['id' => 'aisle-div']);?>
						                                </li>
						                                <li>
						                                	<?php echo $this->Html->tag('strong', __('Last price change')).': '.$this->Html->div(null, __('No available'), ['id' => 'last-price-change-div']);?>
						                                </li>
						                                <li>
						                                	<?php echo $this->Html->tag('strong', __('Days with difference')).': '.$this->Html->div(null, __('No available'), ['id' => 'days-with-difference-div']);?>
						                                </li>
						                            </ul>
						                        </div>
						                        <!-- /.panel-body -->
						                    </div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close');?></button>
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
		            
		            var type = 'image_tag';

		            $.ajax({
		                url: webroot + 'robotReports/getLabelCrop/'+store_code+'/'+session_id+'/'+detection_id+'/price/'+type,
		                cache: false,
		                type: 'GET',
		                dataType: 'json',
		                success: function (response) 
		                {
		                    console.log(response);
		                    modal.find('.modal-body #price-div').html(response.data.image_html_label);
		                }
		            });

		            $.ajax({
		                url: webroot + 'robotReports/getLabelCrop/'+store_code+'/'+session_id+'/'+detection_id+'/sap/'+type,
		                cache: false,
		                type: 'GET',
		                dataType: 'json',
		                success: function (response) 
		                {
		                    console.log(response);
		                    modal.find('.modal-body #code-div').html(response.data.image_html_label);
		                }
		            });
	            }
	        });

	        /*$('.details-class').on('hidden.bs.modal', function (e) {
	        	var modal = $(this);
	        	/*modal.find('.modal-body #code-div').html(<?php echo "'".$this->Html->image('ajax-loader.gif', ['style' => 'width:90px;'])."'";?>);
	        	modal.find('.modal-body #price-div').html(<?php echo "'".$this->Html->image('ajax-loader.gif', ['style' => 'width:90px;'])."'";?>);
	        	modal.find('.modal-body #detection_id').val('');
			});*/

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
	        
	        /*$.ajax({
	            url: webroot + 'ajax/sections/getSectionsList/',
	            data: {
	                id: $(this).val()
	            },
	            cache: false,
	            type: 'POST',
	            dataType: 'json',
	            success: function (response) 
	            {
	                //$('#location-form-div').html(data);
	                console.log(response);

	                var filtro = '';
	                if (response.status == true) {

	                    filtro = '<label><?php echo __('Section');?></label>';
	                    filtro += '<select name="Sections[id]" id="sections-id" class="form-control"><option value="" selected><?php echo __('Select a section');?></option>';
	                    $.each(response.data.sections, function(key, value) {
	                        filtro += '<option value="'+key+'">'+value+'</option>';
	                    });
	                    filtro += '</select></div>';
	                    $('#assign-company-modal #section-select-div').html(filtro);

	                    exist_sections = 1;
	                }
	                else{
	                    $('#assign-company-modal #section-select-div').html('<?php echo $this->Html->div('alert alert-warning', __('No exist sections from this company, create one ').$this->Html->link(__('here'), ['controller' => 'Sections','action' => 'add']));?>');

	                    
	                }
	            }
	        });*/
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

	</script>
<?php else:?>
	<div class="row">
		<div class="col-md-12">
			<div class="alert alert-warning">
				<?php echo $this->Html->tag('i', '', ['class' => 'fa fa-info']).' '.__('Not exist data for the {0} report', $session_date->format('d-m-Y'));?>
	        </div>
	    </div>
	</div>
<?php endif;?>
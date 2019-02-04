<div class="row">
	<div class="col-md-12 text-right">
		<?php echo $this->Html->div('label label-default', __('Last Update: {0}', date('d-m-Y H:i:s')), ['style' => 'font-size: 15px;']);?>
	</div>
</div>

<br>

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
                    <th class="text-center" style="background-color: #5c5c5c;">
                    	<?php echo $this->Html->tag('h4', __('Stock Alert Report'), ['style' => 'color:#FFF;']); ?>
                    </th>
                </tr>
                <tr>
                    <th class="text-center" style="background-color: #dedede; color: #545454;">
                    	<?php echo __('[{0}] {1} {2} / {3}',[$store_data->store_code, $store_data->company->company_name, $store_data->store_name, $session_date->format('d-m-Y H:i:s')]); ?>
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-danger content">
		    <div class="panel-heading">
		        <?php echo $this->Html->tag('h5', __('Products with stock out alert'));?>
		    </div>
			<div class="panel-body">
				<div class="row">
    				<div class="col-md-12">
	    				<?php foreach($stock_outs as $section_name => $info):?>
	    						
	    					<?php if(count($info['data']) > 0 && !isset($info['data'][0]['message'])):?>
	    						<?php echo $this->Html->tag('h4', __('{0} Section ({1} products)', $info['section']->section_name, count($info['data']))); ?>
	    						<table class="table table-bordered" style="font-size:11px;">
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
		                                    <th><?php echo __('Description');?></th>
		                                    <th><?php echo __('Aisle');?></th>
		                                    <th><?php echo __('Lineal meter');?></th>
		                                    <th><?php echo __('Height tray');?></th>
		                                    <th><?php echo __('Detected price');?></th>
		                                    <th><?php echo __('Stock');?></th>
		                                    <th><?php echo __('Status');?></th>
		                                    <th><?php echo __('Image');?></th>
		                                </tr>
		                            </thead>
		                            <tbody>
		                            	<?php $x=1;?>
		                                <?php foreach($info['data'] as $product):?>

		                                	<tr>
		                                		<td class="text-left">
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

			  
		                                					
			                                				if($barcode_html != ''){
                                                               echo '<img style="width:120px;" src="data:image/png;base64,'.base64_encode($barcode_html).'"><br>';
                                                            }

			                                				echo $this->Html->tag('span', $barcode_code, ['style' => 'font-size:14px;']);
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
		                                				echo $this->Html->tag('strong', $product['description'], ['class' => 'text-danger', 'style' => 'font-size:10px;']);
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
                                                        <?php echo $this->Html->tag('span', __('No data'), ['class' => 'label label-default', 'style' => 'font-size:8px;']);?>
                                                    </td>
                                                <?php endif;?>


                                                <?php if(is_null($product['enabled'])):?>
                                                	
                                                	<td class="text-center">
                                                        <?php echo $this->Html->tag('span', __('No data'), ['class' => 'label label-default', 'style' => 'font-size:8px;']);?>
                                                    </td>

                                                <?php else:?>

                                                	<?php if($product['enabled'] == 0):?>

                                                		<td class="text-center">
                                                            <?php echo $this->Html->tag('span', __('Blocked'), ['class' => 'label label-danger', 'style' => 'font-size:8px;']);?>
                                                        </td>

                                                	<?php else:?>

                                                		<?php if($product['enabled'] == 1 && count($product_states) > 0 && $product['status'] == null): ?>

                                                        	<td class="text-center" id="status-td-<?php echo $product['product_store_id'];?>">
                                                        		<?php echo $this->Html->tag('span', __('Without Status'), ['class' => 'label label-default', 'style' => 'font-size:8px;']);?>
                                                        	</td>

                                                        <?php else:?>

                                                        	<td class="text-center">
                                                                <?php echo $this->Html->tag('span', __($product['status']), ['class' => 'label label-'.$product['status_class'], 'style' => 'font-size:8px;']);?>
                                                            </td>

                                                        <?php endif;?>

                                                	<?php endif;?>

                                                    

                                                <?php endif;?>

                                                <td>
		                                			<?php if(isset($zippedi_images[$product['detection_id']]['facing_image_base64'])):?>
		                                				
		                                				<img style="width: 90px;" src="data:image/jpg;base64,<?php echo base64_encode($zippedi_images[$product['detection_id']]['facing_image_base64']);?>" />
		                                			<?php endif;?>
		                                		</td>
		                                	</tr>
		                                <?php endforeach;?>
		                            </tbody>
		                        </table>
		    					
		    				<?php endif;?>

	    				<?php endforeach;?>
    				</div>
				</div>
			</div>
		</div>
	</div>
</div>
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
	    				
	    				<?php foreach($products_differences as $section_name => $info):?>
	    						
	    					<?php if(count($info['data']) > 0 && !isset($info['data'][0]['message'])):?>
	    						<?php echo $this->Html->tag('h4', __('{0} Section ({1} products)', $info['section']->section_name, count($info['data']))); ?>
	    						<table class="table table-bordered" style="font-size:12px;">
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
		                                    <th><?php echo __('Detected price');?></th>
		                                    <th><?php echo __('Master price');?></th>
		                                    <th><?php echo __('Aisle');?></th>
		                                    <th><?php echo __('Last price change');?></th>
		                                    <th><?php echo __('Days with difference');?></th>
		                                </tr>
		                            </thead>
		                            <tbody>
		                                <?php foreach($info['data'] as $product):?>

		                                	<tr>
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

	                                					if($barcode_html != ''){
                                                           echo '<img style="width:130px;" src="data:image/png;base64,'.base64_encode($barcode_html).'"><br>';
                                                        }

		                                				echo $this->Html->tag('span', $barcode_code, ['style' => 'font-size:15px;']);
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
		                                			<?php if(isset($zippedi_images[$product['detection_id']]['sap_image_base64']) && $store_data->company->company_keyword == 'jumbo'):?>
		                                				<br>

		                                				<img style="width: 90px;" src="data:image/jpg;base64,<?php echo base64_encode($zippedi_images[$product['detection_id']]['sap_image_base64']);?>" />

		                                			<?php endif;?>
		                                		</td>
		                                		<td>
		                                			<?php
		                                				echo $this->Html->tag('strong', $product['description'], ['class' => 'text-danger', 'style' => '']);
		                                			?>
		                                		</td>
		                                		<td>
		                                			<?php 
		                                				echo $this->Number->currency($product['price'], 'CLP', ['precision' => 2]);
		                                			?>
		                                			<?php if(isset($zippedi_images[$product['detection_id']]['price_image_base64'])):?>
		                                				<br>
		                                				<img style="width: 90px;" src="data:image/jpg;base64,<?php echo base64_encode($zippedi_images[$product['detection_id']]['price_image_base64']);?>" />
		                                			<?php endif;?>
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
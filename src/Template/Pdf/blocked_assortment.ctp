<!-- Page-Level Plugin CSS - Morris -->
<?php echo $this->Html->css('application/plugins/morris/morris-0.4.3.min.css', ['fullBase' => true]) ?>

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
                        <?php //echo $this->Html->image('companies/'.$robot_session->store->company->company_logo, ['style' => 'width:90px;', 'fullBase' => true]); ?>
                        <img src="<?php echo 'http://my.zippedi.com/img/companies/'.$robot_session->store->company->company_logo;?>" style="width: 90px;"/>
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<br>

<div class="row">
	<div class="col-md-12">
		<table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" style="background-color: #5c5c5c !important;">
                    	<?php echo $this->Html->tag('h4', __('Blocked Assortment Report'), ['style' => 'color:#FFF !important;']); ?>
                        <?php echo $this->Html->tag('small', __('Cataloged and blocked products with stock equal to or greater than zero not found in room'), ['style' => 'color:#FFF !important;']); ?>
                    </th>
                </tr>
                <tr>
                    <th class="text-center" style="background-color: #808080 !important; color: #FFF !important;">
                        
                        <?php 
                            if($section != null){
                                echo __('Section: {0} {1}', [$section->section_name, ($category != null) ? __('/ {0}', $category->category_name) : '']);
                            }
                            else{
                                echo __('All sections');
                            }
                            
                        ?>
                    </th>
                </tr>

                <tr>
                    <th class="text-center" style="background-color: #dedede !important; color: #545454 !important;">
                        <?php echo __('[{0}] {1} {2} - {3}',[$robot_session->store->store_code, $robot_session->store->company->company_name, $robot_session->store->store_name, $robot_session->session_date->format('d-m-Y')]); ?>
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!--<div class="row">
    <div class="col-md-12">
        <table class="table">
            <thead>
                <tr>
                    <th style="border: 0px;">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Line Chart Example
                            </div>
                            <div class="panel-body">
                                <div id="morris-line-chart"></div>
                            </div>
                        </div>
                    </th>
                    <th style="border: 0px;">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Line Chart Example
                            </div>
                            <div class="panel-body">
                                <div id="morris-line-charqt"></div>
                            </div>
                        </div>
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>-->

<?php if(isset($dates['global']['stats'])):?>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-condensed text-center">
                <tr>
                    <td colspan=2 style="border: 0px !important;">
                        <?php    
                            //$total_products = ($dates['global']['stats']['numbers_stats']['readed_products'] + $dates['global']['stats']['numbers_stats']['unreaded_products']) + ($dates['global']['stats']['numbers_stats']['readed_and_blocked_products'] + $dates['global']['stats']['numbers_stats']['unreaded_and_blocked_products']) + ($dates['global']['stats']['numbers_stats']['readed_and_discontinued_products'] + $dates['global']['stats']['numbers_stats']['unreaded_and_discontinued_products']); 

                            //$total_founded = ($dates['global']['stats']['numbers_stats']['readed_products'] + $dates['global']['stats']['numbers_stats']['readed_and_blocked_products'] + $dates['global']['stats']['numbers_stats']['readed_and_discontinued_products']);

                            $total_products = $dates['global']['stats']['numbers_stats']['total_master'];
                            $total_founded = $dates['global']['stats']['numbers_stats']['readed_products'];

                            $compliance_percentage = ($total_founded *100) / $total_products;
                        ?>

                        <?php echo $this->Html->tag('h5', __('Compliance Percentage'));?>
                        <?php echo $this->Html->tag('h2', round($compliance_percentage, 2).'%');?>
                    </td>
                </tr>
                <tr>
                    <td style="border-right: 1px !important;">
                        <?php echo $this->Html->tag('h5', __('Products in master'));?>
                        <?php echo $this->Html->tag('h2', $total_products);?>
                    </td>
                    <td style="border-left: 1px !important;">
                        <?php echo $this->Html->tag('h5', __('Correctly readed products'));?>
                        <?php echo $this->Html->tag('h2', $total_founded);?>
                    </td>
                </tr>
                <tr>
                    <td style="border-right: 1px !important;">
                        <?php
                            $total_not_founded = $dates['global']['stats']['numbers_stats']['unreaded_products'];
                        ?>
                        <?php echo $this->Html->tag('h5', __('Not readed products'));?>
                        <?php echo $this->Html->tag('h2', $total_not_founded);?>
                    </td>
                    <td style="border-left: 1px !important;">
                        <?php $readed_and_discontinued = $dates['global']['stats']['numbers_stats']['readed_and_discontinued_products'];?>

                        <?php echo $this->Html->tag('h5', __('Readed discontinued products'));?>
                        <?php echo $this->Html->tag('h2', $readed_and_discontinued);?>
                    </td>
                </tr>
                <tr>
                    <td style="border-right: 1px !important;">
                        <?php $readed_and_blocked = $dates['global']['stats']['numbers_stats']['readed_and_blocked_products'];?>

                        <?php echo $this->Html->tag('h5', __('Readed blocked products'));?>
                        <?php echo $this->Html->tag('h2', $readed_and_blocked);?>
                    </td>
                    <td style="border-left: 1px !important;">
                        <?php $unreaded_and_blocked =  $dates['global']['stats']['numbers_stats']['unreaded_and_blocked_products'];?>

                        <?php echo $this->Html->tag('h5', __('Unreaded blocked products'));?>
                        <?php echo $this->Html->tag('h2', $unreaded_and_blocked);?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
<?php endif;?>

<?php if(isset($dates['global']['stats'])):?>
    <!--<div class="row">
        <div class="col-md-8">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th colspan=1 rowspan=3></th>
                        <th colspan=6 class="text-center" style="border-left: 0px !important;"><?php echo __('Room'); ?></th>
                    </tr>
                    <tr style="font-size: 11px !important;">
                        <th style="background-color: #d9534f !important;color: #FFF !important;"><?php echo __('Total');?></th>
                        <th colspan=2 style="background-color: #d9534f !important;color: #FFF !important;"><?php echo __('Founded'); ?></th>
                        <th colspan=2 style="background-color: #d9534f !important;color: #FFF !important;"><?php echo __('Not founded'); ?></th>
                    </tr>
                    <tr style="font-size: 11px !important;">
                        <th style="background-color: #484747 !important;color: #FFF !important;"><?php echo __('Quantity'); ?></th>
                        <th style="background-color: #484747 !important;color: #FFF !important;"><?php echo __('Quantity'); ?></th>
                        <th style="background-color: #484747 !important;color: #FFF !important;"><?php echo __('%'); ?></th>
                        <th style="background-color: #484747 !important;color: #FFF !important;"><?php echo __('Quantity'); ?></th>
                        <th style="background-color: #484747 !important;color: #FFF !important;"><?php echo __('%'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="background-color: #484747 !important; color: #FFF !important;font-size: 10px !important;"><?php echo __('Cataloged without blocking');?></td>
                        <td><?php echo ($dates['global']['stats']['numbers_stats']['readed_products'] + $dates['global']['stats']['numbers_stats']['unreaded_products']) ?></td>
                        <td><?php echo $dates['global']['stats']['numbers_stats']['readed_products'];?></td>
                        <td><?php echo round($dates['global']['stats']['percent_stats']['readed_products']).'%';?></td>
                        <td><?php echo $dates['global']['stats']['numbers_stats']['unreaded_products'];?></td>
                        <td><?php echo round($dates['global']['stats']['percent_stats']['unreaded_products']).'%';?></td>
                    </tr>
                    <tr>
                        <td style="background-color: #484747 !important; color: #FFF !important;font-size: 10px !important;"><?php echo __('Cataloged with blocking');?></td>
                        <td><?php echo ($dates['global']['stats']['numbers_stats']['readed_and_blocked_products'] + $dates['global']['stats']['numbers_stats']['unreaded_and_blocked_products']) ?></td>
                        <td><?php echo $dates['global']['stats']['numbers_stats']['readed_and_blocked_products'];?></td>
                        <td><?php echo round($dates['global']['stats']['percent_stats']['readed_and_blocked_products']).'%';?></td>
                        <td><?php echo $dates['global']['stats']['numbers_stats']['unreaded_and_blocked_products'];?></td>
                        <td><?php echo round($dates['global']['stats']['percent_stats']['unreaded_and_blocked_products']).'%';?></td>
                        
                    </tr>
                    <tr>
                        <td style="background-color: #484747 !important; color: #FFF !important;font-size: 10px !important;"><?php echo __('Discontinued with stock (Theoretical)');?></td>
                        <td><?php echo ($dates['global']['stats']['numbers_stats']['readed_and_discontinued_products'] + $dates['global']['stats']['numbers_stats']['unreaded_and_discontinued_products']) ?></td>
                        <td><?php echo $dates['global']['stats']['numbers_stats']['readed_and_discontinued_products'];?></td>
                        <td><?php echo round($dates['global']['stats']['percent_stats']['readed_and_discontinued_products']).'%';?></td>
                        <td><?php echo $dates['global']['stats']['numbers_stats']['unreaded_and_discontinued_products'];?></td>
                        <td><?php echo round($dates['global']['stats']['percent_stats']['unreaded_and_discontinued_products']).'%';?></td>
                                                
                    </tr>
                    <tr>
                        <td colspan=4 style="background-color: #FFF !important; color: #FFF !important;"></td>
                    </tr>
                    <tr>
                        <td style="background-color: #d9534f !important; color: #FFF !important;"><?php echo __('Total room');?></td>
                        <td>
                            <?php
                                $total_products = ($dates['global']['stats']['numbers_stats']['readed_products'] + $dates['global']['stats']['numbers_stats']['unreaded_products']) + ($dates['global']['stats']['numbers_stats']['readed_and_blocked_products'] + $dates['global']['stats']['numbers_stats']['unreaded_and_blocked_products']) + ($dates['global']['stats']['numbers_stats']['readed_and_discontinued_products'] + $dates['global']['stats']['numbers_stats']['unreaded_and_discontinued_products']); 
                                echo $total_products;
                            ?>  
                        </td>
                        <td colspan=2>
                            <?php 
                                $total_founded = ($dates['global']['stats']['numbers_stats']['readed_products'] + $dates['global']['stats']['numbers_stats']['readed_and_blocked_products'] + $dates['global']['stats']['numbers_stats']['readed_and_discontinued_products']);
                                echo $total_founded;
                            ?>
                        </td>
                        <td colspan=2>
                            <?php
                                $total_not_founded = ($dates['global']['stats']['numbers_stats']['unreaded_products'] + $dates['global']['stats']['numbers_stats']['unreaded_and_blocked_products'] + $dates['global']['stats']['numbers_stats']['unreaded_and_discontinued_products']); 
                                echo $total_not_founded;
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-md-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th colspan=1 rowspan=2 style="border:0px !important;"></th>
                        <th colspan=3 class="text-center" style="border-left: 0px !important;"><?php echo __('Room'); ?></th>
                    </tr>
                    <tr style="font-size: 11px !important;">
                        <th style="background-color: #d9534f !important;color: #FFF !important;"><?php echo __('Founded'); ?></th>
                        <th style="background-color: #d9534f !important;color: #FFF !important;"><?php echo __('Not founded'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="background-color: #484747 !important; color: #FFF !important;font-size: 10px !important;"><?php echo __('Cataloged without blocking');?></td>
                        <td><?php echo ($total_founded == 0) ? 0 : round(($dates['global']['stats']['numbers_stats']['readed_products'] * 100) / $total_founded);?>%</td>
                        <td><?php echo ($total_not_founded == 0) ? 0 : round(($dates['global']['stats']['numbers_stats']['unreaded_products'] * 100) / $total_not_founded);?>%</td>
                    </tr>
                    <tr>
                        <td style="background-color: #484747 !important; color: #FFF !important;font-size: 10px !important;"><?php echo __('Cataloged with blocking');?></td>
                        <td><?php echo ($total_founded == 0) ? 0 : round(($dates['global']['stats']['numbers_stats']['readed_and_blocked_products'] * 100) / $total_founded);?>%</td>
                        <td><?php echo ($total_not_founded == 0) ? 0 : round(($dates['global']['stats']['numbers_stats']['unreaded_and_blocked_products'] * 100) / $total_not_founded);?>%</td>
                    </tr>
                    <tr>
                        <td style="background-color: #484747 !important; color: #FFF !important;font-size: 10px !important;"><?php echo __('Discontinued with stock (Theoretical)');?></td>
                        <td><?php echo ($total_founded == 0) ? 0 : round(($dates['global']['stats']['numbers_stats']['readed_and_discontinued_products'] * 100) / $total_founded);?>%</td>
                        <td><?php echo ($total_not_founded == 0) ? 0 : round(($dates['global']['stats']['numbers_stats']['unreaded_and_discontinued_products'] * 100) / $total_not_founded);?>%</td>
                    </tr>
                    <tr>
                        <td colspan=3 style="background-color: #FFF !important; color: #FFF !important;"></td>
                    </tr>
                    <tr>
                        <td style="background-color: #d9534f !important; color: #FFF !important;"><?php echo __('Total room');?></td>
                        <td>
                            <?php 

                                if($total_founded == 0){
                                    $global_percent_founded_total = 0;
                                }
                                else{
                                    $global_percent_founded_total = 
                                    (($dates['global']['stats']['numbers_stats']['readed_products'] * 100) / $total_founded) +
                                    (($dates['global']['stats']['numbers_stats']['readed_and_blocked_products'] * 100) / $total_founded) +
                                    (($dates['global']['stats']['numbers_stats']['readed_and_discontinued_products'] * 100) / $total_founded);
                                }
                                

                                echo round($global_percent_founded_total).'%';
                            ?>
                        </td>
                        <td>
                            <?php
                                if($total_not_founded == 0){
                                    $global_percent_not_founded_total = 0;
                                }
                                else{
                                    $global_percent_not_founded_total = 
                                    (($dates['global']['stats']['numbers_stats']['unreaded_products'] * 100) / $total_not_founded) +
                                    (($dates['global']['stats']['numbers_stats']['unreaded_and_blocked_products'] * 100) / $total_not_founded) +
                                    (($dates['global']['stats']['numbers_stats']['unreaded_and_discontinued_products'] * 100) / $total_not_founded);
                                }
                                

                                echo round($global_percent_not_founded_total).'%';
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>-->
<?php endif;?>

<div class="row">
	<div class="col-md-12 text-left">
		<?php //echo $this->Html->tag('h5', __('{0} {1} - Assortment Report - Between {2}', [$robot_session->store->company->company_name, $robot_session->store->store_name, $robot_session->session_date->format('d-m-Y')])); ?>
	</div>
</div>

<?php if(isset($dates['global']['products'])):?>
    <div class="row">
        <div class="col-md-12">
            <?php $x=0;?>
            <?php foreach($dates['global']['products'] as $section_name => $category_array):?>
                <?php if(count($category_array) > 0 ):?>
                    <?php foreach($category_array as $category_name => $arr):?>
                        <?php if(count($arr['data']) > 0):?>
                            
                            <?php if($x == 0):?>
                                <div class="panel panel-default content">
                                <?php $x=1;?>
                            <?php else:?>
                                <div class="panel panel-default content" style="page-break-before:always;">
                            <?php endif;?>

                                <div class="panel-heading">
                                    <?php //echo $this->Html->tag('h5', __('Products not found in room'));?>
                                    <?php echo $this->Html->tag('h5', __('Section: {0} / {1}', [$section_name, $category_name]));?>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            

                                            <?php //echo $this->Html->tag('h5', __('Category: {0}', $category_name));?>

                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align: center;"><?php echo __('EAN13');?></th>
                                                        <th style="text-align: center;"><?php echo __('Stock');?></th>
                                                        <th style="text-align: center;"><?php echo __('Product name');?></th>
                                                        
                                                        <th style="text-align: center;"><?php echo __('Internal code');?></th>
                                                        <!--<th style="text-align: center;"><?php //echo __('Status');?></th>-->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($arr['data'] as $product):?>

                                                        <tr class="text-center">
                                                            <td style="font-size: 13px;">
                                                                <?php

                                                                    $barcode_html = '';
                                                                    $barcode_code = __('No available');

                                                                    //$barcode_code = $this->Ean->format($product->product->ean13.$product->product->ean13_digit);
                                                                    /*$barcode_html = $barcode->getBarcode($barcode_code, $barcode::TYPE_EAN_13, 1);*/

                                                                    switch ($robot_session->store->company->company_keyword) {
                                                                        case 'lider':
                                                                            $barcode_code = $this->Walmart->codeFormat($robot_session->store->company->company_keyword, $product['internal_code']);

                                                                            if(strlen($barcode_code) == 13){
                                                                                $barcode_html = $barcode->getBarcode($barcode_code, $barcode::TYPE_EAN_13, 1);
                                                                            }

                                                                            break;
                                                                        
                                                                        default:
                                                                            if($product['ean13'] != null){
                                                                                $barcode_code = $this->Ean->format($product['ean13']);

                                                                                if(strlen($barcode_code) == 13){
                                                                                   $barcode_html = $barcode->getBarcode($barcode_code, $barcode::TYPE_EAN_13, 1); 
                                                                                }
                                                                            }
                                                                            
                                                                            break;
                                                                    }

                                                                    if($barcode_html != ''){
                                                                        echo '<img style="" src="data:image/png;base64,'.base64_encode($barcode_html).'"><br>';
                                                                    }

                                                                    echo $barcode_code;
                                                                    
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php //$stock_text = '';$stock_label_class = ''?>
                                                                <?php //if($product['stock'] != ''): ?> 
                                                                    <?php
                                                                        echo $this->Html->tag('span', $product['stock'], ['class' => 'badge']);

                                                                        /*echo '<br>';

                                                                        if($product->stock_up_to_date > 0){
                                                                            echo $this->Html->tag('span', __('Need to implement'), ['class' => 'label label-warning']);

                                                                            $stock_text = __('Need to implement');
                                                                            $stock_label_class = 'warning';
                                                                        }
                                                                        else{
                                                                            echo $this->Html->tag('span', __('Need to buy'), ['class' => 'label label-danger']);
                                                                            $stock_text = __('Need to buy');
                                                                            $stock_label_class = 'danger';
                                                                        }*/
                                                                    ?>
                                                                <?php //else:?>
                                                                    <?php 
                                                                        /*echo $this->Html->tag('span', __('No data'), ['class' => 'label label-default']);

                                                                        $stock_text = __('No data');
                                                                        $stock_label_class = 'default';*/
                                                                    ?>
                                                                <?php //endif;?>
                                                            </td>

                                                            <td>
                                                                <?php 
                                                                    echo $this->Html->tag('strong', $product['description'], ['class' => 'text-danger']);
                                                                ?>
                                                            </td>

                                                            
                                                            <td><?php echo $product['internal_code']; ?></td>
                                                            <!--<td id="status-td-<?php //echo $product['id'];?>">
                                                                <?php //if($product['enabled'] == 0): ?>
                                                                    
                                                                    <?php //echo $this->Html->tag('span', __('Blocked'), ['class' => 'label label-danger']);?>

                                                                <?php //else:?>
                                                                    
                                                                    <?php //if(count($product_states) > 0 && $product['product_state_name'] == null):?>

                                                                        <div class="btn-group" role="group" aria-label="..." id="button-group-div-<?php //echo $product['id'];?>">
                                                                            <?php //foreach($product_states as $product_state):?>
                                                                                <button type="button" class="btn btn-sm btn-default" onclick="Javascript:markProduct('<?php //echo $product_state->state_keyword;?>', '<?php //echo $product['id'];?>');">
                                                                                    <?php //echo __($product_state->state_name);?>
                                                                                </button>
                                                                            <?php //endforeach;?>
                                                                        </div>
                                                                        <?php //echo $this->Html->tag('span', __('Without status'), ['class' => 'label label-default']);?>

                                                                    <?php //else:?>
                                                                        

                                                                        <?php //echo $this->Html->tag('span', __($product['product_state_name']), ['class' => 'label label-'.$product['product_state_class']]);?>

                                                                    <?php //endif;?>

                                                                <?php //endif;?>
                                                            </td>-->
                                                            
                                                        </tr>
                                                    <?php endforeach;?>
                                                </tbody>
                                            </table>
                                                    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif;?>
                    <?php endforeach;?>
                <?php endif;?>
            <?php endforeach;?>
        </div>
    </div> 
<?php endif;?>

<!-- Core Scripts - Include with every page -->

<?php echo $this->Html->script('application/jquery-1.10.2.js', ['fullBase' => true]) ?>

<?php echo $this->Html->script('application/bootstrap.min.js', ['fullBase' => true]) ?>
<?php echo $this->Html->script('application/plugins/metisMenu/jquery.metisMenu.js', ['fullBase' => true]) ?>

<!-- Page-Level Plugin Scripts - Morris -->
<?php echo $this->Html->script('application/plugins/morris/raphael-2.1.0.min.js', ['fullBase' => true]) ?>
<?php echo $this->Html->script('application/plugins/morris/morris.js', ['fullBase' => true]) ?>

<!-- SB Admin Scripts - Include with every page -->
<?php echo $this->Html->script('application/sb-admin.js', ['fullBase' => true]) ?>

<!-- Page-Level Demo Scripts - Morris - Use for reference -->
<?php echo $this->Html->script('application/demo/morris-demo.js', ['fullBase' => true]) ?>
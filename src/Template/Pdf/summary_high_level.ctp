<!-- Page-Level Plugin CSS - Morris -->
<?php echo $this->Html->css('application/plugins/morris/morris-0.4.3.min.css', ['fullBase' => true]) ?>

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
                        <img src="<?php echo 'http://my.zippedi.com/img/companies/'.$company->company_logo;?>" style="width: 90px;"/>
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
                    	<?php echo $this->Html->tag('h4', __('High level report'), ['style' => 'color:#FFF !important;']); ?>
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-condensed text-center">
            <tr>
                <td style="border-right: 1px !important;">
                    <?php echo $this->Html->tag('h5', __("Analized aisles"));?>
                    <?php echo $this->Html->tag('h2', $data['global']['total_analyzed_aisles']);?>
                </td>
                <td style="border-left: 1px !important;">
                    <?php echo $this->Html->tag('h5', __('Correctly readed labels'));?>
                    <?php echo $this->Html->tag('h2', $data['global']['total_detections']);?>
                </td>
                <td style="border-left: 1px !important;">
                    <?php echo $this->Html->tag('h5', __('Audited products'));?>
                    <?php echo $this->Html->tag('h2', $data['global']['total_products']);?>
                </td>
            </tr>
            <tr>
                <td style="border-right: 1px !important;">
                    <?php echo $this->Html->tag('h5', __('Price differences'));?>
                    <?php echo $this->Html->tag('h2', $data['global']['total_price_difference_detections']);?>
                </td>
                <td style="border-left: 1px !important;">
                    <?php echo $this->Html->tag('h5', __('Stock alerts'));?>
                    <?php echo $this->Html->tag('h2', $data['global']['total_stock_alert_detections']);?>
                </td>
                <td style="border-left: 1px !important;">
                    <?php echo $this->Html->tag('h5', __('Assortment compliance'));?>
                    <?php echo $this->Html->tag('h2', $data['global']['total_percent_assortment']);?>
                </td>
            </tr>

        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default content">
            <div class="panel-heading">
                <?php echo $this->Html->tag('h5', __('Overview'));?>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="text-align: center;"><?php echo __('Session date');?></th>
                                    <th style="text-align: center;"><?php echo __('Store');?></th>
                                    <th style="text-align: center;"><?php echo __('Analized aisles');?></th>
                                    <th style="text-align: center;"><?php echo __('Readed labels');?></th>
                                    
                                    <th style="text-align: center;"><?php echo __('Audited products');?></th>
                                    <th style="text-align: center;"><?php echo __('Price differences');?></th>
                                    <th style="text-align: center;"><?php echo __('Stock alerts');?></th>
                                    <th style="text-align: center;"><?php echo __('Assortment compliance');?></th>
                                    <!--<th style="text-align: center;"><?php //echo __('Total');?></th>
                                    <th style="text-align: center;"><?php //echo __('Ranking');?></th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['stores'] as $store_code => $data):?>

                                    <tr class="text-center">
                                        <td>
                                           <?php echo $data['robot_session']['data']['session_date']->format('d-m-Y H:i'); ?> 
                                        </td>
                                        <td>
                                            <?php echo __('[{0}] {1}', [$data['store']['store_code'], $data['store']['store_name']]); ?>
                                        </td>
                                        <td>
                                           <?php echo $data['robot_session']['stats']['total_analyzed_aisles']; ?> 
                                        </td>
                                        <td>
                                            <?php echo $data['robot_session']['stats']['total_detections']; ?> 
                                        </td>
                                        <td>
                                           <?php echo __('No data'); ?> 
                                        </td>
                                        <td>
                                            <?php echo $data['robot_session']['stats']['total_price_difference_detections']; ?> 
                                        </td>
                                        <td>
                                            <?php echo $data['robot_session']['stats']['total_stock_alert_detections']; ?> 
                                        </td>
                                        <td>
                                           <?php echo __('No data'); ?> 
                                        </td>
                                        <!--<td>
                                           <?php //echo __('No data'); ?> 
                                        </td>
                                        <td>
                                           <?php //echo __('No data'); ?> 
                                        </td>-->
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 

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
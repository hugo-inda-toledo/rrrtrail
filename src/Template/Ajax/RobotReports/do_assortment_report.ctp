<?php $this->layout = null;?>

<?php if(count($not_readed_products['products']) > 0):?>

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
                    <?php echo __('Active Assortment Report');?>
                </li>
            </ol>
            <?php echo $this->Html->image('onlyletters2.png', ['style' => 'width: 115px;margin: -40px 0px;', 'class' => 'pull-right hidden-xs']);?>
        </div>
        <div class="section-body">
            <div class="card">

                <div class="card-head style-default-light">
                    <div class="tools pull-left" style="margin-left: 12px;">
                        <?php echo $this->Html->image('companies/'.$robot_session->store->company->company_logo, ['style' => 'width:40px;', 'fullBase' => true]).' '.$this->Html->tag('strong', __('Report summary: {0}', [$catalog_date->format('d-m-Y')]), ['style' => 'font-size: 14px;margin-left: 15px;']); ?>
                    </div>
                    <div class="btn-group pull-right" role="group" style="padding: 15px;">
                        

                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Products -->
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-5 text-center">
                                    <!--<div class="row">
                                        <div class="col-sm-12">
                                        </div>

                                        <div class="col-sm-12">
                                        </div>
                                    </div>-->
                                    <?php    
                                        //$total_products = ($dates['global']['stats']['numbers_stats']['readed_products'] + $dates['global']['stats']['numbers_stats']['unreaded_products']) + ($dates['global']['stats']['numbers_stats']['readed_and_blocked_products'] + $dates['global']['stats']['numbers_stats']['unreaded_and_blocked_products']) + ($dates['global']['stats']['numbers_stats']['readed_and_discontinued_products'] + $dates['global']['stats']['numbers_stats']['unreaded_and_discontinued_products']); 

                                        //$total_founded = ($dates['global']['stats']['numbers_stats']['readed_products'] + $dates['global']['stats']['numbers_stats']['readed_and_blocked_products'] + $dates['global']['stats']['numbers_stats']['readed_and_discontinued_products']);

                                        $total_products = $not_readed_products_count + $readed_products;

                                        $compliance_percentage = ($readed_products *100) / $total_products;
                                    ?>

                                    <?php echo $this->Html->tag('h2', round($compliance_percentage, 2).'%');?>
                                    <?php echo $this->Html->tag('h5', __('Compliance Percentage'));?>
                                    <!--<div id="morris-area-graph" class="height-5" data-colors="#9C27B0,#0aa89e"></div>-->
                                    <div id="donut-detections-assortment" class="height-6" data-colors="#0fbb06,#CB0202"></div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="row">
                                        <div class="col-sm-12 text-center">
                                            <?php echo $this->Html->tag('h5', __('Cataloged and enabled products in master'));?>
                                            <?php echo $this->Html->tag('h2', $total_products);?>
                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 text-center">
                                            <?php echo $this->Html->tag('h5', __('Correctly readed products'));?>
                                            <?php echo $this->Html->tag('h2', $readed_products);?>
                                        </div>
                                        <div class="col-sm-6 text-center">
                                            <?php echo $this->Html->tag('h5', __('Not readed products'));?>
                                            <?php echo $this->Html->tag('h2', $not_readed_products_count);?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            
            <?php //if(count($) > 0):?>
                <div class="card">

                    <div class="card-head style-default-light">
                        <div class="tools pull-left" style="margin-left: 12px;">
                            <?php echo $this->Html->image('companies/'.$robot_session->store->company->company_logo, ['style' => 'width:40px;', 'fullBase' => true]).' '.$this->Html->tag('strong', __('Report details'), ['style' => 'font-size: 14px;margin-left: 15px;']); ?>
                        </div>
                        <div class="btn-group pull-right" role="group" style="padding: 15px;">
                            <?php 
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', ['class' => 'fa fa-file-excel-o']),
                                    '/reports/activeAssortmentReport/download/xlsx/'.$robot_session->id.'/'.(($section_id != 'all') ? $section_id : 'all').'/'.(($category_id != '') ? $category_id : 'all'),
                                    [
                                        /*'data' => [
                                            'company_id' =>$store->company->id,
                                            'store_id' => $store->id, 
                                            'end_date' => $dates['global']['start_date']['report_date']->format('Y-m-d'),
                                            'section_id' =>  $section_id,
                                            'category_id' => $category_id
                                        ],*/
                                        'escape' => false,
                                        'class' => 'btn btn-success',
                                        'data-toggle' => 'tooltip', 
                                        'title' => __('Download Excel'), 
                                        'data-placement' => 'bottom'
                                    ]
                                );

                                if($robot_session->store->company->company_keyword == 'jumbo'){
                                    echo $this->Html->link(
                                        $this->Html->tag('i', '', ['class' => 'fa fa-barcode']),
                                        '/files/labels/'.$robot_session->store->store_code.'-'.$catalog_date_query.'-'.(($section != null) ? $section->id : '-all').(($category != null) ? '-'.$category->id : '').'-assortment.inv',
                                        [
                                            'escape' => false,
                                            'class' => 'btn btn-info',
                                            'target' => '_blank',
                                            'data-toggle' => 'tooltip', 
                                            'title' => __('Download .inv'),
                                            'data-placement' => 'bottom',
                                            'download' => $robot_session->store->store_code.'-'.$catalog_date->format('Ymd').'-'.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != null) ? '-'.$category_id : '').'-assortment'.'.inv'
                                        ]
                                    );
                                }
                            ?>
                            <?php 
                                //if(file_exists(ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'pdfs' .  DIRECTORY_SEPARATOR . 'price_difference_'.$store->company->company_keyword.'_'.$store->store_code.'_'.$session_code.'.pdf')){

                                    echo $this->Html->link(
                                        $this->Html->tag('i', '', ['class' => 'fa fa-file-pdf-o']),
                                        '/reports/activeAssortmentReport/download/pdf/'.$robot_session->id.'/'.(($section_id != 'all') ? $section_id : 'all').'/'.(($category_id != '') ? $category_id : 'all'),
                                        [
                                            'escape' => false,
                                            'class' => 'btn btn-danger',
                                            'target' => '_blank',
                                            'data-toggle' => 'tooltip', 
                                            'title' => __('Download PDF').' '.__('list'), 
                                            'data-placement' => 'bottom',
                                            'id' => 'pdf-download-buttom'
                                        ]
                                    );
                                //}
                            ?>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            <!--<div class="col-sm-4 col-md-3 col-lg-2 hidden-xs">
                                <ul class="nav nav-pills nav-stacked" style="font-size: 11px;" id="filters-ul">
                                    <li>
                                        <small><?php echo __('Categories');?></small>
                                    </li>
                                    <li id="filter-category-all" class="active-zippedi list-class">
                                        <?php echo $this->Html->link(__('All').' '.$this->Html->tag('small', $dates['global']['stats']['numbers_stats']['unreaded_products'], ['class' => 'pull-right text-bold opacity-75']), 'javascript:void(0);', ['escape' => false, 'onclick' => "javascript:doFilter('all')"]);?>
                                    </li>

                                    <?php foreach($dates['global']['products'] as $category_code => $collection):?>
                                        <li id="filter-category-<?php echo $category_code;?>" class="list-class">

                                            <?php echo $this->Html->link($collection['category']->category_name.' '.$this->Html->tag('small', count($collection['data']), ['class' => 'pull-right text-bold opacity-75']), 'javascript:void(0);', ['escape' => false, 'onclick' => 'javascript:doFilter('.$category_code.');']);?>
                                        </li>
                                    <?php endforeach;?>
                                </ul>
                            </div>-->

                            

                            <div class="col-sm-12 col-md-12 col-lg-12">
                            <!--div class="col-sm-8 col-md-9 col-lg-10">-->

                                <div class="panel-group" id="accordion"> 
                                    <?php $x=0;?>
                                    <?php foreach($not_readed_products['products'] as $section_name => $category_array):?>
                                        <?php if(count($category_array['data']) > 0):?>

                                            <?php foreach($category_array['data'] as $category_name => $arr):?>

                                                <?php if(count($arr['data']) > 0):?>

                                                    <div class="card panel style-default-light">
                                                        

                                                        <?php if($x == 0):?>
                                                            <div class="card-head card-head-xs" data-toggle="collapse" data-parent="#accordion-<?php echo $category_array['section_data']['section_code'].'-'.$arr['category_data']['category_code'];?>" data-target="#heading-<?php echo $category_array['section_data']['section_code'].'-'.$arr['category_data']['category_code']?>" aria-expanded="true">
                                                        <?php else:?>
                                                            <div class="card-head card-head-xs" data-toggle="collapse" data-parent="#accordion-<?php echo $category_array['section_data']['section_code'].'-'.$arr['category_data']['category_code'];?>" data-target="#heading-<?php echo $category_array['section_data']['section_code'].'-'.$arr['category_data']['category_code']?>" aria-expanded="false">
                                                        <?php endif;?>

                                                            <header>
                                                                <?php echo __('{0} - {1}', [$category_array['section_data']['section_name'], $arr['category_data']['category_name']]);?>
                                                            </header>
                                                            <div class="tools">
                                                                <a class="btn btn-icon-toggle"><i class="fa fa-angle-down"></i></a>
                                                            </div>
                                                        </div>
                                                        <?php if($x == 0):?>
                                                            <div id="heading-<?php echo $category_array['section_data']['section_code'].'-'.$arr['category_data']['category_code'];?>" class="collapse in" aria-expanded="true" style="">
                                                            <?php $x++;?>
                                                        <?php else:?>
                                                            <div id="heading-<?php echo $category_array['section_data']['section_code'].'-'.$arr['category_data']['category_code'];?>" class="collapse" aria-expanded="false" style="">
                                                        <?php endif;?>

                                                            <div class="card-body style-default-bright">
                                                                <div class="table-responsive">
                                                                    <table id="datatable-<?php echo $arr['category_data']['category_code'];?>" class="table table-hover table-condensed nowrap" cellspacing="0" width="100%" style="font-size: 12px;margin-bottom: 0px;">
                                                                        <thead>
                                                                            <tr>
                                                                                <?php if($robot_session->store->company->company_keyword == 'lider'):?>
                                                                                    <th style="text-align: left;">
                                                                                        <?php echo __('Internal code');?>
                                                                                    </th>
                                                                                <?php else:?>
                                                                                    <th style="text-align: left;">
                                                                                        <?php echo __('EAN13');?>
                                                                                    </th>
                                                                                <?php endif;?>
                                                                                
                                                                                <th style="text-align: center;"><?php echo __('Stock');?></th>
                                                                                <th style="text-align: center;"><?php echo __('Product name');?></th>

                                                                                <?php if($robot_session->store->company->company_keyword == 'lider'):?>
                                                                                    <th class="hidden-sm hidden-xs" style="text-align: center;">
                                                                                        <?php echo __('EAN13');?>
                                                                                    </th>
                                                                                <?php else:?>
                                                                                    <th class="hidden-sm hidden-xs" style="text-align: center;">
                                                                                        <?php echo __('Internal code');?>
                                                                                    </th>
                                                                                <?php endif;?>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php foreach($arr['data'] as $product):?>

                                                                                <tr class="text-center">
                                                                                    <td style="font-size: 13px;" class="hidden-sm hidden-xs text-left">
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
                                                                                        <?php $stock_text = '';$stock_label_class = ''?>
                                                                                        <?php if($product['stock'] != ''): ?> 
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
                                                                                        <?php else:?>
                                                                                            <?php 
                                                                                                echo $this->Html->tag('span', __('No data'), ['class' => 'label label-default']);

                                                                                                $stock_text = __('No data');
                                                                                                $stock_label_class = 'default';
                                                                                            ?>
                                                                                        <?php endif;?>
                                                                                    </td>

                                                                                    <td>
                                                                                        <?php 
                                                                                            echo $this->Html->tag('strong', $product['description'], ['class' => 'text-danger']);
                                                                                        ?>
                                                                                    </td>                                                                               
                                                                                    <?php if($robot_session->store->company->company_keyword == 'lider'):?>
                                                                                        <td><?php echo $product['ean13']; ?></td>
                                                                                    <?php else:?>
                                                                                        <td><?php echo $product['internal_code']; ?></td>
                                                                                    <?php endif;?>
                                                                                    
                                                                                </tr>
                                                                            <?php endforeach;?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>

                                                                <script type="text/javascript">
                                                                    $(document).ready(function () {
                                                                        var table = $('#datatable-<?php echo $category_array['section_data']['section_code'].'-'.$arr['category_data']['category_code'];?>').DataTable({
                                                                            "drawCallback": function () {
                                                                                $('.dataTables_paginate').addClass('text-right');
                                                                                $('.dataTables_paginate').removeClass('paging_simple_numbers');
                                                                                $('.dataTables_paginate').removeClass('dataTables_paginate');
                                                                            },
                                                                            <?php 
                                                                                $data_file = $this->Url->build('/files' . DIRECTORY_SEPARATOR . 'datatable_spanish.json', [
                                                                                    //'escape' => false,
                                                                                    //'fullBase' => true,
                                                                                ]);
                                                                            ?>
                                                                            language: {
                                                                                url: <?php echo "'".$data_file."'";?>
                                                                            }
                                                                        });
                                                                    });
                                                                </script>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php endif;?>

                                            <?php endforeach;?>
                                            
                                        <?php endif;?>
                                    <?php endforeach;?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php //endif;?>
        </div>
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
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <?php echo __('Code Image');?>
                                </div>
                                <div class="panel-body" id="code-div">
                                    <?php echo $this->Html->image('ajax-loader.gif', ['style' => 'width:90px;']);?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                                
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <?php echo __('Price Image');?>
                                </div>
                                <div class="panel-body" id="price-div">
                                    <?php echo $this->Html->image('ajax-loader.gif', ['style' => 'width:90px;']);?>
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


            Morris.Donut({
                element: 'donut-detections-assortment',
                data: [
                    {label: "<?php echo __('Correctly readed products');?>", value: <?php echo $readed_products;?>},
                    {label: "<?php echo __('Not readed products');?>", value: <?php echo $not_readed_products_count;?>},
                ],
                colors: $('#donut-detections-assortment').data('colors').split(',')
            });

            /*var labelColor = $('#morris-area-graph').css('color');

            Morris.Area({
                element: 'morris-area-graph',
                behaveLikeLine: true,
                parseTime: false,
                data: [

                    <?php //foreach($stats as $stat):?>
                        {x: '<?php //echo $stat['session_date']->format("d-m-Y");?>', y: <?php //echo $stat['total_cataloged'];?>, z: <?php //echo $stat['detections'];?>},
                    <?php //endforeach;?>
                ],
                xkey: 'x',
                ykeys: ['y', 'z'],
                labels: ['<?php //echo __("Catalogs")?>', '<?php //echo __("Detections")?>'],
                gridTextColor: labelColor,
                lineColors: $('#morris-area-graph').data('colors').split(',')
            });*/

            $('[data-toggle="tooltip"]').tooltip();
            $('#details-modal').on('show.bs.modal', function (event) {

                var modal = $(this);

                //var detection_id = modal.find('.modal-body #detection_id').val();
                //if(detection_id != ''){
                    var button = $(event.relatedTarget);
                    var detection_id = button.data('detectionid');
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
                //}
            });

            $('#details-modal').on('hidden.bs.modal', function (e) {
                var modal = $(this);
                modal.find('.modal-body #code-div').html(<?php echo "'".$this->Html->image('ajax-loader.gif', ['style' => 'width:90px;'])."'";?>);
                modal.find('.modal-body #price-div').html(<?php echo "'".$this->Html->image('ajax-loader.gif', ['style' => 'width:90px;'])."'";?>);
                //modal.find('.modal-body #detection_id').val('');
            });

        });

        function doFilter(section_id){
            
            
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





    <script>
        $(document).ready(function(){

            $('#sessions-details-button').on('click', function(){
                if($('#sessions-details-div').css('display') == 'none' ){
                    $('#sessions-details-div').slideDown();
                    $('#sessions-details-button').html('<?php echo __("Hide details");?>');
                }
                else{
                    $('#sessions-details-div').slideUp();
                    $('#sessions-details-button').html('<?php echo __("Show details");?>');
                }

            });
        });

        function markProduct(keyword, product_store_id){

            if(keyword != '' && product_store_id != ''){
                
                $('#button-group-div-'+product_store_id).html(<?php echo "'".$this->Html->image('ajax-loader.gif', ['style' => 'width: 54px;', 'class' => 'loader', 'id' => 'loading-facing'])."'";?>);

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
    
<?php else:?>

	<div class="row">
        <div class="col-md-12">
            <div class="alert alert-warning">
                <?php echo __('No data');?>
            </div>
        </div>
    </div>

<?php endif;?>
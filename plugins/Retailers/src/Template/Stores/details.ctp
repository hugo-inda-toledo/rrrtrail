<?php echo $this->Html->script('https://maps.googleapis.com/maps/api/js?key=AIzaSyDjb3YLZVorbujhYh9NkXO5WbSSViAbMk8&;sensor=false');?>
<?php echo $this->Html->css('application/bower_components/bootstrap-datetimepicker.min.css') ?>
<?php echo $this->Html->css('theme-zippedi/libs/bootstrap-datepicker/datepicker3.css?1424887858') ?>



<?php echo $this->Html->css([
        'theme-zippedi/libs/morris/morris.core.css?1420463396',
        'theme-zippedi/libs/rickshaw/rickshaw.css?1422792967'
    ]);?>
<?php $this->assign('title', __('[{0}] {1} - {2}', [$store_data->store_code, $store_data->company->company_name, $store_data->store_name]));?>
<style>

#map_canvas {
    border-top:0px solid #fff;
    border-bottom:0px solid #fff;
    height: 220px;
    width: 100%;
}

</style>


<section>
    <div class="section-header">
        <ol class="breadcrumb">
            <li>
                <?php echo $this->Html->link(__('Map'), ['controller' => 'Stores', 'action' => 'map']); ?>
            </li>
            <li class="active"><?php echo __('Store overview');?></li>
        </ol>
    </div>
    <div class="section-body">
        <div class="card">

            
            <div class="card-head style-<?php echo $store_data->company->company_class;?>">
                <div class="tools pull-left">
                    <!--<form class="navbar-search" role="search">
                        <div class="form-group">
                            <input type="text" class="form-control" name="contactSearch" placeholder="Enter your keyword">
                        </div>
                        <button type="submit" class="btn btn-icon-toggle ink-reaction"><i class="fa fa-search"></i></button>
                    </form>-->
                    <?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'glyphicon glyphicon-arrow-left fa-2x', 'style' => 'margin: 8px;']), ['controller' => 'Stores', 'action' => 'map', 'plugin' => 'Retailers'], ['class' => 'btn btn-flat hidden-xs', 'escape' => false]);?>
                </div>
                <!--<div class="tools">
                    <a class="btn btn-flat hidden-xs" href="../../../html/pages/contacts/search.html"><span class="glyphicon glyphicon-arrow-left"></span> &nbsp;Back to search results</a>
                </div>-->
            </div>

            <!-- BEGIN CONTACT DETAILS -->
            <div class="card-tiles">
                <div class="hbox-md col-md-12">
                    <div class="hbox-column col-md-9">
                        <div class="row">

                            <!-- BEGIN CONTACTS MAIN CONTENT -->
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                
                                <div class="row">
                                    <div class="col-sm-7">
                                        <div class="margin-bottom-xxl">
                                            <div class="pull-left width-3 clearfix hidden-xs">
                                                <?php echo $this->Html->image('companies/'.$store_data->company->company_logo, ['alt' => $store_data->company->company_name.' '.$store_data->store_name, 'class' => 'size-2']);?>
                                            </div>
                                            <h1 class="text-light no-margin">
                                                <?php echo __('[{0}] {1} - {2}', [$store_data->store_code, $store_data->company->company_name, $store_data->store_name]); ?>
                                            </h1>
                                            <h5>
                                                <?php echo __('{0}. {1}, {2}.', [$store_data->location->commune->commune_name, $store_data->location->region->region_name, $store_data->location->country->country_name]);?>
                                            </h5>
                                            &nbsp;&nbsp;
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="alert alert-info">
                                            <?php echo $this->Html->tag('i', '', ['class' => 'fa fa-info']).' '.__('Viewing data from the last session: {0}', $this->Html->tag('strong', $robot_sessions[0]['session_date']->format('d-m-Y H:i:s')));?>
                                        </div>
                                    </div>
                                </div>
                                    


                                <ul class="nav nav-tabs" data-toggle="tabs">
                                    <li class="active">
                                        <a href="#summary">
                                            <?php echo __('Summary');?>
                                        </a>
                                    </li>
                                    <!--<li>
                                        <a href="#stats">
                                            <?php //echo __('Stats');?>
                                        </a>
                                    </li>-->
                                    <li>
                                        <a href="#reports">
                                            <?php echo __('Reports');?>
                                        </a>
                                    </li>
                                    
                                </ul>
                                <div class="tab-content">

                                    <br>
                                    <div class="tab-pane active" id="summary">

                                        <?php echo $this->element('retailers_stores_details_summary', ['store_data' => $store_data, 'data' => $data]);?>
                                    </div>

                                    <!--<div class="tab-pane" id="stats">
                                        
                                        <?php //echo $this->element('retailers_stores_details_stats', ['store_data' => $store_data, 'data' => $data]);?>

                                    </div>-->

                                    <div class="tab-pane" id="reports">
                                        
                                        <?php echo $this->element('retailers_stores_details_reports', ['store_data' => $store_data, 'data' => $data]);?>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BEGIN CONTACTS COMMON DETAILS -->
                    <div class="hbox-column col-md-3 style-default-light">
                        <div class="row">
                            <div class="col-xs-12">
                                <dl class="dl-horizontal dl-icon">
                                    
                                    <dt><span class="fa fa-fw fa-calendar fa-lg opacity-50"></span></dt>
                                    <dd>
                                        <span class="opacity-50"><?php echo __('Calendar');?></span><br/>
                                        <span class="text-medium">
                                            <div id="store-session-calendar" class="pull-left"></div>
                                        </span>
                                        
                                    </dd>
                                    <div id="session-select-div">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group" id="session_select-div">
                                                    <label><?php echo __('Sessions');?> 
                                                        <span class="label label-danger"><?php echo __('Required');?></span>
                                                    </label>
                                                    <select name="robot_session_id" id="robot_session_id" class="form-control">
                                                        <option value="<?php echo $robot_sessions[0]->id; ?>"><?php echo $robot_sessions[0]->session_date->format('Y-m-d H:i:s'); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <dt><span class="fa fa-fw fa-external-link-square fa-lg opacity-50"></span></dt>
                                    <dd>
                                        <span class="opacity-50"><?php echo __('Shortcuts');?></span><br/>
                                        <span class="text-medium">
                                            <?php echo $this->Html->link(
                                                $this->Html->tag('strong', __('Assortment Report'), ['class' => 'text-default-dark']), ['controller' => 'RobotReports', 'action' => 'assortment_report', 'plugin' => 'Retailers'], ['class' => 'btn btn-default btn-sm btn-block', 'escape' => false, 'data-toggle' => 'tooltip', 'data-placement' => 'left', 'data-original-title' => __('Tooltip on left')]); ?>
                                            <?php echo $this->Html->link($this->Html->tag('strong', __('Price Difference Report'), ['class' => 'text-default-dark']), ['controller' => 'RobotReports', 'action' => 'price_difference_report', 'plugin' => 'Retailers'], ['class' => 'btn btn-default btn-sm btn-block', 'escape' => false, 'data-toggle' => 'tooltip', 'data-placement' => 'left', 'data-original-title' => __('Tooltip on left')]); ?>
                                            <?php echo $this->Html->link($this->Html->tag('strong', __('Stock Alert Report'), ['class' => 'text-default-dark']), ['controller' => 'RobotReports', 'action' => 'stock_out_report', 'plugin' => 'Retailers'], ['class' => 'btn btn-default btn-sm btn-block', 'escape' => false, 'data-toggle' => 'tooltip', 'data-placement' => 'left', 'data-original-title' => __('Tooltip on left')]); ?>
                                        </span>
                                    </dd>



                                    <dt><span class="fa fa-fw fa-location-arrow fa-lg opacity-50"></span></dt>
                                    <dd>
                                        <span class="opacity-50"><?php echo __('Address');?></span><br/>
                                        <span class="text-medium">
                                            <?php echo $this->Html->tag('strong', $store_data->location->street_name.' '.$store_data->location->street_number.($store_data->location->complement != null ? ' '.$store_data->location->complement : '.'));?><br/>
                                            <?php echo $store_data->location->commune->commune_name.' '.$store_data->location->region->region_name;?><br/>
                                            <?php echo $store_data->location->country->country_name;?>
                                        </span>
                                    </dd>
                                    <dd class="full-width">
                                        <div id="map_canvas" class="border-white border-xl height-5 text-center">
                                            <?php echo $this->Html->image('ajax-loader.gif', ['style' => 'width: 125px;margin-top: 50px;']);?>
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!--<pre>
<?php //print_r($robot_session_calendar);?>
</pre>-->

<?php echo $this->Html->script('theme-zippedi/libs/jquery/jquery-1.11.2.min.js');?>

<?php echo $this->Html->script([
    'theme-zippedi/libs/raphael/raphael-min.js',
    'theme-zippedi/libs/morris.js/morris.min.js',
]);?>

<?php echo  $this->Html->script('application/bower_components/moment.min.js', ['block' => 'scriptBottom']);?>
<?php echo  $this->Html->script('application/bower_components/bootstrap-datetimepicker.min.js', ['block' => 'scriptBottom']);?>

<?php $this->Html->scriptStart(array('block' => 'scriptBottom', 'inline' => false)); ?>
    
    $(document).ready(function(){

        <?php if(count($robot_session_calendar) > 0):?>
            var x=0;
            var dates = [];

            <?php foreach($robot_session_calendar as $key => $value):?>
                dates[x] = '<?php echo $value;?>';
                x = x+1;
            <?php endforeach;?>


            $('#store-session-calendar').datetimepicker({
                inline: true,
                sideBySide: false,
                format: "YYYY-MM-DD",
                enabledDates: dates,
                defaultDate: "<?php echo $robot_sessions[0]->session_date->format('Y-m-d');?>"
            });

        <?php else:?>
            $('#store-session-calendar').datetimepicker({
                inline: true,
                sideBySide: false,
                format: "YYYY-MM-DD",
            });
        <?php endif;?>

        

        $('#store-session-calendar').on('dp.change', function(e){ 

            var date_string = e.date.format('YYYY-MM-DD');

            $('#session-select-div').html('<div class="text-center"><?php echo $this->Html->image('ajax-loader.gif', ['style' => 'width:64px;']);?></div>');

            $.ajax({
                url: webroot + 'robot-sessions/getSessionsByDate/'+ <?php echo $store_data->id; ?> + '/null/' + date_string,
                cache: false,
                type: 'POST',
                dataType: 'json',
                success: function (response) 
                {
                    console.log(response);

                    var filtro = '';
                    if (response.status == true) {

                        filtro = '<form id="session-form"><div class="row"><div class="col-sm-12"><div class="form-group" id="session_select-div"><label><?php echo __('Sessions');?> <?php echo $this->Html->tag('span', __('Required'), ['class' => 'label label-danger']);?></label>';
                        filtro += '<select name="robot_session_id" id="robot_session_id" class="form-control">';
                        
                        quantity = 0;
                        $.each(response.data.sessions, function(key, value) {
                            quantity = quantity + 1;
                        });


                        if(quantity == 1){
                            $.each(response.data.sessions, function(key, value) {
                                filtro += '<option value="'+key+'">'+value+'</option>';
                            });
                        }
                        else{
                            filtro += '<option value="" selected><?php echo __('Select a session');?></option>';

                            $.each(response.data.sessions, function(key, value) {
                                filtro += '<option value="'+key+'">'+value+'</option>';
                            });
                        }
                        
                        filtro += '</select><input name="store_id" type="hidden" value="<?php echo $store_data->id;?>"></input></div></div><div class="col-sm-12 text-center"><?php echo $this->Form->button(__('Update'), ['type' => 'button', 'class' => 'btn btn-danger btn-xs', 'id' => 'generate-button']);?></div></div></form>';

                        $('#session-select-div').html(filtro);


                        $('#button-div').fadeIn(200);
                    }
                    else{
                        $('#button-div').fadeOut(300);
                        $('#session-select-div').html('');
                    }
                }
            });
        });

        var map = new google.maps.Map(document.getElementById('map_canvas'), {
            center: {lat: <?php echo $store_data->location->latitude?>, lng: <?php echo $store_data->location->longitude?>},
            zoom: 15,
            zoomControl: true,
            fullscreenControl: false,
            streetViewControl: false,
            mapTypeControl: false,
            rotateControl: false,
            scaleControl: false,
            scrollwheel: false,
            disableDoubleClickZoom: true,
            draggable: true,
        });

        var marker = new google.maps.Marker({
            position: {lat: <?php echo $store_data->location->latitude?>, lng: <?php echo $store_data->location->longitude?>},
            map: map,
            animation: google.maps.Animation.DROP,
            title: 'Hello World!',
        });

        var infoWindow = new google.maps.InfoWindow({map: map});

        var labelColor = $('#morris-area-graph').css('color');

        Morris.Area({
            element: 'morris-area-graph',
            behaveLikeLine: true,
            parseTime: false,
            data: [

                <?php $ymax_value = 0;?>
                <?php foreach($data['summary']['global_chart'] as $value):?>
                    {x: '<?php echo $value['datetime'];?>', y: <?php echo ($value['all_seen_labels'] != null) ? $value['all_seen_labels'] : 0;?>, z: <?php echo ($value['labels_with_price_difference'] != null) ? $value['labels_with_price_difference'] : 0;?>, a: <?php echo ($value['detections_with_stock_alert'] != null) ? $value['detections_with_stock_alert'] : 0;?>},

                    <?php 
                        if($value['labels_with_price_difference'] > $ymax_value || $value['detections_with_stock_alert'] > $ymax_value){

                            if($value['labels_with_price_difference'] > $value['detections_with_stock_alert']){
                                $ymax_value = $value['labels_with_price_difference'];
                            }
                            else{
                                $ymax_value = $value['detections_with_stock_alert'];
                            }
                        }
                    ?>
                <?php endforeach;?>
                <?php $ymax_value = $ymax_value + 50; ?>
            ],
            xkey: 'x',
            ykeys: ['y', 'z', 'a'],
            labels: ['<?php echo __("Seen labels")?>', '<?php echo __("Price differences")?>', '<?php echo __("Stock alerts")?>'],
            gridTextColor: labelColor,
            lineColors: $('#morris-area-graph').data('colors').split(','),
            ymax: <?php echo $ymax_value;?>,
            hideHover: true
        });


        Morris.Donut({
            element: 'donut-today-session',
            data: [
                {label: "<?php echo __('Readed labels');?>", value: <?php echo $robot_sessions[0]->total_detections;?>},
                {label: "<?php echo __('Price differences');?>", value: <?php echo $robot_sessions[0]->total_price_difference_detections;?>},
                {label: "<?php echo __('Stocks alerts');?>", value: <?php echo $robot_sessions[0]->total_stock_alert_detections;?>},
            ],
            colors: $('#donut-today-session').data('colors').split(',')
        });

    });

    function showTestDate(){
        alert('pesco');
    }

<?php $this->Html->scriptEnd(); ?>

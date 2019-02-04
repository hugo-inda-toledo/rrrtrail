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
            <li class="active"><?php echo __('Store Overview');?></li>
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
                    <?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'glyphicon glyphicon-arrow-left fa-2x', 'style' => 'margin: 8px;']), ['controller' => 'Stores', 'action' => 'map'], ['class' => 'btn btn-flat hidden-xs', 'escape' => false]);?>
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
                                                <!--<img class="img-circle size-2" src="../../../assets/img/avatar7.jpg?1404026721" alt="" />-->

                                                <?php echo $this->Html->image('companies/'.$store_data->company->company_logo, ['alt' => $store_data->company->company_name.' '.$store_data->store_name, 'class' => 'img-circle size-2']);?>
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
                                            <?php echo $this->Html->tag('i', '', ['class' => 'fa fa-info']).' '.__('Viewing data from the last session: {0}', $this->Html->tag('strong', $robot_session_data->session_date->format('d-m-Y H:i:s')));?>
                                        </div>
                                    </div>
                                </div>
                                    


                                <ul class="nav nav-tabs" data-toggle="tabs">
                                    <!--<li><a href="#notes">NOTES</a></li>
                                    <li><a href="#activity">ACTIVITY</a></li>-->
                                    <li class="active"><a href="#details">DETAILS</a></li>
                                </ul>
                                <div class="tab-content">

                                    <!-- BEGIN CONTACTS NOTES -->
                                    <div class="tab-pane" id="notes">
                                        <br/>
                                        <form class="form" id="formNote" accept-charset="utf-8" method="post">
                                            <span class="opacity-50">Add a note</span>
                                            <div class="form-group">
                                                <textarea id="summernote" name="message" class="form-control control-3-rows" placeholder="Enter note ..." spellcheck="false"></textarea>
                                            </div>
                                            <div class="form-group clearfix">
                                                <button type="submit" class="btn btn-raised btn-default-light pull-right">Add this note</button>
                                            </div>
                                        </form>
                                        <div class="list-results list-results-underlined">
                                            <div class="col-xs-12">
                                                <p class="clearfix">
                                                    <span class="fa fa-fw fa-file-o fa-2x pull-left"></span>
                                                    <span class="pull-left">
                                                        <span class="text-bold">Saturday, Oktober 18, 2014</span><br/>
                                                        <span class="opacity-50">Note by Daniel Johnson.</span>
                                                    </span>
                                                </p>
                                                <div>
                                                    <em>"It looks like he wanted our help and there is an opening here."</em>
                                                </div>
                                            </div><!--end .col -->
                                            <div class="col-xs-12">
                                                <p class="clearfix">
                                                    <span class="fa fa-fw fa-envelope-o fa-2x pull-left"></span>
                                                    <span class="pull-left">
                                                        <span class="text-bold">Tuesday, Juli 08, 2011</span><br/>
                                                        <span class="opacity-50">Email via Ann Laurens.</span><br/>
                                                        <span class="opacity-50">Subject: Can we meet tomorrow and come to a decision?</span>
                                                    </span>
                                                </p>
                                                <div>
                                                    <p>
                                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                                    </p>
                                                    <p>
                                                        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                                    </p>
                                                    <p>
                                                        Curabitur pretium tincidunt lacus. Nulla gravida orci a odio. Nullam varius, turpis et commodo pharetra..
                                                    </p>
                                                </div>
                                            </div><!--end .col -->
                                            <div class="col-xs-12">
                                                <p class="clearfix">
                                                    <span class="fa fa-fw fa-file-o fa-2x pull-left"></span>
                                                    <span class="pull-left">
                                                        <span class="text-bold">Wednesday, May 28, 2014</span><br/>
                                                        <span class="opacity-50">Note by Daniel Johnson.</span>
                                                    </span>
                                                </p>
                                                <div>
                                                    <em>There should be a meeting scheduled soon.</em>
                                                </div>
                                            </div><!--end .col -->
                                        </div><!--end .list-results -->
                                    </div><!--end #notes -->
                                    <!-- END CONTACTS NOTES -->

                                    <!-- BEGIN CONTACTS ACTIVITY -->
                                    <div class="tab-pane" id="activity">
                                        <form class="form" id="formFilter" accept-charset="utf-8" method="post">
                                            <br/>
                                            <div class="text-center">
                                                <label class="checkbox-inline checkbox-styled checkbox-default">
                                                    <input type="checkbox" value="option1" checked><span>System alerts</span>
                                                </label>
                                                <label class="checkbox-inline checkbox-styled checkbox-primary">
                                                    <input type="checkbox" value="option2" checked><span>Social activity</span>
                                                </label>
                                                <label class="checkbox-inline checkbox-styled checkbox-default-dark">
                                                    <input type="checkbox" value="option3" checked><span>Event</span>
                                                </label>
                                            </div>
                                            <br/>
                                        </form>
                                        <hr class="no-margin"/>
                                        <ul class="timeline collapse-md">
                                            <li class="timeline-inverted">
                                                <div class="timeline-circ"></div>
                                                <div class="timeline-entry">
                                                    <div class="card style-default-light">
                                                        <div class="card-body small-padding">
                                                            <img class="img-circle img-responsive pull-left width-1" src="../../../assets/img/avatar9.jpg?1404026744" alt="" />
                                                            <span class="text-medium">Received a <a class="text-primary" href="../../../html/mail/inbox.html">message</a> from <span class="text-primary">Ann Lauren</span></span><br/>
                                                            <span class="opacity-50">
                                                                Saturday, Oktober 18, 2014
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="timeline-circ"></div>
                                                <div class="timeline-entry">
                                                    <div class="card style-default-light">
                                                        <div class="card-body small-padding">
                                                            <img class="img-circle img-responsive pull-left width-1" src="../../../assets/img/avatar7.jpg?1404026721" alt="" />
                                                            <span class="text-medium">User login at <span class="text-primary">8:15 pm</span></span><br/>
                                                            <span class="opacity-50">
                                                                Saturday, August 2, 2014
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="timeline-inverted">
                                                <div class="timeline-circ style-default-dark"></div>
                                                <div class="timeline-entry">
                                                    <div class="card style-default-dark">
                                                        <div class="card-body small-padding">
                                                            <img class="img-circle img-responsive pull-left width-1" src="../../../assets/img/avatar7.jpg?1404026721" alt="" />
                                                            <span class="text-medium">Meeting in the <span class="text-primary">conference room</span></span><br/>
                                                            <span class="opacity-50">
                                                                Saturday, Juli 29, 2014
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="timeline-circ circ-xl style-accent"><span class="glyphicon glyphicon-upload"></span></div>
                                                <div class="timeline-entry">
                                                    <div class="card style-primary">
                                                        <div class="card-body small-padding">
                                                            <p><img class="img-circle img-responsive pull-left width-1" src="../../../assets/img/avatar5.jpg?1404026513" alt="" />
                                                            <span class="text-medium">Contacted <a class="text-primary-dark" href="../../../html/mail/inbox.html">Mabel Logan</a></span><br/>
                                                            <span class="opacity-50">
                                                                Saturday, Juli 23, 2014
                                                            </span>
                                                        </p>
                                                        <em>
                                                            Can you send me the latest updates? Then I can see the new colors for the themes.
                                                        </em>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="timeline-inverted">
                                            <div class="timeline-circ circ-lg"><span class="glyphicon glyphicon-plus-sign"></span></div>
                                            <div class="timeline-entry">
                                                <div class="card style-default-light">
                                                    <div class="card-body small-padding">
                                                        <img class="img-circle img-responsive pull-left width-1" src="../../../assets/img/avatar7.jpg?1404026721" alt="" />
                                                        <span class="text-medium">User registered on website</span><br/>
                                                        <span class="opacity-50">
                                                            Saturday, March 2, 2014
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul><!--end .timeline -->
                                </div><!--end #activity -->
                                <!-- END CONTACTS ACTIVITY -->

                                <!-- BEGIN CONTACTS DETAILS -->
                                <div class="tab-pane active" id="details">
                                    
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="card">
                                                        <div class="card-body no-padding">
                                                            <div class="alert alert-callout alert-success no-margin">
                                                                <strong class="pull-right text-success text-lg">0,38% <i class="md md-trending-up"></i></strong>
                                                                <strong class="text-xl"><?php echo (isset($data['reports']['assortment']['numbers_stats']['readed_products'])) ? $data['reports']['assortment']['numbers_stats']['readed_products'] : '';?></strong><br/>
                                                                <span class="opacity-50"><?php echo __('Seen products');?></span>
                                                                <div class="stick-bottom-left-right">
                                                                    <div class="height-2 sparkline-revenue" data-line-color="#bdc1c1"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="card">
                                                        <div class="card-body no-padding">
                                                            <div class="alert alert-callout alert-danger no-margin">
                                                                <strong class="pull-right text-danger text-lg">0,38% <i class="md md-trending-up"></i></strong>
                                                                <strong class="text-xl"><?php echo (isset($data['reports']['assortment']['numbers_stats']['unreaded_products'])) ? $data['reports']['assortment']['numbers_stats']['unreaded_products'] : '';?></strong><br/>
                                                                <span class="opacity-50"><?php echo __('Not Seen products');?></span>
                                                                <div class="stick-bottom-left-right">
                                                                    <div class="height-2 sparkline-revenue" data-line-color="#bdc1c1"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div id="morris-area-graph" class="height-5" data-colors="#9C27B0,#0aa89e"></div>
                                                        </div><!--end .card-body -->
                                                    </div><!--end .card -->
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="card">
                                                        <div class="card-body no-padding">
                                                            <div class="alert alert-callout alert-info no-margin">
                                                                <strong class="pull-right text-success text-lg">0,38% <i class="md md-trending-up"></i></strong>
                                                                <strong class="text-xl">$ 32,829</strong><br/>
                                                                <span class="opacity-50">Revenue</span>
                                                                <div class="stick-bottom-left-right">
                                                                    <div class="height-2 sparkline-revenue" data-line-color="#bdc1c1"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="card">
                                                        <div class="card-body no-padding">
                                                            <div class="alert alert-callout alert-warning no-margin">
                                                                <strong class="pull-right text-warning text-lg">0,01% <i class="md md-swap-vert"></i></strong>
                                                                <strong class="text-xl">432,901</strong><br/>
                                                                <span class="opacity-50">Visits</span>
                                                                <div class="stick-bottom-right">
                                                                    <div class="height-1 sparkline-visits" data-bar-color="#e5e6e6"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                

                            </div><!--end .tab-content -->
                        </div><!--end .col -->
                        <!-- END CONTACTS MAIN CONTENT -->

                    </div><!--end .row -->
                </div><!--end .hbox-column -->

                <!-- BEGIN CONTACTS COMMON DETAILS -->
                <div class="hbox-column col-md-3 style-default-light">
                    <div class="row">
                        <div class="col-xs-12">
                            <dl class="dl-horizontal dl-icon">
                                
                                <dt><span class="fa fa-fw fa-calendar fa-lg opacity-50"></span></dt>
                                <dd>
                                    <span class="opacity-50"><?php echo __('Calendar');?></span><br/>
                                    <span class="text-medium">
                                        <div id="store-session-calendar"></div>
                                    </span>
                                </dd>


                                <dt><span class="fa fa-fw fa-external-link-square fa-lg opacity-50"></span></dt>
                                <dd>
                                    <span class="opacity-50"><?php echo __('Shortcuts');?></span><br/>
                                    <span class="text-medium">
                                        <?php echo $this->Html->link(
                                            $this->Html->tag('strong', __('Assortment Report'), ['class' => 'text-default-dark']), ['controller' => 'RobotReports', 'action' => 'assortment_report'], ['class' => 'btn btn-default btn-sm btn-block', 'escape' => false, 'data-toggle' => 'tooltip', 'data-placement' => 'left', 'data-original-title' => __('Tooltip on left')]); ?>
                                        <?php echo $this->Html->link($this->Html->tag('strong', __('Price Difference Report'), ['class' => 'text-default-dark']), ['controller' => 'RobotReports', 'action' => 'price_difference_report'], ['class' => 'btn btn-default btn-sm btn-block', 'escape' => false, 'data-toggle' => 'tooltip', 'data-placement' => 'left', 'data-original-title' => __('Tooltip on left')]); ?>
                                        <?php echo $this->Html->link($this->Html->tag('strong', __('Stock Alert Report'), ['class' => 'text-default-dark']), ['controller' => 'RobotReports', 'action' => 'stock_out_report'], ['class' => 'btn btn-default btn-sm btn-block', 'escape' => false, 'data-toggle' => 'tooltip', 'data-placement' => 'left', 'data-original-title' => __('Tooltip on left')]); ?>
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
                                    <div id="map_canvas" class="border-white border-xl height-5">
                                        <?php echo $this->Html->image('ajax-loader.gif', ['style' => 'width:125px;margin: 200px;']);?>
                                    </div>
                                </dd>
                            </dl>
                        </div><!--end .col -->
                    </div><!--end .row -->
                </div><!--end .hbox-column -->
                <!-- END CONTACTS COMMON DETAILS -->

                </div><!--end .hbox-md -->
            </div><!--end .card-tiles -->
        <!-- END CONTACT DETAILS -->

        </div><!--end .card -->
    </div><!--end .section-body -->
</section>

<!--<pre>
<?php //print_r($data);?>
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

        $('#store-session-calendar').datetimepicker({
            inline: true,
            sideBySide: false
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

        /*Morris.Area({
            element: 'morris-area-graph',
            behaveLikeLine: true,
            parseTime: false,
            data: [

                <?php foreach($robot_sessions as $robot_session):?>
                    {x: '<?php echo $robot_session->session_date->format("d-m-Y");?>', y: <?php echo ($robot_session->total_price_difference_products != null) ? $robot_session->total_price_difference_products : 0;?>, z: <?php echo ($robot_session->total_stock_alert_detections != null) ? $robot_session->total_stock_alert_detections : 0;?>},
                <?php endforeach;?>
            ],
            xkey: 'x',
            ykeys: ['y', 'z'],
            labels: ['<?php echo __("Price Differences")?>', '<?php echo __("Stock Alerts")?>'],
            gridTextColor: labelColor,
            lineColors: $('#morris-area-graph').data('colors').split(',')
        });*/
    });

<?php $this->Html->scriptEnd(); ?>

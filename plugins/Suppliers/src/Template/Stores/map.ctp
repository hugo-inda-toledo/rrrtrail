<?php echo $this->Html->script('https://maps.googleapis.com/maps/api/js?key=AIzaSyDjb3YLZVorbujhYh9NkXO5WbSSViAbMk8&;sensor=false');?>
<?php $this->Html->scriptStart(array('block' => 'scriptBottom', 'inline' => false)); ?>
    
    $(document).ready(function(){

        var arrMarkers = {};
        var arrInfo = {};

        var map = new google.maps.Map(document.getElementById('map_canvas'), {
            center: {lat: -33.436885, lng: -70.634537},
            zoom: 10,
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

        <?php if(count($stores_data) > 0):?>
            
            <?php foreach($stores_data as $store):?>

                

                var marker_<?php echo $store->id;?> = new google.maps.Marker({
                    position: {lat: <?php echo $store->location->latitude?>, lng: <?php echo $store->location->longitude?>},
                    map: map,
                    animation: google.maps.Animation.DROP,
                    title: '<?php echo __('[{0}] {1} - {2}', [$store->store_code, $store->company->company_name, $store->store_name]);?>',
                    storeId: <?php echo $store->id;?>
                });

                arrMarkers[<?php echo $store->id;?>] = marker_<?php echo $store->id;?>;


                var contentString_<?php echo $store->id;?> = '<div id="content"><ul class="chat">'+
                '<li class="left clearfix store-list-element" data-store-id="1">'+
                '<span class="chat-img pull-left">'+
                '<?php echo $this->Html->image('companies/'.$store->company->company_logo, ['style' => 'width: 50px;', 'alt' => $store->company->company_name.' '.$store->store_name]);?>'+
                '</span>'+
                '<div class="chat-body clearfix">'+
                '<div class="header">'+
                '<strong class="primary-font"><?php echo __('[{0}] {1} - {2}', [$store->store_code, $store->company->company_name, $store->store_name]);?></strong>'+
                '<div class="btn-group pull-right">'+
                '<button type="button" class="btn btn-default btn-xs dropdown-toggle pull-right" data-toggle="dropdown">'+
                '<i class="fa fa-chevron-down"></i>'+
                '</button>'+
                '<ul class="dropdown-menu slidedown">'+
                '<li>'+
                '<a href="#"><i class="fa fa-search fa-fw"></i> Details</a>'+
                '</li>'+
                '</ul>'+
                '</div>'+
                '</div>'+
                '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales.</p>'+
                '</div>'+
                '</li></ul></div>';

                var infowindow_<?php echo $store->id;?> = new google.maps.InfoWindow({
                  content: contentString_<?php echo $store->id;?>
                });
                
                arrInfo[<?php echo $store->id;?>] = infowindow_<?php echo $store->id;?>;

                marker_<?php echo $store->id;?>.addListener('click', function() {

                    $.each(arrInfo, function( key, value ) {
                        value.close();
                    });

                    map.setZoom(16);
                    map.setCenter(marker_<?php echo $store->id;?>.getPosition());

                    infowindow_<?php echo $store->id;?>.open(map, marker_<?php echo $store->id;?>);
                });

            <?php endforeach;?>
        <?php endif;?>

        var infoWindow = new google.maps.InfoWindow({map: map});

        // Try HTML5 geolocation.
        /*console.log(navigator.geolocation);
        if (navigator.geolocation) {
            
            navigator.geolocation.getCurrentPosition(function(position) {
                //alert('alert');
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                infoWindow.setPosition(pos);
                infoWindow.setContent('Location found.');
                map.setCenter(pos);
            }, function() {
                handleLocationError(true, infoWindow, map.getCenter());
            });
        } else {
            // Browser doesn't support Geolocation
            handleLocationError(false, infoWindow, map.getCenter());
        }*/

        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            //alert('alert no');
            infoWindow.setPosition(pos);
            infoWindow.setContent(browserHasGeolocation ?
                                  'Error: The Geolocation service failed.' :
                                  'Error: Your browser doesn\'t support geolocation.');
        }


        $('.store-list-element').click(function(){

            $.each(arrInfo, function( key, value ) {
                value.close();
            });
            var current_marker = arrMarkers[$(this).attr("data-store-id")];
            var current_windows = arrInfo[$(this).attr("data-store-id")];

            map.setZoom(16);
            map.setCenter(current_marker.getPosition());

            current_windows.open(map, current_marker);

        });
    });

<?php $this->Html->scriptEnd(); ?>

<?php
    use Cake\I18n\Time;
    use Cake\View\Helper\TimeHelper
?>

<script type="text/javascript">

    function toggleBounce() {
        if (marker.getAnimation() !== null) {
          marker.setAnimation(null);
        } else {
          marker.setAnimation(google.maps.Animation.BOUNCE);
        }
    }

    function hideMap()
    {
        //$('#map').slideUp();

        var options = { direction: 'right' };
        $('#map-container').toggle('slide', options, 500);


        $('#events-section').removeClass('events-list');

        $('#mapViewButton').html('Show Map');
        $('#mapViewButton').removeClass('btn-danger');
        $('#mapViewButton').addClass('btn-success');
        $('#mapViewButton').attr('onclick', 'showMap();');

        $('div#event-box').removeClass('col-md-12');
        $('div#event-box').addClass('col-md-6');

        $('#event-container').removeClass('col-md-7');
        $('#event-container').addClass('col-md-12');
    }

    function showMap()
    {
        //$('#map').slideDown();

        var options = { direction: 'right' };
        $('#map').toggle('slide', options, 500);
        $('#map').toggle('slide', options, 500);
        $('#events-section').addClass('events-list');

        $('#mapViewButton').html('Hide Map');
        $('#mapViewButton').removeClass('btn-success');
        $('#mapViewButton').addClass('btn-danger');
        $('#mapViewButton').attr('onclick', 'hideMap();');

        $('#event-box').removeClass('col-md-6');
        $('#event-box').addClass('col-md-12');

        $('#event-container').removeClass('col-md-12');
        $('#event-container').addClass('col-md-7');
    }
    
</script>

<style>
    .sidebar-nav-fixed {
        width:14%;
    }
    #map_canvas{
        height: 670px;
        width: 540px;
    }
</style>


<div class="row">
    <!--/span-->
    <div id="event-container" class="col-md-7">
        <section>
            <div class="section-header" style="height: 50px;padding: 5px 0;">
                <?php //echo $this->Form->button(__('Hide Map'), ['type' => 'button',  'onclick' => 'hideMap();', 'id' => 'mapViewButton', 'class' => 'btn btn-danger pull-right']);?>
                <?php //echo $this->Form->button(__d( 'application', 'Show Filters' ), ['class' => 'btn btn-default-dark pull-right', 'type' => 'button', 'onclick' => 'Javascript:showFilterBox();', 'id' => 'filter-button']); ?>
                
                <ol class="breadcrumb">
                    <li class="active"><?php echo __('Real Time Map');?></li>
                </ol>
            </div>
            <div class="section-body">
                <div id="events-wrapper" class="row">
                    <div class="card">

                        <!-- BEGIN SEARCH HEADER -->
                        <div class="card-head style-accent">
                            <div class="tools pull-left">
                                
                                <?php echo $this->Html->tag('h4', $this->Html->tag('i', '', ['class' => 'fa fa-map-marker fa-fw']).' '.__('My Stores'), ['style' => 'margin-top: 20px;margin-left: 10px;']); ?>
                                <!--<form class="navbar-search" role="search">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="contactSearch" placeholder="Enter your keyword">
                                    </div>
                                    <button type="submit" class="btn btn-icon-toggle ink-reaction"><i class="fa fa-search"></i></button>
                                </form>-->
                            </div>
                            <div class="tools">
                                <!--<a class="btn btn-floating-action btn-default-light" href="../../../html/pages/contacts/add.html"><i class="fa fa-plus"></i></a>-->
                            </div>
                        </div><!--end .card-head -->
                        <!-- END SEARCH HEADER -->

                        <!-- BEGIN SEARCH RESULTS -->
                        <div class="card-body pre-scrollable" style="max-height: 500px;">
                            <div class="row">

                                <div class="col-sm-12 col-md-12 col-lg-12">

                                    <!--<div class="margin-bottom-xxl">
                                        <span class="text-light text-lg">
                                            <?php echo __('{0} stores assigned', $this->Html->tag('strong', count($stores_data)));?>
                                        </span>
                                        <div class="btn-group btn-group-sm pull-right">
                                            <button type="button" class="btn btn-default-light dropdown-toggle" data-toggle="dropdown">
                                                <span class="glyphicon glyphicon-arrow-down"></span> Sort
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right animation-dock" role="menu">
                                                <li><a href="#">First name</a></li>
                                                <li><a href="#">Last name</a></li>
                                                <li><a href="#">Email address</a></li>
                                            </ul>
                                        </div>
                                    </div>-->
                                    <div class="list-results">
                                        <?php foreach($stores_data as $store):?>
                                            <div class="col-xs-12 col-lg-12 hbox-xs">
                                                <div class="hbox-column width-2">
                                                    <?php echo $this->Html->image('companies/'.$store->company->company_logo, ['alt' => $store->company->company_name.' '.$store->store_name, 'class' => 'img-responsive pull-left']);?>
                                                </div><!--end .hbox-column -->
                                                <div class="hbox-column v-top">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="clearfix">
                                                                <div class="col-lg-12 margin-bottom-lg">
                                                                    <?php 
                                                                        //echo $this->Html->link(__('[{0}] {1} - {2}', [$store->store_code, $store->company->company_name, $store->store_name]), ['controller' => 'Stores', 'action' => 'details', $store->store_code], ['class' => 'text-lg text-medium']);

                                                                        echo $this->Html->tag('h4', __('[{0}] {1} - {2}', [$store->store_code, $store->company->company_name, $store->store_name]), ['class' => 'text-lg text-medium']);
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix">
                                                                <div class="col-lg-12">
                                                                    <span class="opacity-75">
                                                                        <span class="glyphicon glyphicon-map-marker text-sm"></span> 
                                                                        &nbsp;<?php echo __('{0}', $this->Html->tag('strong', $store->location->street_name.' '.$store->location->street_number.($store->location->complement != null ? ' '.$store->location->complement : '.')))?>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix opacity-75">
                                                                <div class="col-md-12">
                                                                    &nbsp;<?php echo __('{0}', [$store->location->commune->commune_name]);?>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix opacity-75">
                                                                
                                                                <div class="col-md-12">
                                                                    &nbsp;<?php echo __('{0}, {1}', [$store->location->region->region_name, $store->location->country->country_name]);?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="row text-center" style="margin-right: 0px;">
                                                                <div class="col-sm-12">
                                                                    <?php if(isset($store->robot_sessions) && count($store->robot_sessions) > 0):?>    
                                                                        <div class="label label-default">
                                                                            <?php echo __('Last session: {0}', $store->robot_sessions[0]->session_date->format('d-m-Y H:i:s'));?>
                                                                        </div>
                                                                    <?php endif;?>
                                                                </div>

                                                                <div class="col-sm-12" style="margin-top: 5px;margin-bottom: -10px;">
                                                                    <?php if(isset($store->robot_sessions) && count($store->robot_sessions) > 0):?>
                                                                        <?php echo $this->Html->tag('strong', ($store->robot_sessions[0]->total_detections != null) ? $store->robot_sessions[0]->total_detections : __('No available'), ['class' => 'text-success', 'style' => 'font-size: 20px;']);?>
                                                                       <?php echo $this->Html->tag('h5', __('Readed labels'), ['style' => 'margin-top: -5px;']);?>
                                                                    <?php endif;?>
                                                                </div>
                                                                
                                                                <?php if(isset($store->robot_sessions) && count($store->robot_sessions) > 0 && $store->robot_sessions[0]->total_stock_alert_detections != null):?>
                                                                    <div class="col-sm-6">
                                                                        <?php echo $this->Html->tag('h4', ($store->robot_sessions[0]->total_stock_alert_detections != null) ? $store->robot_sessions[0]->total_stock_alert_detections : __('No available'), ['class' => 'text-danger', 'style' => 'font-size: 17px;']);?>
                                                                        <?php echo $this->Html->tag('h5', __('Stock Alerts'));?>
                                                                    </div>
                                                                <?php endif;?>
                                                            
                                                            
                                                                <?php if(isset($store->robot_sessions) && count($store->robot_sessions) > 0 && $store->robot_sessions[0]->total_price_difference_detections != null):?>
                                                                    <div class="col-sm-6">
                                                                       <?php echo $this->Html->tag('h4', ($store->robot_sessions[0]->total_price_difference_detections != null) ? $store->robot_sessions[0]->total_price_difference_detections : __('No available'), ['class' => 'text-info', 'style' => 'font-size: 17px;']);?>
                                                                       <?php echo $this->Html->tag('h5', __('Price Differences'));?>
                                                                    </div>
                                                                <?php endif;?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="stick-top-right small-padding" style="padding: 0px;">
                                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle store-list-element" data-store-id="<?php echo $store->id;?>">
                                                            <i class="fa fa-crosshairs"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach;?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    
    <!--/span-->
    <div id="map-container" class="col-md-offset-7 col-md-5 pull-right affix">
        
        <!--<div class="card ">
            <div class="card-body no-padding">-->
                <div id="map_canvas" class="border-gray" style=""></div>
            <!--</div>
        </div><--end .card -->
    </div>
    <!--/span-->
</div>

<!--<pre>
<?php print_r($stores_data);?>
</pre>-->



<script>
    $(document).ready(function() {
        $("[data-toggle=popover]").popover({
            html : true,
            content: function() {
              var content = $(this).attr("data-popover-content");
              return $(content).children(".popover-body").html();
            },
            title: function() {
              var title = $(this).attr("data-popover-content");
              return $(title).children(".popover-heading").html();
            }
        });
    });
    
    function showFilterBox()
    {
        if($('#filters-wrapper').css('display') == 'none')
        {
            $('#filters-wrapper').slideDown(300);
            $('#filter-button').html(<?php echo "'".__('Hide Filters')."'"; ?>);
        }
        else
        {
            $('#filters-wrapper').slideUp(300);
            $('#filter-button').html(<?php echo "'".__('Show Filters')."'"; ?>);
        }
    }
</script>
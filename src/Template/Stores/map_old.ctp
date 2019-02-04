<?php echo $this->Html->script('https://maps.googleapis.com/maps/api/js?key=AIzaSyDjb3YLZVorbujhYh9NkXO5WbSSViAbMk8&;sensor=false');?>

<style>

#map_canvas {
    border-top:0px solid #fff;
    border-bottom:20px solid #fff;
    height: 600px;
    width: 100%;
}

.store-list-li {
    cursor:pointer;
}

.store-list-li:hover {
    background-color: #e7e7e7;
    /*margin-top: -5px;*/
}

.store-list-element .btn-group {
    cursor:none;
}

.timeline:before {
    background-color: none;
}

</style>


<h3><?php echo __('Real-Time Stores Map');?></h3>

<div class="row">
	<div class="col-md-4">
        <div class="chat-panel panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-map-marker fa-fw"></i>
                <?php echo __('My Stores'); ?>
            </div>
            <div class="panel-body" style="height: 540px;padding: 5px;">
                <ul class="chat">
                	<?php foreach($stores_data as $store):?>
		                <li class="left clearfix store-list-li" >
		                    <span class="chat-img pull-left">
		                        <?php echo $this->Html->image('companies/'.$store->company->company_logo, ['alt' => $store->company->company_name.' '.$store->store_name, 'style' => 'width: 55px;']);?>
		                    </span>
		                    <div class="chat-body clearfix">
		                        <div class="header">
		                            <strong class="primary-font">
                                        <?php 
                                            echo $this->Html->link(__('[{0}] {1} - {2}', [$store->store_code, $store->company->company_name, $store->store_name]), ['controller' => 'Stores', 'action' => 'details', $store->store_code]);
                                        ?>        
                                    </strong> 
		                            <div class="btn-group pull-right">
                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle store-list-element" data-store-id="<?php echo $store->id;?>">
                                            <i class="fa fa-crosshairs"></i>
                                        </button>

					                    <button type="button" class="btn btn-default btn-xs dropdown-toggle pull-right" data-toggle="dropdown">
					                        <i class="fa fa-chevron-down"></i>
					                    </button>
					                    <ul class="dropdown-menu slidedown">
					                        <li style="border-bottom:none; margin-bottom:0px;">

					                            <?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-search fa-fw']).' '.__('Details'), ['controller' => 'Stores', 'action' => 'details', $store->id], ['escape' => false]);?>
					                        </li>
					                    </ul>
					                </div>
		                        </div>
		                        <p>
		                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales.
		                        </p>
		                    </div>
		                </li>
                	<?php endforeach;?>
                </ul>
            </div>
        </div>
	</div>
	<div class="col-md-8 text-center">
		<div id="map_canvas" class="map_canvas">
			<?php echo $this->Html->image('ajax-loader.gif', ['style' => 'width:125px;margin: 200px;']);?>
		</div>
	</div>
</div>


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
                    title: 'Hello World!',
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
    /*echo '<pre>';
    print_r($stores_data);
    echo '</pre>';

    echo '<pre>';
    print_r($users_suppliers_data);
    echo '</pre>';*/
?>
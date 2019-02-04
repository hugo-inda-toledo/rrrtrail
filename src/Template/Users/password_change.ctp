<?php echo $this->Html->script('https://maps.googleapis.com/maps/api/js?key=AIzaSyDjb3YLZVorbujhYh9NkXO5WbSSViAbMk8&;sensor=false');?>

<style>
.map_canvas {
    border-top: 0px solid #fff;
    border-bottom: 0px solid #fff;
    height: 160px;
    width: 100%;
}
</style>

<section class="section-account">
    <div class="card contain-lg style-transparent">
        <div class="card-body" style="padding: 0px;">
            <?php echo $this->Flash->render() ?>
            <div class="row">
                <div class="col-sm-12">
                	<?php echo $this->Html->image('onlyletters2.png', ['class' => 'img-responsive', 'style' => 'width: 160px;']);?>
                </div>
            </div>
            <div class="row">
            	<div class="col-sm-12">
                	<h3>
                		<?php echo __('Dear {0}, please validate the information that appears below.', ucwords(strtolower($user->name)));?>
                	</h3>
                	<h5>
                		<?php echo __('Then create a password of at least 6 characters to continue.');?>
                	</h5>
                </div>
            </div>
            <br>
            <?php echo $this->Form->create(null, array('type' => 'post', 'class' => 'form-vertical login-form', 'url' => ['controller' => 'Users', 'action' => 'passwordChange'], 'id' => 'loginForm')); ?>
	            <div class="row">
	                <div class="col-sm-6">
	                    <div class="card">
							<div class="card-head card-head-xs style-default-dark">
								<header><?php echo __('These are the stores that you will have access'); ?></header>
							</div><!--end .card-head -->
							<div class="card-body">
								
								<?php foreach($stores as $store_id => $data):?>
									<div class="well">
										<div class="row">
											<div class="col-sm-6">
												<div class="row">
													<!--<div class="col-sm-12">
														<?php //echo $this->Html->image('companies/'.$data['company']->company_logo, ['class' => 'text-left', 'style' => 'width:30px;']);?>
													</div>-->
													<div class="col-sm-12">
														<h5 class="text-left">
					                                        <?php 
					                                            echo $this->Html->tag('span', $this->Html->image('companies/'.$data['company']->company_logo, ['class' => 'text-left', 'style' => 'width:30px;']).' '.__('[{0}] {1} - {2}', [$data['store']->store_code, $data['company']->company_name, $data['store']->store_name]), ['escape' => false]);
					                                        ?>
					                                    </h5>
					                                    <ul class="list-unstyled text-left">
					                                        <li>
					                                            <?php echo __('{0}', $this->Html->tag('strong', $data['store']->location->street_name.' '.$data['store']->location->street_number.($data['store']->location->complement != null ? ' '.$data['store']->location->complement : '.')))?>
					                                        </li>
					                                        <li>
					                                            <?php echo __('{0}', $this->Html->tag('strong', $data['store']->location->commune->commune_name))?>
					                                        </li>
					                                        <li>
					                                            <?php echo __('{0}', $this->Html->tag('strong', $data['store']->location->region->region_name))?>
					                                        </li>
					                                        <li>
					                                            <?php echo __('{0}', $this->Html->tag('strong', $data['store']->location->country->country_name))?>
					                                        </li>
					                                    </ul>
													</div>
												</div>
												
											</div>
											<div class="col-sm-6">
												<div id="store_map_<?php echo $data['store']->id?>" class="map_canvas"></div>


												<?php $this->Html->scriptStart(array('block' => 'scriptBottom', 'inline' => false)); ?>

								                    $(document).ready(function(){

								                        var map_<?php echo $data['store']->id?> = new google.maps.Map(document.getElementById('store_map_<?php echo $data['store']->id?>'), {
								                            center: {lat: <?php echo $data['store']->location->latitude?>, lng: <?php echo $data['store']->location->longitude?>},
								                            zoom: 15,
								                            zoomControl: false,
								                            fullscreenControl: false,
								                            streetViewControl: false,
								                            mapTypeControl: false,
								                            rotateControl: false,
								                            scaleControl: false,
								                            scrollwheel: false,
								                            disableDoubleClickZoom: true,
								                            draggable: false,
								                        });

								                        var marker_<?php echo $data['store']->id?> = new google.maps.Marker({
								                            position: {lat: <?php echo $data['store']->location->latitude?>, lng: <?php echo $data['store']->location->longitude?>},
								                            map: map_<?php echo $data['store']->id?>,
								                            animation: google.maps.Animation.DROP,
								                            title: 'Hello World!',
								                        });

								                        var infoWindow = new google.maps.InfoWindow({map: map_<?php echo $data['store']->id?>});
								                    });

								                <?php $this->Html->scriptEnd(); ?>
											</div>
										</div>
	                                </div>
								<?php endforeach;?>
							</div><!--end .card-body -->
						</div>
	                </div><!--end .col -->
	                <div class="col-sm-6">
	                	<div class="card">
							<div class="card-head card-head-xs style-info">
								<header><?php echo __('Your personal information'); ?></header>
							</div><!--end .card-head -->
							<div class="card-body">

								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label><?php echo __('Name');?></label>
											<p class="form-control-static"><?php echo $user->name;?></p>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label><?php echo __('Last name');?></label>
											<p class="form-control-static"><?php echo $user->last_name;?></p>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											<label><?php echo __('Email');?></label>
											<p class="form-control-static"><?php echo $user->email;?></p>
										</div>
									</div>
								</div>
								<?php echo $this->form->hidden('password_token', ['value' => $user->password_token]); ?>
							</div>
						</div>

						<div class="card">
							<div class="card-head card-head-xs style-danger">
								<header><?php echo __('Create a password of at least 6 characters'); ?></header>
							</div><!--end .card-head -->
							<div class="card-body">
								<div class="alert alert-warning">
									<i class="fa fa-info-circle"></i>
									<?php echo __('It must contain at least one number and one capital letter');?>
								</div>
								<div class="form-group">

		                            <?php echo $this->Form->input('password', ['type' => 'password', 'class' => 'form-control', 'autofocus', 'id' => 'password', 'label' => false, 'required' => 'required']) ?>

		                            <label for="password"><?php echo __('Password');?></label>
		                        </div>
		                        <div class="form-group">
		                            <?php echo $this->Form->input('retype_password', ['type' => 'password', 'class' => 'form-control', 'id' => 'retype_password', 'label' => false, 'required' => 'required']) ?>
		                            <label for="retype_password"><?php echo __('Retype password');?></label>

		                        </div>
		                        <br/>
		                        <?php echo $this->Form->button(__('Change password'), ['class' => 'btn btn-primary btn-raised pull-right']); ?>
							</div>
						</div>
	                </div>
	            <?php echo $this->Form->end();?>
            </div>
        </div>
    </div>
</section>
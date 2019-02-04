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
                		<?php echo __('Dear {0}, please create a password of at least 6 characters to continue.', ucwords(strtolower($user->name)));?>
                	</h3>
                </div>
            </div>
            <br>
            <?php echo $this->Form->create(null, array('type' => 'post', 'class' => 'form-vertical login-form', 'url' => ['controller' => 'Users', 'action' => 'resetPasswordToken'], 'id' => 'loginForm')); ?>
	            <div class="row">
	                
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
								<?php echo $this->form->hidden('recovery_token', ['value' => $user->recovery_token]); ?>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
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
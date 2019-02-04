<?php //$this->viewBuilder()->setLayout('login_layout');?>
<?php $this->layout = 'login_layout';?>
<div class="row">
	<div class="col-lg-12 text-center">
		<?php echo $this->Html->image('new_zippedi_logo_horizontal.png', ['class' => 'img-responsive text-center', 'style' => 'width: 130px;float: none;margin: 0 auto;']); ?>
	</div>
</div>
<div class="row">
	<div class="col-lg-6">
		<?php echo $this->Html->image('robot-msg-error.png', ['style' => 'width:450px;', 'class' => 'img-responsive']);?>
	</div>
    <div class="col-lg-6">
        <div class="well-lg" style="margin-top: 100px;">
        	<h2><?php echo __('Page not found');?>.</h2>
        	<p><?php echo __("We're sorry, we couldn't find the page you requested");?>.</p>
        	<strong class="text-info"><?php echo __('Within a few seconds you will be redirected to the previous page');?>.</strong>
        </div>
    </div>
</div>

<?php $this->Html->scriptStart(array('block' => 'scriptBottom', 'inline' => false)); ?>

    $(document).ready(function(){
    	setTimeout(function(){
    		parent.history.back();
        	return false;
    	}, 3300);
    });

<?php $this->Html->scriptEnd(); ?>
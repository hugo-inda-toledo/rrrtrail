<?php if($this->request->param('plugin') != 'Management'):?>

	<?php echo 
		$this->Html->script([
		    'theme-zippedi/libs/jquery/jquery-1.11.2.min.js', 
		    'theme-zippedi/libs/jquery/jquery-migrate-1.2.1.min.js',
		    'theme-zippedi/libs/toastr/toastr.js'
		]);
	?>

	<?php echo $this->Html->css([
	        'theme-zippedi/libs/toastr/toastr.css?1425466569'
	    ]); ?>
	<script>
		$(document).ready(function() {
	    	toastr.options.timeOut = 4000; // 1.5s
	    	toastr.options.positionClass = 'toast-top-full-width';
			toastr.options.closeButton = true;
	    	toastr.warning('<?php echo h($message) ?>');
	    });

	</script>

<?php else:?>

	<?php
	if (!isset($params['escape']) || $params['escape'] !== false) {
	    $message = h($message);
	}
	?>
	<div class="alert alert-warning" onclick="this.classList.add('hidden');" role="alert"><?php echo $message ?></div>
	
<?php endif;?>

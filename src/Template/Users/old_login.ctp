<!--<div class="row">
    <div class="col-md-4 col-md-offset-4 ">
    	<?php echo $this->Html->image('zippedi_logo.png', ['class' => 'img-responsive text-center pull-center']); ?>
    </div>
</div>-->
<div class="row">
    <div class="col-md-4 col-md-offset-4">
    	<?php echo $this->Html->image('onlyletters2.png', ['class' => 'img-responsive text-center', 'style' => 'width: 180px;float: none;margin: 0 auto;']); ?>
        <div class="login-panel panel panel-default" style="margin-top: 8%;">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo __('Please sign in');?></h3>
            </div>
            <div class="panel-body">
            	<?php echo $this->Form->create() ?>
                <!--<form role="form">-->
                    <fieldset>
                        <div class="form-group">
                            <!--<input class="form-control" placeholder="E-mail" name="email" type="email" autofocus>-->
                            <?php echo $this->Form->input('username', ['type' => 'email', 'class' => 'form-control', 'placeholder' => __('Email'), 'autofocus', 'id' => 'username', 'label' => __('Email')]) ?>
                        </div>
                        <div class="form-group">
                            <!--<input class="form-control" placeholder="Password" name="password" type="password" value="">-->
                            <?php echo $this->Form->input('password', ['type' => 'password', 'class' => 'form-control', 'placeholder' => __('Password'), 'id' => 'password']) ?>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input name="remember" type="checkbox" value="Remember Me"><?php echo __('Remember me');?>
                            </label>
                        </div>
                        <!-- Change this to a button or input when using this as a form -->
                        <!--<a href="index.html" class="btn btn-lg btn-success btn-block">Login</a>-->
                        <?php echo $this->Form->button(__('Login'), ['class' => 'btn btn-lg btn-danger btn-block']); ?>
                    </fieldset>
                <!--</form>-->
                <?php echo $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
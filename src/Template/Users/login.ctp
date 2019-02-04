<section class="section-account">
    <!--<div class="img-backdrop" style="background: #e2e2e2 url(img/portada_login.jpg) no-repeat 0px -500px;background-size: cover;"></div>-->
    <div class="img-backdrop" style="background-image: url('<?php echo $this->Url->build('/img/zi3.jpg');?>"></div>
    <div class="spacer"></div>
    <div class="card contain-sm style-transparent">
        <div class="card-body">
            <?php echo $this->Flash->render() ?>
            <div class="row">
                <div class="col-sm-6" id="login-div">
                    <br/>
                    
                    <?php echo $this->Html->image('onlyletters2.png', ['class' => 'img-responsive', 'style' => 'width: 160px;']);?>
                    <br/><br/>

                    <?php echo $this->Form->create(null, ['class' => 'form floating-label']) ?>
                        <div class="form-group">

                            <?php echo $this->Form->input('username', ['type' => 'email', 'class' => 'form-control', 'autofocus', 'id' => 'username', 'label' => false, 'required' => 'required']) ?>

                            <label for="username"><?php echo __('Email');?></label>
                        </div>
                        <div class="form-group">
                            <?php echo $this->Form->input('password', ['type' => 'password', 'class' => 'form-control', 'id' => 'password', 'label' => false, 'required' => 'required']) ?>
                            <label for="password">Password</label>
                            <p class="help-block">
                                <?php echo $this->Html->link(__('Forgot the password?'), 'javascript:void(0);', ['id' => 'recover-password-link']); ?>
                            </p>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-xs-6 text-left">
                                <div class="checkbox checkbox-inline checkbox-styled">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me"><?php echo __('Remember me');?>
                                    </label>
                                </div>
                            </div><!--end .col -->
                            <div class="col-xs-6 text-right">
                                <?php echo $this->Form->button(__('Login'), ['class' => 'btn btn-primary btn-raised']); ?>
                            </div><!--end .col -->
                        </div><!--end .row -->
                    <?php echo $this->Form->end() ?>
                </div><!--end .col -->
                <div class="col-sm-6" id="recovery-div" style="display:none;">
                    <br/>
                    
                    <?php echo $this->Html->image('onlyletters2.png', ['class' => 'img-responsive', 'style' => 'width: 160px;']);?>
                    

                    <?php echo $this->Form->create(null, ['class' => 'form floating-label', 'url' => ['controller' => 'Users', 'action' => 'recoverPassword']]) ?>

                        <div class="alert alert-warning">
                            <?php echo __('Write your email to request the reset of your password');?>
                        </div>

                        <div class="form-group">

                            <?php echo $this->Form->input('recover_email', ['type' => 'email', 'class' => 'form-control', 'id' => 'recover_email', 'label' => false, 'required' => 'required']) ?>

                            <label for="username"><?php echo __('Email');?></label>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-xs-12">
                                <?php echo $this->Form->button(__('Back'), ['type' => 'button', 'class' => 'btn btn-default btn-raised pull-left', 'id' => 'back-button']); ?>
                                <?php echo $this->Form->button(__('Send'), ['class' => 'btn btn-primary btn-raised pull-right']); ?>
                            </div><!--end .col -->
                        </div><!--end .row -->
                    <?php echo $this->Form->end() ?>
                </div>
            </div>
        </div><!--end .card-body -->
    </div><!--end .card -->
</section>


<?php $this->Html->scriptStart(array('block' => 'scriptBottom', 'inline' => false)); ?>

    $(document).ready(function(){
        $('#recover-password-link').on('click', function(){

            if($('#recovery-div').css('display') == 'none'){
                $('#recovery-div').show();
                $('#login-div').hide();
            }
            else{
                $('#login-div').show();
                $('#recovery-div').hide();
            }
        });

        $('#back-button').on('click', function(){

            if($('#recovery-div').css('display') == 'none'){
                $('#recovery-div').show();
                $('#login-div').hide();
            }
            else{
                $('#login-div').show();
                $('#recovery-div').hide();
                $('#recover_email').val('');
            }
        });
    });

<?php $this->Html->scriptEnd(); ?>
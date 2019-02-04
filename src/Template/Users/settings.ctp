<section>
	<div class="section-header">
		<ol class="breadcrumb">
			<li class="active"><?php echo __('Settings');?></li>
		</ol>
	</div>

	<?php echo $this->Form->create('null', ['url' => ['controller' => 'Users', 'action' => 'settings'], 'class' => 'form', 'id' => 'settings-form']);?>
		<div class="section-body contain-lg">
			<div class="row">
				<div class="col-lg-12">
					<h2 class="text-primary"><?php echo __('User settings');?></h2>
				</div><!--end .col -->
				<div class="col-lg-8">
					<p class="lead">
						<?php echo __('Here you can select the reports that you want to receive by email, also you can change your password');?>
					</p>
				</div>
				<div class="col-lg-4">
						<?php echo $this->Form->button(__('Validate changes'), ['type' => 'button', 'class' => 'btn btn-md btn-primary pull-right', 'id' => 'button-modal-submit', 'data-toggle' => 'modal', 'data-target' => '#confirm-changes-modal']);?>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-3 col-md-4">
					<div class="card">
						<div class="card-head style-warning">
							<header style="line-height: 30px;"><?php echo __('Change your password');?></header>
						</div>
						<div class="card-body">
							<div class="alert alert-warning">
								<i class="fa fa-info-circle"></i>
								<?php echo __('It must contain at least one number and one capital letter');?>
							</div>
							<div class="form-group">

	                            <?php echo $this->Form->input('Users.password', ['type' => 'password', 'class' => 'form-control', 'autofocus', 'id' => 'password', 'label' => false]) ?>

	                            <label for="password"><?php echo __('Password');?></label>
	                        </div>
	                        <div class="form-group">
	                            <?php echo $this->Form->input('Users.retype_password', ['type' => 'password', 'class' => 'form-control', 'id' => 'retype_password', 'label' => false]) ?>
	                            <label for="retype_password"><?php echo __('Retype password');?></label>
	                        </div>
						</div>
					</div>
				</div>
				<div class="col-lg-offset-1 col-md-8">
					<?php if(count($users_companies) > 0):?>
						<div class="card">
							<div class="card-head style-gray-dark">
								<header style="line-height: 30px;"><?php echo __('My stores (Retailer)');?></header>
							</div>
							<div class="card-body">
		                        <?php $x = 0;?>

		                        <div class="alert alert-info" style="font-size: 15px;">
                            		<i class="fa fa-envelope"></i>
                            		<?php echo __('Select the reports that you want to receive in your email');?>
                            	</div>

		                        <?php foreach($users_companies as $user_company):?>
		                            <div class="well">
		                                <div class="row">
		                                    <div class="col-sm-6">
		                                        

		                                        <div class="row">
													<div class="col-sm-12">
														<h5 class="text-left">
					                                        <?php 
					                                            echo $this->Html->tag('span', $this->Html->image('companies/'.$user_company->company->company_logo, ['class' => 'text-left', 'style' => 'width:30px;']).' '.__('[{0}] {1} - {2}', [$user_company->store->store_code, $user_company->company->company_name, $user_company->store->store_name]), ['escape' => false]);
					                                        ?>
					                                    </h5>
					                                    <ul class="list-unstyled text-left">
					                                        <li>
					                                            <?php echo __('{0}', $this->Html->tag('strong', $user_company->store->location->street_name.' '.$user_company->store->location->street_number.($user_company->store->location->complement != null ? ' '.$user_company->store->location->complement : '.')))?>
					                                        </li>
					                                        <li>
					                                            <?php echo __('{0}', $this->Html->tag('strong', $user_company->store->location->commune->commune_name))?>
					                                        </li>
					                                        <li>
					                                            <?php echo __('{0}', $this->Html->tag('strong', $user_company->store->location->region->region_name))?>
					                                        </li>
					                                        <li>
					                                            <?php echo __('{0}', $this->Html->tag('strong', $user_company->store->location->country->country_name))?>
					                                        </li>
					                                    </ul>
													</div>
												</div>
		                                    </div>

		                                    <div class="col-sm-6">
		                                        <?php foreach($robot_reports as $robot_report):?>

		                                            <?php 
		                                                $input_name = '';
		                                                switch ($robot_report->report_keyword) {
		                                                    case 'assortmentReport':
		                                                        $input_name = 'assortment_report';
		                                                        break;

		                                                    case 'priceDifferenceReport':
		                                                        $input_name = 'price_differences';
		                                                        break;

		                                                    case 'stockOutReport':
		                                                        $input_name = 'stock_alert';
		                                                        break;
		                                                
		                                                    default:    
		                                                        # code...
		                                                        break;
		                                            }?>

		                                            <?php $founded = 0;?>
		                                            <?php if(count($user_company->users_companies_robot_reports) > 0):?>

		                                                <?php foreach($user_company->users_companies_robot_reports as $user_company_robot_report):?>

		                                                    <?php if($user_company_robot_report->robot_report_id == $robot_report->id):?>

		                                                        <?php $founded = 1;?>
		                                                        <label class="checkbox-inline checkbox-styled"><input name="UsersCompaniesRobotReports[<?php echo $x;?>][<?php echo $robot_report->report_keyword;?>]" type="checkbox" value="<?php echo $robot_report->report_keyword;?>" checked><span><?php echo __($robot_report->report_name);?></span></label>

		                                                        <?php echo $this->Form->hidden('UsersCompaniesRobotReports.'.$x.'.id', ['value' => $user_company_robot_report->id]);?>
		                                                        <?php break;?>
		                                                    <?php endif;?>


		                                                <?php endforeach;?>

		                                                <?php if($founded == 0):?>
		                                                    <label class="checkbox-inline checkbox-styled"><input name="UsersCompaniesRobotReports[<?php echo $x;?>][<?php echo $robot_report->report_keyword;?>]" type="checkbox" value="<?php echo $robot_report->report_keyword;?>"><span><?php echo __($robot_report->report_name);?></span></label>
		                                                <?php endif;?>

		                                            <?php else:?>
		                                                <label class="checkbox-inline checkbox-styled"><input name="UsersCompaniesRobotReports[<?php echo $x;?>][<?php echo $robot_report->report_keyword;?>]" type="checkbox" value="<?php echo $robot_report->report_keyword;?>"><span><?php echo __($robot_report->report_name);?></span></label>
		                                            <?php endif;?>
		                                                
		                                            <?php echo $this->Form->hidden('UsersCompaniesRobotReports.'.$x.'.user_company_id', ['value' => $user_company->id]);?>
		                                            <?php echo $this->Form->hidden('UsersCompaniesRobotReports.'.$x.'.robot_report_id', ['value' => $robot_report->id]);?>
		                                            <?php $x++;?>
		                                        <?php endforeach;?>
		                                    </div>
		                                </div>
		                            </div>
		                        <?php endforeach;?>
							</div>
						</div>
					<?php endif;?>
				</div><!--end .col -->
			</div>
		</div>
	<?php echo $this->Form->end();?>
</section>



<div class="modal fade" id="confirm-changes-modal" tabindex="-1" role="dialog" aria-labelledby="assign-company-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="assign-company-modal-label"><?php echo __('Information');?></h4>
            </div>
            <div class="modal-body">
                <?php echo $this->Html->para(null, __('You bear in mind that the process of sending reports to your email starts from 05:30 until 14:00 hrs. This may be subject to future changes.'))?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <?php echo $this->Form->button($this->Html->tag('i', '', ['class' => 'fa fa-save']).' '.__('Save changes'), ['type' => 'button', 'onclick' => 'javascript:deleteUserSupplier();', 'class' => 'btn btn-danger pull-right', 'id' => 'submit-button', 'data-loading-text' => "<i class='fa fa-spinner fa-spin'></i> ".__('Processing')."..."]);?>
            </div>
        </div>
    </div>
</div>

<?php $this->Html->scriptStart(array('block' => 'scriptBottom', 'inline' => false)); ?>
    
    $(document).ready(function(){
    	$("#submit-button").click(function(){
    		$('#settings-form').submit();
    	});
    });

<?php $this->Html->scriptEnd(); ?>
<h3><?php echo __('Editing User');?></h3>
<?php echo $this->Form->create($user, ['url' => ['controller' => 'Users', 'action' => 'edit'], 'class' => 'form']);?>

    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-default content">
                <div class="panel-heading">
                    <?php echo __('User Information');?>
                </div>
                <div class="panel-body" id="users-data-div">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label><?php echo __('Name'); ?></label>
                                <?php echo $this->Form->input('Users.name', ['class' => 'form-control', 'type' => 'text', 'label' => false, 'required' => 'required']);?>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label><?php echo __('Last name'); ?></label>
                                <?php echo $this->Form->input('Users.last_name', ['class' => 'form-control', 'type' => 'text', 'label' => false, 'required' => 'required']);?>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label><?php echo __('Email'); ?></label>
                                <?php echo $this->Form->input('Users.email', ['class' => 'form-control', 'type' => 'email', 'label' => false, 'required' => 'required']);?>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label><?php echo __('Status'); ?></label>
                                <?php echo $this->Form->select('Users.active', [1 => __('Active'), 0 => __('Disabled')],['class' => 'form-control', 'type' => 'select', 'label' => false, 'required' => 'required', 'value' => $user->active]);?>
                            </div>
                        </div>
                    </div>                
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-default content">
                <div class="panel-heading">
                    <?php echo __('Reports Association');?>
                </div>
                <div class="panel-body" id="users-data-div">

                    <div class="row">
                        <?php if(count($robot_reports) > 0):?>
                            
                                <div class="col-lg-12">
                                    <table class="table table-condensed">
                                        <?php $x = 0;?>
                                        <?php foreach($robot_reports as $report):?>

                                            <?php

                                                $exist_report = false;
                                                $exist_newsletter = false;
                                                $exist_update = false;

                                                if(count($users_robot_reports) > 0){
                                                    foreach($users_robot_reports as $user_robot_report){
                                                        if($user_robot_report->robot_report_id == $report->id){
                                                            $exist_report = true;
                                                
                                                            if($user_robot_report->newsletter_suscribe == 1){
                                                                $exist_newsletter = true;
                                                            }

                                                            if($user_robot_report->update_permission == 1){
                                                                $exist_update = true;
                                                            }

                                                            break;
                                                        }
                                                    }
                                                }
                                            ?>

                                            <tr>
                                                <td class="">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" value="<?php echo $report->id;?>" name="UsersRobotReports[<?php echo $x;?>][robot_report_id]" id="robot-reports-id-<?php echo $x;?>" onclick="Javascript:validateExtraCheckboxes(<?php echo $x;?>);" <?php echo ($exist_report == true) ? 'checked' : ''; ?> >
                                                            <?php echo __($report->report_name);?>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="">
                                                    <div class="checkbox <?php echo ($exist_report == true && $exist_newsletter == true) ? '' : 'disabled'; ?>" id="newsletter-checkbox-div-<?php echo $x;?>">
                                                        <label>
                                                            <input type="checkbox" value="1" name="UsersRobotReports[<?php echo $x;?>][newsletter_suscribe]" id="robot-reports-newsletter-<?php echo $x;?>" <?php echo ($exist_newsletter == true) ? 'checked' : ''; ?> <?php echo ($exist_report == false) ? 'disabled' : ''; ?> >
                                                            <?php echo __('Email Newsletter Suscribe');?>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="">
                                                    <div class="checkbox <?php echo ($exist_report == false) ? 'disabled' : ''; ?>" id="update-checkbox-div-<?php echo $x;?>">
                                                        <label>
                                                            <input type="checkbox" value="1" name="UsersRobotReports[<?php echo $x;?>][update_permission]" id="robot-reports-update-<?php echo $x;?>" <?php echo ($exist_update == true) ? 'checked' : ''; ?> <?php echo ($exist_report == false) ? 'disabled' : ''; ?> >
                                                            <?php echo __('Permission to Update Data');?>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php $x++;?>
                                        <?php endforeach;?>
                                    </table>        
                                </div>
                            

                        <?php endif;?>
                    </div>                
                </div>
            </div>
        </div>
    </div>

    

    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-default content" id="users-companies-panel-div">
                <div class="panel-heading">
                    <?php echo __('Company Association');?>
                     <button type="button" class="btn btn-success btn-xs pull-right" data-toggle="modal" data-target="#assign-company-modal">
                        <i class="fa fa-plus"></i> <?php echo __('Assign Company');?>
                    </button>
                </div>
                <div class="panel-body" id="users-companies-panel-body-div">
                    <?php if(count($users_companies) > 0):?>

                        <!--<div class="table-responsive">
                            <table class="table table-stripped" id="users-companies-table">
                                <thead>
                                    <tr>
                                        <th><?php //echo __('Company');?></th>
                                        <th><?php //echo __('Store');?></th>
                                        <th><?php //echo __('Section');?></th>
                                        <th><?php //echo __('Actions');?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php //foreach($users_companies as $user_company):?>
                                    <tr id="user-company-row-<?php //echo $user_company->id;?>">
                                        <td><?php //echo $user_company->company->company_name; ?></td>
                                        <td><?php //echo $user_company->store->store_name.' ['.$user_company->store->store_code.']'; ?></td>
                                        <td><?php //echo $user_company->section->section_name; ?></td>
                                        <td><?php //echo $this->Form->button($this->Html->tag('i', '', ['class' => 'fa fa-times']), ['escape' => false, 'type' => 'button','class' => 'btn btn-danger btn-xs pull-center', 'data-user-company-id' => $user_company->id, 'data-toggle' => 'modal', 'data-target' => '#delete-user-company-modal']); ?></td>
                                    </tr>
                                <?php //endforeach;?>
                                </tbody>
                            </table>
                        </div>-->
                        <?php $x = 0;?>
                        <?php foreach($users_companies as $user_company):?>
                            <div class="well">
                                <h4>
                                    <?php echo __('Data to associate');?>
                                </h4>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <ul>
                                            <li>
                                                <?php echo __('Company');?>: 
                                                <strong><?php echo $user_company->company->company_name; ?></strong>
                                            </li>
                                            <li>
                                                <?php echo __('Store');?>: 
                                                <strong><?php echo $user_company->store->store_name.' ['.$user_company->store->store_code.']'; ?></strong>
                                            </li>
                                            <li>
                                                <?php echo __('Section');?>: 
                                                <strong><?php echo $user_company->section->section_name; ?></strong>
                                            </li>
                                        </ul>
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

                    <?php else:?>
                        <div class="alert alert-danger" id="users-companies-empty-alert">
                            <i class="fa fa-times"></i> <?php echo __('No exist company association');?>
                        </div>
                    <?php endif;?>
                   
                    <div id="users-companies-inputs-div">
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="assign-company-modal" tabindex="-1" role="dialog" aria-labelledby="assign-company-modal-label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="assign-company-modal-label"><?php echo __('Company Assignation');?></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group" id="company-select-div">
                                <label>Company</label>
                                <?php echo $this->Form->select('Companies.id', $companies, ['type' => 'select', 'class' => 'form-control', 'label' => false, 'empty' => __('Select a company'), 'id' => 'companies-id']);?>
                            </div>
                            <div class="form-group" id="store-select-div">
                            </div>
                            <div class="form-group" id="section-select-div">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <?php echo $this->Form->button(__('Assign'), ['type' => 'button', 'onclick' => 'javascript:assignUserCompany();', 'class' => 'btn btn-default pull-right', 'id' => 'assign-company-button', 'style' => 'display:none;']);?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal -->
        </div>

        <div class="col-lg-6">
            <div class="panel panel-default content" id="users-suppliers-panel-div">
                <div class="panel-heading">
                    <?php echo __('Supplier Association');?>
                    <button type="button" class="btn btn-success btn-xs pull-right" data-toggle="modal" data-target="#assign-supplier-modal">
                        <i class="fa fa-plus"></i> <?php echo __('Assign Supplier');?>
                    </button>
                </div>
                <div class="panel-body" id="users-suppliers-panel-body-div">


                    <?php if(count($users_suppliers) > 0):?>

                        <?php $x = 0;?>
                        <?php foreach($users_suppliers as $user_supplier):?>
                            <div class="well">
                                <h4>
                                    <?php echo __('Data to associate');?>
                                </h4>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <ul>
                                            <li>
                                                <?php echo __('Company');?>: 
                                                <strong><?php echo $user_supplier->company->company_name; ?></strong>
                                            </li>
                                            <li>
                                                <?php echo __('Store');?>: 
                                                <strong><?php echo $user_supplier->store->store_name.' ['.$user_supplier->store->store_code.']'; ?></strong>
                                            </li>
                                            <li>
                                                <?php echo __('Section');?>: 
                                                <strong><?php echo $user_supplier->section->section_name; ?></strong>
                                            </li>
                                            <li>
                                                <?php echo __('Supplier');?>: 
                                                <strong><?php echo $user_supplier->supplier->supplier_name; ?></strong>
                                            </li>
                                        </ul>
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
                                            <?php if(count($user_supplier->users_suppliers_robot_reports) > 0):?>

                                                <?php foreach($user_supplier->users_suppliers_robot_reports as $user_supplier_robot_report):?>

                                                    <?php if($user_supplier_robot_report->robot_report_id == $robot_report->id):?>

                                                        <?php $founded = 1;?>
                                                        <label class="checkbox-inline checkbox-styled"><input name="UsersSuppliersRobotReports[<?php echo $x;?>][<?php echo $robot_report->report_keyword;?>]" type="checkbox" value="<?php echo $robot_report->report_keyword;?>" checked><span><?php echo __($robot_report->report_name);?></span></label>

                                                        <?php echo $this->Form->hidden('UsersSuppliersRobotReports.'.$x.'.id', ['value' => $user_supplier_robot_report->id]);?>
                                                        <?php break;?>
                                                    <?php endif;?>


                                                <?php endforeach;?>

                                                <?php if($founded == 0):?>
                                                    <label class="checkbox-inline checkbox-styled"><input name="UsersSuppliersRobotReports[<?php echo $x;?>][<?php echo $robot_report->report_keyword;?>]" type="checkbox" value="<?php echo $robot_report->report_keyword;?>"><span><?php echo __($robot_report->report_name);?></span></label>
                                                <?php endif;?>

                                            <?php else:?>
                                                <label class="checkbox-inline checkbox-styled"><input name="UsersSuppliersRobotReports[<?php echo $x;?>][<?php echo $robot_report->report_keyword;?>]" type="checkbox" value="<?php echo $robot_report->report_keyword;?>"><span><?php echo __($robot_report->report_name);?></span></label>
                                            <?php endif;?>
                                                
                                            <?php echo $this->Form->hidden('UsersSuppliersRobotReports.'.$x.'.user_supplier_id', ['value' => $user_supplier->id]);?>
                                            <?php echo $this->Form->hidden('UsersSuppliersRobotReports.'.$x.'.robot_report_id', ['value' => $robot_report->id]);?>
                                            <?php $x++;?>
                                        <?php endforeach;?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;?>

                    <?php else:?>
                        <div class="alert alert-danger" id="users-companies-empty-alert">
                            <i class="fa fa-times"></i> <?php echo __('No exist supplier association');?>
                        </div>
                    <?php endif;?>
                    
                    <div id="users-suppliers-inputs-div">
                    </div>
                </div>
            </div>

            <div class="modal fade" id="assign-supplier-modal" tabindex="-1" role="dialog" aria-labelledby="assign-company-modal-label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="assign-company-modal-label"><?php echo __('Supplier Assignation');?></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group" id="company-select-div">
                                <label>Company</label>
                                <?php echo $this->Form->select('Companies.id', $companies, ['type' => 'select', 'class' => 'form-control', 'label' => false, 'empty' => __('Select a company'), 'id' => 'companies-id']);?>
                            </div>
                            <div class="form-group" id="store-select-div">
                            </div>
                            <div class="form-group" id="supplier-select-div">
                            </div>
                            <div class="form-group" id="section-select-div">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <?php echo $this->Form->button(__('Assign'), ['type' => 'button', 'onclick' => 'javascript:assignUserSupplier();', 'class' => 'btn btn-default pull-right', 'id' => 'assign-supplier-button', 'style' => 'display:none;']);?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--<div class="panel panel-default content" id="users-companies-panel-div">
        <div class="panel-heading">
            <?php echo __('Now, assign a company or supplier');?>
        </div>
        <div class="panel-body">
            
            <div class="row">
                <div class="col-lg-6" id="users-companies-inputs-div">
                    <div class="form-group" id="company-select-div">
                        <label>Company</label>
                        <?php echo $this->Form->select('Companies.id', $companies, ['type' => 'select', 'class' => 'form-control', 'label' => false, 'empty' => __('Select a company'), 'id' => 'companies-id']);?>
                    </div>
                    <div class="form-group" id="store-select-div">
                    </div>
                    <div class="form-group" id="section-select-div">
                    </div>
                    <?php echo $this->Form->button(__('Assign'), ['type' => 'button', 'onclick' => 'javascript:assignUserCompany();', 'class' => 'btn btn-default pull-right', 'id' => 'assign-company-button']);?>
                </div>
                <div class="col-lg-6" id="users-suppliers-inputs-div">
                    <div class="form-group" id="-select-div">
                        <label>Company</label>
                        <?php echo $this->Form->select('Companies.id', $companies, ['type' => 'select', 'class' => 'form-control', 'label' => false, 'empty' => __('Select a company'), 'id' => 'companies-id']);?>
                    </div>
                    <div class="form-group" id="store-select-div">
                    </div>
                    <div class="form-group" id="supplier-select-div">
                    </div>
                    <?php echo $this->Form->button(__('Assign'));?>
                </div>
            </div>
        </div>
    </div>-->
    
    <div class="row" id="submit-div">
        <div class="col-lg-12">
            <button type="submit" class="btn btn-danger pull-right">Edit</button>
        </div>
    </div>
    
<?php echo $this->Form->end() ?>

<div class="modal fade" id="delete-user-supplier-modal" tabindex="-1" role="dialog" aria-labelledby="assign-company-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="assign-company-modal-label"><?php echo __('Deleting Supplier');?></h4>
            </div>
            <div class="modal-body">
                <?php echo $this->Html->para(null, __('Are you sure to delete this association').'?')?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <?php echo $this->Form->button(__('Delete'), ['type' => 'button', 'onclick' => 'javascript:deleteUserSupplier();', 'class' => 'btn btn-danger pull-right', 'id' => 'delete-button']);?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete-user-company-modal" tabindex="-1" role="dialog" aria-labelledby="assign-company-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="assign-company-modal-label"><?php echo __('Deleting Company');?></h4>
            </div>
            <div class="modal-body">
                <?php echo $this->Html->para(null, __('Are you sure to delete this association').'?')?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <?php echo $this->Form->button(__('Delete'), ['type' => 'button', 'onclick' => 'javascript:deleteUserCompany();', 'class' => 'btn btn-danger pull-right', 'id' => 'delete-button']);?>
            </div>
        </div>
    </div>
</div>

<?php $this->Html->scriptStart(array('block' => 'scriptBottom', 'inline' => false)); ?>
    
    var x = 0;
    var y = 0;

    $(document).ready(function(){

        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
        
        // Start Users Companies
        $("#assign-company-modal #companies-id").change(function() {

            if($(this).val() != ''){
                var exist_stores = 0;
                var exist_sections = 0;

                $('#assign-company-modal #section-select-div').html('');
                $('#assign-company-modal #store-select-div').html(<?php echo "'".$this->Html->image('ajax-loader.gif', ['style' => 'width:40px;'])."'";?>);

                $.ajax({
                    url: webroot + 'ajax/stores/getStoresListAdmin',
                    data: {
                        id: $(this).val()
                    },
                    cache: false,
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) 
                    {
                        //$('#location-form-div').html(data);
                        console.log(response);

                        var filtro = '';
                        if (response.status == true) {

                            exist_stores = 1;

                            filtro = '<label><?php echo __('Store');?></label>';
                            filtro += '<select name="Stores[id]" id="stores-id" class="form-control"><option value="" selected><?php echo __('Select a store');?></option>';
                            $.each(response.data.stores, function(key, value) {
                                filtro += '<option value="'+key+'">'+value+'</option>';
                            });
                            filtro += '</select></div>';
                            $('#assign-company-modal #store-select-div').html(filtro);
                        }
                        else{
                            $('#assign-company-modal #store-select-div').html('<?php echo $this->Html->div('alert alert-warning', __('No exist stores from this company, create one ').$this->Html->link(__('here'), ['controller' => 'Stores', 'action' => 'add']));?>');

                            
                        }
                    }
                });

                $.ajax({
                    url: webroot + 'ajax/sections/getSectionsList/',
                    data: {
                        id: $(this).val()
                    },
                    cache: false,
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) 
                    {
                        //$('#location-form-div').html(data);
                        console.log(response);

                        var filtro = '';
                        if (response.status == true) {

                            filtro = '<label><?php echo __('Section');?></label>';
                            filtro += '<select name="Sections[id]" id="sections-id" class="form-control"><option value="" selected><?php echo __('Select a section');?></option>';
                            $.each(response.data.sections, function(key, value) {
                                filtro += '<option value="'+key+'">'+value+'</option>';
                            });
                            filtro += '</select></div>';
                            $('#assign-company-modal #section-select-div').html(filtro);

                            exist_sections = 1;
                        }
                        else{
                            $('#assign-company-modal #section-select-div').html('<?php echo $this->Html->div('alert alert-warning', __('No exist sections from this company, create one ').$this->Html->link(__('here'), ['controller' => 'Sections','action' => 'add']));?>');

                            
                        }
                    }
                });

                setTimeout(function(){
                    if(exist_sections == 1 && exist_stores == 1){
                        $('#assign-company-modal #assign-company-button').css('display', 'block');
                    }
                    else{
                        $('#assign-company-modal #assign-company-button').css('display', 'none');
                    }
                }, 2000);
            }
            else{
                $('#assign-company-modal #store-select-div').remove();
                $('#assign-company-modal #section-select-div').remove();
            }
        });

        //Users Suppliers
        $("#assign-supplier-modal #companies-id").change(function() {

            if($(this).val() != ''){
                var exist_stores = 0;
                var exist_suppliers = 0;
                var exist_sections = 0;

                $('#assign-supplier-modal #section-select-div').html('');
                $('#assign-supplier-modal #store-select-div').html(<?php echo "'".$this->Html->image('ajax-loader.gif', ['style' => 'width:40px;'])."'";?>);

                $.ajax({
                    url: webroot + 'ajax/stores/getStoresListAdmin',
                    data: {
                        id: $(this).val()
                    },
                    cache: false,
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) 
                    {
                        //$('#location-form-div').html(data);
                        console.log(response);

                        var filtro = '';
                        if (response.status == true) {

                            filtro = '<label><?php echo __('Store');?></label>';
                            filtro += '<select name="Stores[id]" id="stores-id" class="form-control"><option value="" selected><?php echo __('Select a store');?></option>';
                            $.each(response.data.stores, function(key, value) {
                                filtro += '<option value="'+key+'">'+value+'</option>';
                            });
                            filtro += '</select></div>';
                            $('#assign-supplier-modal #store-select-div').html(filtro);

                            exist_stores = 1;
                        }
                        else{
                            $('#assign-supplier-modal #store-select-div').html('<?php echo $this->Html->div('alert alert-warning', __('No exist stores from this company, create one ').$this->Html->link(__('here'), ['controller' => 'Stores', 'action' => 'add']));?>');
                        }
                    }
                });

                $.ajax({
                    url: webroot + 'ajax/suppliers/getSuppliersList/',
                    data: {
                        id: $(this).val()
                    },
                    cache: false,
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) 
                    {
                        //$('#location-form-div').html(data);
                        console.log(response);

                        var filtro = '';
                        if (response.status == true) {

                            filtro = '<label><?php echo __('Supplier');?></label>';
                            filtro += '<select name="Suppliers[id]" id="suppliers-id" class="form-control"><option value="" selected><?php echo __('Select a supplier');?></option>';
                            $.each(response.data.suppliers, function(key, value) {
                                filtro += '<option value="'+key+'">'+value+'</option>';
                            });
                            filtro += '</select></div>';
                            $('#assign-supplier-modal #supplier-select-div').html(filtro);

                            exist_suppliers = 1;

                        }
                        else{
                            $('#assign-supplier-modal #supplier-select-div').html('<?php echo $this->Html->div('alert alert-warning', __('No exist suppliers from this company, create one ').$this->Html->link(__('here'), ['controller' => 'Suppliers', 'action' => 'add']));?>');
                        }
                    }
                });

                $.ajax({
                    url: webroot + 'ajax/sections/getSectionsList/',
                    data: {
                        id: $(this).val()
                    },
                    cache: false,
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) 
                    {
                        //$('#location-form-div').html(data);
                        console.log(response);

                        var filtro = '';
                        if (response.status == true) {

                            filtro = '<label><?php echo __('Section');?></label>';
                            filtro += '<select name="Sections[id]" id="sections-id" class="form-control"><option value="" selected><?php echo __('Select a section');?></option>';
                            $.each(response.data.sections, function(key, value) {
                                filtro += '<option value="'+key+'">'+value+'</option>';
                            });
                            filtro += '</select></div>';
                            $('#assign-supplier-modal #section-select-div').html(filtro);

                            exist_sections = 1;
                        }
                        else{
                            $('#assign-supplier-modal #section-select-div').html('<?php echo $this->Html->div('alert alert-warning', __('No exist sections from this company, create one ').$this->Html->link(__('here'), ['controller' => 'Sections', 'action' => 'add']));?>');

                            
                        }
                    }
                });

                setTimeout(function(){
                    if(exist_suppliers == 1 && exist_stores == 1 && exist_sections == 1){
                        $('#assign-supplier-modal #assign-supplier-button').css('display', 'block');
                    }
                    else{
                        $('#assign-supplier-modal #assign-supplier-button').css('display', 'none');
                    }
                }, 2000);

            }
            else{
                $('#assign-supplier-modal #store-select-div').remove();
                $('#assign-supplier-modal #supplier-select-div').remove();
            }
        });

        $('#delete-user-supplier-modal').on('hidden.bs.modal', function (e) {
            var modal = $(this);
            modal.find('.modal-footer #delete-button').attr('onclick', 'Javascript:deleteUserSupplier();');
        });

        $('#delete-user-supplier-modal').on('show.bs.modal', function (e) {
            var button = $(e.relatedTarget);
            var id = button.data('user-supplier-id');

            var modal = $(this);
            modal.find('.modal-footer #delete-button').attr('onclick', 'Javascript:deleteUserSupplier('+ id +');');
        });  

        $('#delete-user-company-modal').on('hidden.bs.modal', function (e) {
            var modal = $(this);
            modal.find('.modal-footer #delete-button').attr('onclick', 'Javascript:deleteUserCompany();');
        });

        $('#delete-user-company-modal').on('show.bs.modal', function (e) {
            var button = $(e.relatedTarget);
            var id = button.data('user-company-id');

            var modal = $(this);
            modal.find('.modal-footer #delete-button').attr('onclick', 'Javascript:deleteUserCompany('+ id +');');
        });     
    });

    function assignUserCompany(){
        var company_id = $('#assign-company-modal #companies-id').val();
        var company_name = $('#assign-company-modal #companies-id').find(":selected").text();
        var store_id = $('#assign-company-modal #stores-id').val();
        var store_name = $('#assign-company-modal #stores-id').find(":selected").text();
        var section_id = $('#assign-company-modal #sections-id').val();
        var section_name = $('#assign-company-modal #sections-id').find(":selected").text();

        if(company_id != '' && store_id != '' && section_id != ''){

            $('#users-companies-inputs-div').append('<input type="hidden" name="UsersCompanies['+x+'][company_id]" id="users-companies-company-id" value='+company_id+'>');
            $('#users-companies-inputs-div').append('<input type="hidden" name="UsersCompanies['+x+'][store_id]" id="users-companies-store-id" value='+store_id+'>');
            $('#users-companies-inputs-div').append('<input type="hidden" name="UsersCompanies['+x+'][section_id]" id="users-companies-section-id" value='+section_id+'>');

            $('#assign-company-modal').modal('hide');

            /*$('#users-companies-panel-body-div').prepend('<div class="well"><h4><?php echo __('Data to associate');?></h4><ul><li><?php echo __('Company');?>: <strong>'+company_name+'</strong></li><li><?php echo __('Store');?>: <strong>'+store_name+'</strong></li><li><?php echo __('Section');?>: <strong>'+section_name+'</strong></li></ul></div>');*/

            $('#users-companies-panel-body-div').prepend('<div class="well"><h4><?php echo __('Data to associate');?></h4><div class="row"><div class="col-sm-6"><ul><li><?php echo __('Company');?>: <strong>'+company_name+'</strong></li><li><?php echo __('Store');?>: <strong>'+store_name+'</strong></li><li><?php echo __('Section');?>: <strong>'+section_name+'</strong></li></ul></div><div class="col-sm-6"><label class="checkbox-inline checkbox-styled"><input name="UsersCompanies['+x+'][assortmentReport]" type="checkbox" value="assortmentReport"><span><?php echo __('Assortment Report');?></span></label><label class="checkbox-inline checkbox-styled"><input name="UsersCompanies['+x+'][priceDifferenceReport]" type="checkbox" value="priceDifferenceReport"><span><?php echo __('Price Difference Report');?></span></label><label class="checkbox-inline checkbox-styled"><input name="UsersCompanies['+x+'][stockOutReport]" type="checkbox" value="stockOutReport"><span><?php echo __('Stock Alert Report');?></span></label></div></div></div>');

            x = x + 1;

            $("#assign-company-modal #companies-id").val('');
            $('#assign-company-modal #store-select-div').html('');
            $('#assign-company-modal #section-select-div').html('');

            $('#assign-company-modal #assign-supplier-button').css('display', 'none');
        }
        else{
            console.log('Invalid params');
        }

        verifiedAssociation('users-companies-empty-alert');

    }

    function assignUserSupplier(){
        var company_id = $('#assign-supplier-modal #companies-id').val();
        var company_name = $('#assign-supplier-modal #companies-id').find(":selected").text();
        var store_id = $('#assign-supplier-modal #stores-id').val();
        var store_name = $('#assign-supplier-modal #stores-id').find(":selected").text();
        var supplier_id = $('#assign-supplier-modal #suppliers-id').val();
        var supplier_name = $('#assign-supplier-modal #suppliers-id').find(":selected").text();
        var section_id = $('#assign-supplier-modal #sections-id').val();
        var section_name = $('#assign-supplier-modal #sections-id').find(":selected").text();

        if(company_id != '' && store_id != '' && supplier_id != '' && section_id != ''){

            $('#users-suppliers-inputs-div').append('<input type="hidden" name="UsersSuppliers['+y+'][company_id]" id="users-suppliers-company-id" value='+company_id+'>');
            $('#users-suppliers-inputs-div').append('<input type="hidden" name="UsersSuppliers['+y+'][store_id]" id="users-suppliers-store-id" value='+store_id+'>');
            $('#users-suppliers-inputs-div').append('<input type="hidden" name="UsersSuppliers['+y+'][supplier_id]" id="users-suppliers-supplier-id" value='+supplier_id+'>');
            $('#users-suppliers-inputs-div').append('<input type="hidden" name="UsersSuppliers['+y+'][section_id]" id="users-suppliers-section-id" value='+section_id+'>');

            $('#assign-supplier-modal').modal('hide');

            /*$('#users-suppliers-panel-body-div').prepend('<div class="well"><h4><?php echo __('Data to associate');?></h4><ul><li><?php echo __('Company');?>: <strong>'+company_name+'</strong></li><li><?php echo __('Store');?>: <strong>'+store_name+'</strong></li><li><?php echo __('Supplier');?>: <strong>'+supplier_name+'</strong></li><li><?php echo __('Section');?>: <strong>'+section_name+'</strong></li></ul></div>');*/





            $('#users-suppliers-panel-body-div').prepend('<div class="well"><h4><?php echo __('Data to associate');?></h4><div class="row"><div class="col-sm-6"><ul><li><?php echo __('Company');?>: <strong>'+company_name+'</strong></li><li><?php echo __('Store');?>: <strong>'+store_name+'</strong></li><li><?php echo __('Section');?>: <strong>'+section_name+'</strong></li><li><?php echo __('Supplier');?>: <strong>'+supplier_name+'</strong></li></ul></div><div class="col-sm-6"><label class="checkbox-inline checkbox-styled"><input name="UsersSuppliers['+x+'][assortmentReport]" type="checkbox" value="assortmentReport"><span><?php echo __('Assortment Report');?></span></label><label class="checkbox-inline checkbox-styled"><input name="UsersSuppliers['+x+'][priceDifferenceReport]" type="checkbox" value="priceDifferenceReport"><span><?php echo __('Price Difference Report');?></span></label><label class="checkbox-inline checkbox-styled"><input name="UsersSuppliers['+x+'][stockOutReport]" type="checkbox" value="stockOutReport"><span><?php echo __('Stock Alert Report');?></span></label></div></div></div>');


            y = y + 1;

            $("#assign-supplier-modal #companies-id").val('');
            $('#assign-supplier-modal #store-select-div').html('');
            $('#assign-supplier-modal #supplier-select-div').html('');
            $('#assign-supplier-modal #section-select-div').html('');

            $('#assign-supplier-modal #assign-supplier-button').css('display', 'none');
        }
        else{
            console.log('Invalid params');
        }

        verifiedAssociation('users-suppliers-empty-alert');

    }


    function verifiedAssociation(empty_label){
        if((x + y) > 0){
            $('#submit-div').show();
            $('#'+empty_label).hide();
        }
        else{
            $('#submit-div').hide();
            $('#'+empty_label).show();
        }
    }

    function deleteUserSupplier(id){
        if(id != ''){
            $.ajax({
                url: webroot + 'ajax/usersSuppliers/deleteRecord/',
                data: {
                    id: id
                },
                cache: false,
                type: 'POST',
                dataType: 'json',
                success: function (response) 
                {
                    //$('#location-form-div').html(data);
                    console.log(response);

                    if (response.status == true) {
                        $('#delete-user-supplier-modal').modal('hide')
                        $('#user-supplier-row-'+response.data.id).remove();

                        $('#delete-user-supplier-modal .modal-footer #delete-button').attr('onclick', 'Javascript:deleteUserSupplier();');
                    }
                }
            });
        }
    }

    function deleteUserCompany(id){
        if(id != ''){
            $.ajax({
                url: webroot + 'ajax/usersCompanies/deleteRecord/',
                data: {
                    id: id
                },
                cache: false,
                type: 'POST',
                dataType: 'json',
                success: function (response) 
                {
                    //$('#location-form-div').html(data);
                    console.log(response);

                    if (response.status == true) {
                        $('#delete-user-company-modal').modal('hide')
                        $('#user-company-row-'+response.data.id).remove();

                        $('#delete-user-company-modal .modal-footer #delete-button').attr('onclick', 'Javascript:deleteUserCompany();');
                    }
                }
            });
        }
    }

    function validateExtraCheckboxes(id){

        if ($("#robot-reports-id-"+id).is(":checked")) {

            $('#newsletter-checkbox-div-'+id).removeClass('disabled');
            $('#robot-reports-newsletter-'+id).removeAttr('disabled');

            $('#update-checkbox-div-'+id).removeClass('disabled');
            $('#robot-reports-update-'+id).removeAttr('disabled');

        }
        else {
            $('#newsletter-checkbox-div-'+id).addClass('disabled');
            $('#robot-reports-newsletter-'+id).attr("disabled", true);
            $('#robot-reports-newsletter-'+id).attr('checked', false);

            $('#update-checkbox-div-'+id).addClass('disabled');
            $('#robot-reports-update-'+id).attr("disabled", true);
            $('#robot-reports-update-'+id).attr('checked', false);
        }
    }

<?php $this->Html->scriptEnd(); ?>

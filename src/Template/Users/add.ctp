<h3><?php echo __('User Creation');?></h3>
<?php echo $this->Form->create(null, ['url' => ['controller' => 'Users', 'action' => 'add'], 'class' => 'form']);?>
    <div class="panel panel-default content">
        <div class="panel-heading">
            <?php echo __('First, complete the user data');?>
        </div>
        <div class="panel-body" id="users-data-div">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label><?php echo __('Name'); ?></label>
                        <?php echo $this->Form->input('Users.name', ['class' => 'form-control', 'type' => 'text', 'label' => false, 'required' => 'required']);?>
                    </div>
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
                    <div class="form-group">
                        <label><?php echo __('Password'); ?></label>
                        <?php echo $this->Form->input('Users.password', ['class' => 'form-control', 'type' => 'password', 'label' => false, 'required' => 'required']);?>
                    </div>
                </div>
            </div>                
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-info">
                <i class="fa fa-exclamation-triangle"></i> <?php echo __('Now, you must associate at least one company or one supplier for this user');?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-default content" id="users-companies-panel-div">
                <div class="panel-heading">
                    <?php echo __('Company Association');?>
                </div>
                <div class="panel-body" id="users-companies-panel-body-div">
                    <div class="alert alert-danger" id="users-companies-empty-alert">
                        <i class="fa fa-times"></i> <?php echo __('No exist company association');?>
                    </div>
                    <button type="button" class="btn btn-default btn-lg" data-toggle="modal" data-target="#assign-company-modal">
                        <?php echo __('Assign Company');?>
                    </button>
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
                </div>
                <div class="panel-body" id="users-suppliers-panel-body-div">
                    <div class="alert alert-danger" id="users-suppliers-empty-alert">
                        <i class="fa fa-times"></i> <?php echo __('No exist supplier association');?>
                    </div>
                    <button type="button" class="btn btn-default btn-lg" data-toggle="modal" data-target="#assign-supplier-modal">
                        <?php echo __('Assign Supplier');?>
                    </button>
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
    
    <div class="row" id="submit-div" style="display: none;">
        <div class="col-lg-12">
            <button type="submit" class="btn btn-danger pull-right">Create</button>
        </div>
    </div>
    
<?php echo $this->Form->end() ?>

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
                    url: webroot + 'ajax/stores/getStoresList/',
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
                    url: webroot + 'ajax/stores/getStoresList/',
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

            $('#users-companies-panel-body-div').prepend('<div class="well"><h4><?php echo __('Data to associate');?></h4><ul><li><?php echo __('Company');?>: <strong>'+company_name+'</strong></li><li><?php echo __('Store');?>: <strong>'+store_name+'</strong></li><li><?php echo __('Section');?>: <strong>'+section_name+'</strong></li></ul></div>');

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

            $('#users-suppliers-panel-body-div').prepend('<div class="well"><h4><?php echo __('Data to associate');?></h4><ul><li><?php echo __('Company');?>: <strong>'+company_name+'</strong></li><li><?php echo __('Store');?>: <strong>'+store_name+'</strong></li><li><?php echo __('Supplier');?>: <strong>'+supplier_name+'</strong></li><li><?php echo __('Section');?>: <strong>'+section_name+'</strong></li></ul></div>');

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

<?php $this->Html->scriptEnd(); ?>
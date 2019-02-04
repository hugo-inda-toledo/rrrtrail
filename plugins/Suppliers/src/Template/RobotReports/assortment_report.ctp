<?php echo $this->Html->css('application/bower_components/bootstrap-datetimepicker.min.css') ?>
<?php echo $this->Html->css('application/plugins/dataTables/dataTables.bootstrap.css') ?>
<?php echo $this->Html->css('application/plugins/dataTables/jquery.dataTables.min.css') ?>


<section>
    <h3>
        <?php echo __('Assortment Report');?>        
    </h3>

    <div class="panel panel-default content">
        <div class="panel-heading">
            <?php echo __('Select the corresponding parameters to obtain the report');?>
        </div>
        <div class="panel-body" id="users-data-div">
            <div class="row">
                <div class="col-lg-2">
                    <div class="form-group" id="company-input-id">
                        <label><?php echo __('Company').' '.$this->Html->tag('span', __('Required'), ['class' => 'label label-danger']); ?></label>
                        <?php echo $this->Form->select('company_id', $companies_list, ['class' => 'form-control', 'type' => 'select', 'label' => false, 'required' => 'required', 'empty' => __('Select a company'), 'default' => (isset($company_default) ? $company_default : ''), 'id' => 'company_id']);?>
                    </div>
                </div>

                <div class="col-lg-2">
    	            <div class="form-group" id="store-input-div">
    	            	<?php if(isset($stores_list)):?>
    	            		<label><?php echo __('Store').' '.$this->Html->tag('span', __('Required'), ['class' => 'label label-danger']); ?></label>
    	                    <?php echo $this->Form->select('store_id', $stores_list, ['class' => 'form-control', 'type' => 'select', 'label' => false, 'required' => 'required', 'empty' => __('Select a store'), 'default' => (isset($store_default) ? $store_default : ''), 'id' => 'store_id']);?>
    	            	<?php endif;?>
    	            </div>
    	        </div>

    	        <div class="col-lg-2">
    	            <div class="form-group" id="section-input-div">
    	            	<?php if(isset($sections_list)):?>
    	            		<label><?php echo __('Section').' '.$this->Html->tag('span', __('Required'), ['class' => 'label label-danger']); ?></label>
                            <?php echo $this->Form->select('section_id', $sections_list, ['class' => 'form-control', 'type' => 'select', 'label' => false, 'required' => 'required', 'empty' => __('Select a section'), 'id' => 'section_id']);?>
    	            	<?php endif;?>
    	            </div>
    	        </div>

                <div class="col-lg-2" <?php echo (!isset($categories_list)) ? 'style="display:none;"' : ''?> id="category-input-div">
                    <div class="form-group" id="category-input-div">
                        <?php if(isset($categories_list)):?>
                            <label><?php echo __('Section').' '.$this->Html->tag('span', __('Required'), ['class' => 'label label-danger']); ?></label>
                            <?php echo $this->Form->select('category_id', $categories_list, ['class' => 'form-control', 'type' => 'select', 'label' => false, 'required' => 'required', 'empty' => __('Select a category'), 'id' => 'category_id']);?>
                        <?php endif;?>
                    </div>
                </div>

                <div class="col-lg-2" style="display:none;" id="start-input-id">
                    <div class="form-group">
                        <label><?php echo __('Start date').' '.$this->Html->tag('span', __('Required'), ['class' => 'label label-danger']); ?></label>
                        <?php echo $this->Form->input('start_date', ['class' => 'form-control', 'type' => 'text', 'label' => false, 'required' => 'required']);?>
                    </div>
                </div>

                <div class="col-lg-2" <?php echo (!isset($stores_list)) ? 'style="display:none;"' : ''?> id="end-input-id">
                    <div class="form-group">
                        <label id="end-date-label"><?php echo __('Report date').' '.$this->Html->tag('span', __('Required'), ['class' => 'label label-danger']); ?></label>
                        <?php echo $this->Form->input('end_date', ['class' => 'form-control', 'type' => 'text', 'label' => false, 'required' => 'required', 'id' => 'end_date', 'readonly' => 'true']);?>
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-sm-10">
                </div>
                <div class="col-lg-2 " <?php echo (!isset($stores_list)) ? 'style="display:none;"' : ''?> id="button-div">
                    <?php echo $this->Form->button(__('Generate'), ['type' => 'button', 'class' => 'btn btn-danger pull-right', 'id' => 'generate-button']);?>
                </div>
            </div>               
        </div>
    </div>

    <div id="report-response">
    </div>
    <div id="editor">
    </div>
</section>

<?php echo $this->Html->script('theme-zippedi/libs/jquery/jquery-1.11.2.min.js');?>

<?php echo  $this->Html->script('application/bower_components/moment.min.js', ['block' => 'scriptBottom']);?>
<?php echo  $this->Html->script('application/bower_components/bootstrap-datetimepicker.min.js', ['block' => 'scriptBottom']);?>
<?php echo  $this->Html->script('application/plugins/dataTables/jquery.dataTables.js', ['block' => 'scriptBottom']) ?>
<?php echo  $this->Html->script('application/plugins/dataTables/dataTables.bootstrap.js', ['block' => 'scriptBottom']) ?>


<?php //$this->Html->scriptStart(array('block' => 'scriptBottom', 'inline' => false)); ?>



<script type="text/javascript">
    var x = 0;
    var y = 0;

    $(document).ready(function(){

        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });

        
        /*$('#start_date').datetimepicker();
        $('#end_date').datetimepicker({
            useCurrent: false, //Important! See issue #1075,
            format: "YYYY-MM-DD",
            //maxDate: d,
            ignoreReadonly: false
        });
        $("#start_date").on("dp.change", function (e) {
            $('#end-date').data("DateTimePicker").minDate(e.date);
        });
        $("#end_date").on("dp.change", function (e) {
            $('#start_date').data("DateTimePicker").maxDate(e.date);
        });*/
        

        // On change company
        $("#company_id").change(function() {

            if($(this).val() != ''){
            	var exist_stores = 0;
                var exist_sections = 0;

                $('#section-input-div').html('');
                $('#store-input-div').html(<?php echo "'".$this->Html->image('ajax-loader.gif', ['style' => 'width:40px;'])."'";?>);

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
                        console.log(response);

                        var filtro = '';
                        if (response.status == true) {

                        	exist_stores = 1;
                            filtro = '<label><?php echo __('Store');?> <?php echo $this->Html->tag('span', __('Required'), ['class' => 'label label-danger']);?></label>';
                            filtro += '<select name="store_id" id="store_id" class="form-control" onchange="Javascript:getSessions();"><option value="" selected><?php echo __('Select a store');?></option>';
                            $.each(response.data.stores, function(key, value) {
                                filtro += '<option value="'+key+'">'+value+'</option>';
                            });
                            filtro += '</select></div>';

                            $('#store-input-div').html(filtro);
                        }
                        else{
                            $('#store-input-div').html('<?php echo $this->Html->div('alert alert-warning', __('No exist stores from this company'));?>');
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
                        console.log(response);

                        var filtro = '';
                        if (response.status == true) {

                        	exist_sections = 1;
                            filtro = '<label><?php echo __('Section');?> <?php echo $this->Html->tag('span', __('Required'), ['class' => 'label label-danger']);?></label>';
                            filtro += '<select name="section_id" id="section_id" class="form-control" onchange="Javascript:getCategories();"><option value="" selected><?php echo __('Select a section');?></option>';
                            $.each(response.data.sections, function(key, value) {
                                filtro += '<option value="'+key+'">'+value+'</option>';
                            });
                            filtro += '</select></div>';
                            $('#section-input-div').html(filtro);
                        }
                        else{
                            $('#section-input-div').html('<?php echo $this->Html->div('alert alert-warning', __('No exist sections from this company'));?>');
                        }
                    }
                });

                /*setTimeout(function(){

                    if(exist_sections == 1 && exist_stores == 1){

                        $('#end-input-id').fadeIn(300);
                        $('#button-div').fadeIn(300);
                    }
                    else{
                        $('#end-input-id').fadeOut(300);
                        $('#button-div').fadeOut(300);
                        $('#report-response').html('');
                    }
                }, 2000);*/
            }
            else{
                $('#store-input-div').html('');
                $('#section-input-div').html('');
                $('#report-response').html('');
                $('#end-input-id').css('display', 'none');
            }
        });

        $('#generate-button').on('click', function(){

        	clearErrors();

        	var pass = true;
        	if($('#company_id').val() == ''){
        		pass = false;
        		$('#company-input-id').addClass('has-error');
        	}

        	if($('#store_id').val() == ''){
        		pass = false;
        		$('#store-input-id').addClass('has-error');
        	}

        	if($('#section_id').val() == ''){
        		pass = false;
        		$('#section-input-id').addClass('has-error');
        	}

        	if($('#end_date').val() == ''){
        		pass = false;
        		$('#end-input-id').addClass('has-error');
        	}

        	if(pass == true){

        		$('#report-response').html('<div class="row"><div class="col-md-12 text-center"><?php echo __('We are processing your report, please wait a moment').'...';?><br><?php echo $this->Html->image('ajax-loader.gif', ['style' => 'width:120px;']);?></div></div>');

                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                
        		$.ajax({
                    url: webroot + 'ajax/robotReports/doAssortmentReport/',
                    data: {
                        company_id: $('#company_id').val(),
                        store_id: $('#store_id').val(),
                        section_id: $('#section_id').val(),
                        category_id: $('#category_id').val(),
                        end_date: $('#end_date').val(),
                    },
                    cache: false,
                    type: 'POST',
                    dataType: 'html',
                    success: function (response) 
                    {
                    	$('#report-response').html(response);
                        /*console.log(response);

                        var filtro = '';
                        if (response.status == true) {

                        	exist_sections = 1;
                            filtro = '<label><?php echo __('Section');?></label>';
                            filtro += '<select name="section_id" id="section_id" class="form-control"><option value="" selected><?php echo __('Select a section');?></option>';
                            $.each(response.data.sections, function(key, value) {
                                filtro += '<option value="'+key+'">'+value+'</option>';
                            });
                            filtro += '</select></div>';
                            $('#section-input-div').html(filtro);
                        }
                        else{
                            $('#section-input-div').html('<?php echo $this->Html->div('alert alert-warning', __('No exist sections from this company, create one ').$this->Html->link(__('here'), ['controller' => 'Sections','action' => 'add']));?>');
                        }*/
                    }
                });
        	}

        });
    });

    function clearErrors(){
    	$('#company-input-id').removeClass('has-error');
    	$('#store-input-id').removeClass('has-error');
    	$('#section-input-id').removeClass('has-error');
    	$('#start-input-id').removeClass('has-error');
    	$('#end-input-id').removeClass('has-error');
    }

    function getSessions(){
        if($('#store_id').val() != ''){
            $.ajax({
                //url: webroot + 'robot-reports/getSessionsList/'+ $('#store_id').val() + '/true/true',

                url: webroot + 'robot-sessions/getCatalogDates/'+ $('#store_id').val() + '/true/true',
                cache: false,
                type: 'POST',
                dataType: 'json',
                success: function (response) 
                {
                    console.log(response);

                    var filtro = '';
                    if (response.status == true) {

                        var dates = [];
                        var x=0;
                        $.each(response.data.sessions, function(key, value) {
                            dates[x] = value;
                            x = x+1;
                        });

                        exist_sessions = 1;
                        filtro = '<label><?php echo __('Session date');?> <?php echo $this->Html->tag('span', __('Required'), ['class' => 'label label-danger']);?></label>';
                        filtro += '<input type="text" required="required" name="end_date" id="end_date" class="form-control" />';

                        $('#session-input-div').html(filtro);

                        $('#end_date').datetimepicker({
                            useCurrent: false, //Important! See issue #1075,
                            format: "YYYY-MM-DD",
                            //maxDate: d,
                            //ignoreReadonly: false,
                            enabledDates: dates,
                            ignoreReadonly: true,
                            allowInputToggle: true
                        });

                        $('#end-input-id').fadeIn(300);
                        $('#button-div').fadeIn(300);
                    }
                    else{
                        $('#session-input-div').html('<?php echo $this->Html->div('alert alert-warning', __('No exist session from this store'));?>');
                        $('#end-input-id').fadeOut(300);
                        $('#button-div').fadeOut(300);
                        $('#report-response').html('');
                    }
                }
            });
        }
    }

    function getCategories(){
        if($('#section_id').val() != ''){


            $('#category-input-div').html(<?php echo "'".$this->Html->image('ajax-loader.gif', ['style' => 'width:40px;'])."'";?>);
            $('#category-input-div').show();

            $.ajax({
                url: webroot + 'ajax/categories/getCategoriesList/',
                data: {
                    id: $('#section_id').val()
                },
                cache: false,
                type: 'POST',
                dataType: 'json',
                success: function (response) 
                {
                    console.log(response);

                    var filtro = '';
                    if (response.status == true) {

                        filtro = '<div class="form-group">';
                        filtro += '<label><?php echo __('Category');?></label>';
                        filtro += '<select name="category_id" id="category_id" class="form-control"><option value="" selected><?php echo __('Select a category');?></option>';
                        $.each(response.data.categories, function(key, value) {
                            filtro += '<option value="'+key+'">'+value+'</option>';
                        });
                        filtro += '</select></div></div>';

                        $('#category-input-div').html(filtro);
                        $('#category-input-div').fadeIn(300);
                    }
                    else{
                        $('#category-input-div').html('<?php echo $this->Html->div('alert alert-warning', __('No exist categories from this company'));?>');
                    }
                }
            });
        }
        else{
            $('#category-input-div').html('');
        }
    }
</script>

<?php //$this->Html->scriptEnd(); ?>
<?php echo $this->Html->css('application/bower_components/bootstrap-datetimepicker.min.css') ?>
<?php echo $this->Html->css('application/plugins/dataTables/dataTables.bootstrap.css') ?>
<?php echo $this->Html->css('application/plugins/dataTables/jquery.dataTables.min.css') ?>

<section>
    <h3>
        <?php echo __('Price Difference Report');?>        
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
    	                    <?php echo $this->Form->select('store_id', $stores_list, ['class' => 'form-control', 'type' => 'select', 'label' => false, 'required' => 'required', 'empty' => __('Select a store'), 'default' => (isset($store_default) ? $store_default : ''), 'id' => 'store_id', 'onchange' => 'javascript:getRobotSessions();']);?>
    	            	<?php endif;?>
    	            </div>
    	        </div>

                <div class="col-lg-2" id="session-input-id">
                    <div class="form-group" id="session-input-div">
    	            	<?php if(isset($stores_list)):?>
    	            		<label><?php echo __('Session date').' '.$this->Html->tag('span', __('Required'), ['class' => 'label label-danger']); ?></label>
                            <?php echo $this->Form->select('session_id', $sessions_list, ['class' => 'form-control', 'type' => 'select', 'label' => false, 'required' => 'required', 'empty' => __('Select a session'), 'id' => 'session_id']);?>
    	            	<?php endif;?>
    	            </div>
                </div>
                <div class="col-lg-3" id="sessions-day-input-id">
                    <div class="form-group" id="sessions-day-input-div">
                    </div>
                </div>  
                <div class="col-lg-3" <?php echo (!isset($stores_list)) ? 'style="display:none;"' : ''?> id="button-div">
                    <?php echo $this->Form->button(__('Generate'), ['type' => 'button', 'class' => 'btn btn-danger pull-center', 'id' => 'generate-button', 'style' => 'margin-top: 24px;']);?>
                </div>
            </div>                
        </div>
    </div>

    <div id="report-response">
    </div>
</section>


<?php echo  $this->Html->script('application/bower_components/moment.min.js', ['block' => 'scriptBottom']);?>
<?php echo  $this->Html->script('application/bower_components/bootstrap-datetimepicker.min.js', ['block' => 'scriptBottom']);?>
<?php echo  $this->Html->script('application/google_charts/loader.js', ['block' => 'scriptBottom']);?>


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

        /*var d = new Date();
        d.setDate(d.getDate() + 1);*/
        
        $('#end_date').datetimepicker({
            useCurrent: false, //Important! See issue #1075,
            format: "YYYY-MM-DD",
            //maxDate: d,
            ignoreReadonly: false
        });
        

        // On change company
        $("#company_id").change(function() {

            if($(this).val() != ''){

                $('#section-input-div').html('');
                $('#session-input-div').html('');
                $('#sessions-day-input-div').html('');

                $('#button-div').hide();
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

                            filtro = '<label><?php echo __('Store');?> <?php echo $this->Html->tag('span', __('Required'), ['class' => 'label label-danger']);?></label>';
                            filtro += '<select name="store_id" id="store_id" class="form-control" onchange="javascript:getRobotSessions();"><option value="" selected><?php echo __('Select a store');?></option>';
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
            }
            else{

                $('#store-input-div').html('');
                $('#report-response').html('');
                $('#session-input-div').html('');
                $('#sessions-day-input-div').html('');
                $('#button-div').fadeOut(300);
            }
        });

        $('#generate-button').on('click', function(){

        	clearErrors();

        	var pass = true;
        	if($('#company_id').val() == ''){
        		pass = false;
        		$('#company-input-div').addClass('has-error');
        	}

        	if($('#store_id').val() == ''){
        		pass = false;
        		$('#store-input-div').addClass('has-error');
        	}

        	if($('#session_date').val() == ''){
        		pass = false;
        		$('#session-input-div').addClass('has-error');
        	}


            if($('#robot_session_id').val() == ''){
                pass = false;
                $('#sessions-day-input-div').addClass('has-error');
            }


        	if(pass == true){

        		$('#report-response').html('<div class="row"><div class="col-md-12 text-center"><?php echo __('We are processing your report, please wait a moment').'...';?><br><?php echo $this->Html->image('ajax-loader.gif', ['style' => 'width:120px;']);?></div></div>');

                $("html, body").animate({ scrollTop: $(document).height() }, "slow");

                <?php if($enable_to_update == true):?>
                    $('#report-options-div').hide();
                <?php endif;?>

        		$.ajax({
                    url: webroot + 'ajax/robotReports/doPriceDifferenceReport/',
                    data: {
                        company_id: $('#company_id').val(),
                        store_id: $('#store_id').val(),
                        session_date: $('#session_date').val(),
                        robot_session_id: $('#robot_session_id').val(),
                    },
                    cache: false,
                    type: 'POST',
                    dataType: 'html',
                    success: function (response) 
                    {
                    	$('#report-response').html(response);

                        
                    }
                });
        	}

        });
    });

    function getRobotSessions(){

    	var store_id = $('#store_id').val();

    	if(store_id != ''){

            $('#sessions-day-input-div').html('');
    		$('#session-input-div').html(<?php echo "'".$this->Html->image('ajax-loader.gif', ['style' => 'width:40px;'])."'";?>);

    		$.ajax({
                url: webroot + 'robot-sessions/getSessionsList/'+ store_id + '/price_differences/true/true',
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
                            //filtro += '<option value="'+key+'">'+value+'</option>';
                            dates[x] = value;
                            x = x+1;
                        });


                    	exist_sessions = 1;
                        filtro = '<label><?php echo __('Session date');?> <?php echo $this->Html->tag('span', __('Required'), ['class' => 'label label-danger']);?></label>';
                        filtro += '<input type="text" required="required" name="session_date" id="session_date" class="form-control" readonly="readonly"/>';
                        /*filtro = '<label><?php echo __('Session date');?> <?php echo $this->Html->tag('span', __('Required'), ['class' => 'label label-danger']);?></label>';
                        filtro += '<select name="session_id" id="session_id" class="form-control"><option value="" selected><?php echo __('Select a session');?></option>';
                        $.each(response.data.sessions, function(key, value) {
                            filtro += '<option value="'+key+'">'+value+'</option>';
                        });
                        filtro += '</select></div>';*/
                        $('#session-input-div').html(filtro);


                        $('#session_date').datetimepicker({
                            useCurrent: false, //Important! See issue #1075,
                            format: "YYYY-MM-DD",
                            //maxDate: d,
                            //ignoreReadonly: false,
                            //calendarWeeks: true,
                            enabledDates: dates,
                            ignoreReadonly: true,
                            allowInputToggle: true
                        });

                        $("#session_date").on("dp.change", function (e) {
                            $('#button-div').fadeOut(300);
                            $('#sessions-day-input-div').html(<?php echo "'".$this->Html->image('ajax-loader.gif', ['style' => 'width:40px;'])."'";?>);
                            getSessionsFromDate(store_id, e.date.format('YYYY-MM-DD'));
                        });

                        $('#session-input-id').css('display', 'block');



                        //$('#button-div').fadeIn(200);
                    }
                    else{
                        $('#session-input-div').html('<?php echo $this->Html->div('alert alert-warning', __('No exist session from this store'));?>');
                        $('#button-div').fadeOut(300);
                        $('#report-response').html('');
                        //$('#session-input-id').html('');
                    }
                }
            });
    	}
        else{
            $('#session-input-div').html('');
            $('#button-div').fadeOut(300);
            $('#session-input-div').html('');
            $('#sessions-day-input-div').html('');
        }
    }


    function getSessionsFromDate(store_id, date_string){

        if(store_id != '' && date_string != ''){

            $.ajax({
                url: webroot + 'robot-sessions/getSessionsByDate/'+ store_id + '/price_differences/' + date_string,
                cache: false,
                type: 'POST',
                dataType: 'json',
                success: function (response) 
                {
                    console.log(response);

                    var filtro = '';
                    if (response.status == true) {


                        exist_sessions = 1;
                        filtro = '<label><?php echo __('Sessions');?> <?php echo $this->Html->tag('span', __('Required'), ['class' => 'label label-danger']);?></label>';
                        filtro += '<select name="robot_session_id" id="robot_session_id" class="form-control">';
                        
                        quantity = 0;
                        $.each(response.data.sessions, function(key, value) {
                            quantity = quantity + 1;
                        });


                        if(quantity == 1){
                            $.each(response.data.sessions, function(key, value) {
                                filtro += '<option value="'+key+'">'+value+'</option>';
                            });
                        }
                        else{
                            filtro += '<option value="" selected><?php echo __('Select a session');?></option>';

                            $.each(response.data.sessions, function(key, value) {
                                filtro += '<option value="'+key+'">'+value+'</option>';
                            });
                        }
                        
                        filtro += '</select></div>';

                        $('#sessions-day-input-div').html(filtro);



                        $('#button-div').fadeIn(200);
                    }
                    else{
                        $('#session-input-div').html('<?php echo $this->Html->div('alert alert-warning', __('No exist session from this store'));?>');
                        $('#button-div').fadeOut(300);
                        $('#report-response').html('');
                        //$('#session-input-id').html('');
                    }
                }
            });
        }

    }

    function clearErrors(){
    	$('#company-input-id').removeClass('has-error');
    	$('#store-input-id').removeClass('has-error');
    	$('#session-input-id').removeClass('has-error');
    }

<?php $this->Html->scriptEnd(); ?>
<h3>
    <?php echo __('Store Creation');?>        
</h3>
<?php echo $this->Form->create(null, ['url' => ['controller' => 'Stores', 'action' => 'add'], 'class' => 'form']);?>  
    <div class="panel panel-default content">
        <div class="panel-heading">
            <?php echo __('Complete the store data and location');?>
        </div>
        <div class="panel-body" id="users-data-div">
            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label><?php echo __('Company').' '.$this->Html->tag('span', __('Required'), ['class' => 'label label-danger']); ?></label>
                        <?php echo $this->Form->select('Store.company_id', $companies, ['class' => 'form-control', 'type' => 'select', 'label' => false, 'required' => 'required', 'empty' => __('Select a company')]);?>
                    </div>
                    <div class="form-group">
                        <label><?php echo __('Store name').' '.$this->Html->tag('span', __('Required'), ['class' => 'label label-danger']); ?></label>
                        <?php echo $this->Form->input('Store.store_name', ['class' => 'form-control', 'type' => 'text', 'label' => false, 'required' => 'required']);?>
                    </div>
                    <div class="form-group">
                        <label><?php echo __('Store code').' '.$this->Html->tag('span', __('Required'), ['class' => 'label label-danger']); ?></label>
                        <?php echo $this->Form->input('Store.store_code', ['class' => 'form-control', 'type' => 'text', 'label' => false, 'required' => 'required']);?>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label><?php echo __('Address Street 1').' '.$this->Html->tag('span', __('Required'), ['class' => 'label label-danger']); ?></label>
                                <?php echo $this->Form->input('Location.street_name', ['class' => 'form-control', 'type' => 'text', 'label' => false, 'required' => 'required']);?>
                            </div>
                            <div class="form-group">
                                <label><?php echo __('Address Street 2'); ?></label>
                                <?php echo $this->Form->input('Location.street_name_2', ['class' => 'form-control', 'type' => 'text', 'label' => false]);?>
                            </div>
                            <div class="form-group">
                                <label><?php echo __('Street Number').' '.$this->Html->tag('span', __('Required'), ['class' => 'label label-danger']); ?></label>
                                <?php echo $this->Form->input('Location.street_number', ['class' => 'form-control', 'type' => 'text', 'label' => false, 'required' => 'required']);?>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label><?php echo __('Country').' '.$this->Html->tag('span', __('Required'), ['class' => 'label label-danger']); ?></label>
                                <?php echo $this->Form->select('Location.country_id', $countries, ['class' => 'form-control', 'type' => 'select', 'label' => false, 'required' => 'required', 'empty' => __('Select a country'), 'id' => 'country-select-input']);?>
                            </div>

                            <div id="region-input-div"></div>
                            <div id="commune-input-div"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <?php echo $this->Form->button(__('Create'), ['type' => 'submit', 'class' => 'btn btn-danger pull-right']);?>
                </div>
            </div>                
        </div>
    </div>
<?php echo $this->Form->end();?>


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
        $("#country-select-input").change(function() {

            if($(this).val() != ''){
                var exist_stores = 0;
                var exist_sections = 0;

                $('#region-input-div').html('');
                $('#region-input-div').html(<?php echo "'".$this->Html->image('ajax-loader.gif', ['style' => 'width:40px;'])."'";?>);

                $.ajax({
                    url: webroot + 'ajax/regions/getRegionsList/',
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

                            filtro = '<div class="form-group"><label><?php echo __('Region').' '.$this->Html->tag('span', __('Required'), ['class' => 'label label-danger']);?> </label>';
                            filtro += '<select name="Location[region_id]" id="region-select-input" onchange="javascript:getCommunesList();" class="form-control" required="required"><option value="" selected><?php echo __('Select a region');?></option>';
                            $.each(response.data.regions, function(key, value) {
                                filtro += '<option value="'+key+'">'+value+'</option>';
                            });
                            filtro += '</select></div></div>';
                            $('#region-input-div').html(filtro);
                        }
                        else{
                            $('#region-input-div').html('<?php echo $this->Html->div('alert alert-warning', __('No exist regions from this country, create one ').$this->Html->link(__('here'), ['controller' => 'Regions', 'action' => 'add']));?>');
                        }
                    }
                });

            }
        });
    });

    function getCommunesList() {

        if($('#region-select-input').val() != ''){
            var exist_stores = 0;
            var exist_sections = 0;

            $('#commune-input-div').html('');
            $('#commune-input-div').html(<?php echo "'".$this->Html->image('ajax-loader.gif', ['style' => 'width:40px;'])."'";?>);

            $.ajax({
                url: webroot + 'ajax/communes/getCommunesList/',
                data: {
                    id: $('#region-select-input').val()
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

                        filtro = '<div class="form-group"><label><?php echo __('Commune').' '.$this->Html->tag('span', __('Required'), ['class' => 'label label-danger']);?> </label>';
                        filtro += '<select name="Location[commune_id]" id="commune-select-input" class="form-control" required="required"><option value="" selected><?php echo __('Select a commune');?></option>';
                        $.each(response.data.communes, function(key, value) {
                            filtro += '<option value="'+key+'">'+value+'</option>';
                        });
                        filtro += '</select></div></div>';
                        $('#commune-input-div').html(filtro);
                    }
                    else{
                        $('#commune-input-div').html('<?php echo $this->Html->div('alert alert-warning', __('No exist communes from this region, create one ').$this->Html->link(__('here'), ['controller' => 'Communes', 'action' => 'add']));?>');
                    }
                }
            });

        }
    }
<?php $this->Html->scriptEnd(); ?>
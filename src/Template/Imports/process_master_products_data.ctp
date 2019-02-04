<h3><?php echo __('User Creation');?></h3>
<?php echo $this->Form->create(null, ['class' => 'form']);?>
    <div class="panel panel-default content">
        <div class="panel-heading">
            <?php echo __('First, complete the user data');?>
        </div>
        <div class="panel-body" id="users-data-div">
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label><?php echo __('Name'); ?></label>
                        <?php echo $this->Form->select('Companies.id', $companies_list, ['class' => 'form-control', 'type' => 'select', 'label' => false, 'required' => 'required', 'id' => 'companies-id', 'empty' => __('Select a Company')]);?>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div id="store-select-div"></div>
                </div>
                <div class="col-lg-4">
                	<br>
                	<button type="submit" class="btn btn-danger pull-left" style="display:none;" id="submit-button">Create</button>
                </div>
            </div>                
        </div>
    </div>
</form>


<?php $this->Html->scriptStart(array('block' => 'scriptBottom', 'inline' => false)); ?>
	
	$(document).ready(function(){

        /*$(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });*/

        $("#companies-id").change(function() {

        	$('#store-select-div').html('');

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

                        filtro = '<label><?php echo __('Store');?></label>';
                        filtro += '<select name="Stores[id]" id="stores-id" class="form-control" required="required"><option value="" selected><?php echo __('Select a store');?></option>';
                        $.each(response.data.stores, function(key, value) {
                            filtro += '<option value="'+key+'">'+value+'</option>';
                        });
                        filtro += '</select></div>';
                        $('#store-select-div').html(filtro);

                        $('#submit-button').show();
                    }
                    else{
                        $('#store-select-div').html('<?php echo $this->Html->div('alert alert-warning', __('No exist stores from this company, create one ').$this->Html->link(__('here'), ['controller' => 'Stores', 'action' => 'add']));?>');
                        $('#submit-button').hide();
                    }
                }
            });
        });
    });

<?php $this->Html->scriptEnd(); ?>	


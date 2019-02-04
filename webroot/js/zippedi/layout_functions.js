$(document).ready(function(){
    // On change company
    $("#dropdown-loads").click(function() {

        //alert('olaj');
        //if($('#dropdown-loads').hasClass('open')){

            /*if($(this).attr("data-robotsessionid") != ''){
                alert($(this).attr("data-robotsessionid"));
            }*/
        //}


        /*if($(this).val() != ''){

            $('#section-input-div').html('');
            $('#session-input-div').html('');

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
            $('#button-div').hide();
            $('#end-input-id').css('display', 'none');
        }*/
    });
});



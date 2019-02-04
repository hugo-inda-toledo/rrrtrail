<?php echo $this->Html->script('https://maps.googleapis.com/maps/api/js?key=AIzaSyDjb3YLZVorbujhYh9NkXO5WbSSViAbMk8&;sensor=false');?>

<style>
.map_canvas {
    border-top: 0px solid #fff;
    border-bottom: 0px solid #fff;
    height: 220px;
    width: 100%;
}
</style>

<div class="row">
    <div class="col-lg-12">
        <h1>
            <?php echo $company->company_name;?> <small><?php echo __('Company information');?></small>

            <?php if($company->active == 1):?>
                <span class="label label-success" style="padding: 0.2em 1.6em .3em;font-size: 40%;"><?php echo __('Enabled');?></span>
            <?php else:?>
                <span class="label label-danger" style="padding: 0.2em 1.6em .3em;font-size: 40%;"><?php echo __('Disabled');?></span>
            <?php endif;?>
        
            <?php echo $this->Html->image('companies/'.$company->company_logo, ['class' => 'pull-right img-responsive', 'style' => 'width: 40px;']);?>
        </h1>
        <ol class="breadcrumb">
            <li>
                <?php echo $this->Html->link($this->Html->tag('span', '', ['class' => 'fa fa-building-o']).' '.__('Companies'), ['controller' => 'companies', 'action' => 'index', 'plugin' => 'management'], ['escape' => false]);?>
            </li>
            <li class="active">
                <i class="icon-file-alt"></i> <?php echo $company->company_name;?>
            </li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <?php echo $this->Html->tag('strong', $company->company_description);?>
    </div>
</div>

<?php if (!empty($company->stores)): ?>
    <div class="row">
        <div class="col-sm-6">
            <h3><?= __('Stores') ?></h3>
        </div>
        <div class="col-sm-6">
            <h3><?= __('Sections') ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="row">
                <div class="pre-scrollable" style="max-height: 420px;border: 3px solid #CCC;padding: 15px;">
                    <?php foreach ($company->stores as $store): ?>
                        <div class="col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-7">
                                            <div class="well">
                                                <h5 class="text-left">
                                                    <?php 
                                                        echo $this->Html->link(__('[{0}] {1} - {2}', [$store->store_code, $company->company_name, $store->store_name]), ['controller' => 'stores', 'action' => 'view', $store->id, 'plugin' => 'management']);
                                                    ?>
                                                </h5>
                                                <ul class="list-unstyled">
                                                    <li>
                                                        <?php echo __('{0}', $this->Html->tag('strong', $store->location->street_name.' '.$store->location->street_number.($store->location->complement != null ? ' '.$store->location->complement : '.')))?>
                                                    </li>
                                                    <li>
                                                        <?php echo __('{0}', $this->Html->tag('strong', $store->location->commune->commune_name))?>
                                                    </li>
                                                    <li>
                                                        <?php echo __('{0}', $this->Html->tag('strong', $store->location->region->region_name))?>
                                                    </li>
                                                    <li>
                                                        <?php echo __('{0}', $this->Html->tag('strong', $store->location->country->country_name))?>
                                                    </li>
                                                </ul>
                                                <?php if($store->active == 1):?>
                                                    <span class="label label-success"><?php echo __('Store enabled');?></span>
                                                <?php else:?>
                                                    <span class="label label-danger"><?php echo __('Store disabled');?></span>
                                                <?php endif;?>
                                            </div>

                                            <?php if($store->active == 1):?>
                                                    
                                                <?php echo $this->Html->link(__('Disable store'), ['controller' => 'Stores', 'action' => 'disable', $store->id, 'plugin' => 'management'], ['confirm' => __('Are you sure you wish to disable the {0} - {1} [{2}] store?', [$company->company_name, $store->store_name, $store->store_code]), 'class' => 'btn btn-default btn-xs']); ?>

                                            <?php else:?>
                                                
                                                <?php echo $this->Html->link(__('Enable store'), ['controller' => 'Stores', 'action' => 'enable', $store->id, 'plugin' => 'management'], ['confirm' => __('Are you sure you wish to disable the {0} - {1} [{2}] store?', [$company->company_name, $store->store_name, $store->store_code]), 'class' => 'btn btn-default btn-xs']); ?>

                                            <?php endif;?>
                                        </div>
                                        <div class="col-sm-5">
                                            <div id="store_map_<?php echo $store->id?>" class="map_canvas"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php $this->Html->scriptStart(array('block' => 'scriptBottom', 'inline' => false)); ?>
                
                                $(document).ready(function(){

                                    var map_<?php echo $store->id?> = new google.maps.Map(document.getElementById('store_map_<?php echo $store->id?>'), {
                                        center: {lat: <?php echo $store->location->latitude?>, lng: <?php echo $store->location->longitude?>},
                                        zoom: 15,
                                        zoomControl: true,
                                        fullscreenControl: false,
                                        streetViewControl: false,
                                        mapTypeControl: false,
                                        rotateControl: false,
                                        scaleControl: false,
                                        scrollwheel: false,
                                        disableDoubleClickZoom: true,
                                        draggable: false,
                                    });

                                    var marker_<?php echo $store->id?> = new google.maps.Marker({
                                        position: {lat: <?php echo $store->location->latitude?>, lng: <?php echo $store->location->longitude?>},
                                        map: map_<?php echo $store->id?>,
                                        animation: google.maps.Animation.DROP,
                                        title: 'Hello World!',
                                    });

                                    var infoWindow = new google.maps.InfoWindow({map: map_<?php echo $store->id?>});
                                });

                            <?php $this->Html->scriptEnd(); ?>
                        </div>
                    <?php endforeach;?>

                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-12">
                    <div class="pre-scrollable" style="max-height: 210px;border: 3px solid #CCC;padding: 15px;margin: 0px 0px 5px;">
                        <?php if (!empty($company->sections)): ?>
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <th scope="col"><?= __('Id') ?></th>
                                <th scope="col"><?= __('Company Id') ?></th>
                                <th scope="col"><?= __('Section Name') ?></th>
                                <th scope="col"><?= __('Section Code') ?></th>
                                <th scope="col"><?= __('Created') ?></th>
                                <th scope="col"><?= __('Modified') ?></th>
                                <th scope="col" class="actions"><?= __('Actions') ?></th>
                            </tr>
                            <?php foreach ($company->sections as $sections): ?>
                            <tr>
                                <td><?= h($sections->id) ?></td>
                                <td><?= h($sections->company_id) ?></td>
                                <td><?= h($sections->section_name) ?></td>
                                <td><?= h($sections->section_code) ?></td>
                                <td><?= h($sections->created) ?></td>
                                <td><?= h($sections->modified) ?></td>
                                <td class="actions">
                                    <?= $this->Html->link(__('View'), ['controller' => 'Sections', 'action' => 'view', $sections->id]) ?>
                                    <?= $this->Html->link(__('Edit'), ['controller' => 'Sections', 'action' => 'edit', $sections->id]) ?>
                                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Sections', 'action' => 'delete', $sections->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sections->id)]) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="pre-scrollable" style="max-height: 210px;border: 3px solid #CCC;padding: 15px;">
                        <h4><?= __('Related Suppliers') ?></h4>
                        <?php if (!empty($company->suppliers)): ?>
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <th scope="col"><?= __('Id') ?></th>
                                <th scope="col"><?= __('Supplier Name') ?></th>
                                <th scope="col"><?= __('Supplier Description') ?></th>
                                <th scope="col"><?= __('Supplier Keyword') ?></th>
                                <th scope="col"><?= __('Enabled') ?></th>
                                <th scope="col"><?= __('Created') ?></th>
                                <th scope="col"><?= __('Modified') ?></th>
                                <th scope="col" class="actions"><?= __('Actions') ?></th>
                            </tr>
                            <?php foreach ($company->suppliers as $suppliers): ?>
                            <tr>
                                <td><?= h($suppliers->id) ?></td>
                                <td><?= h($suppliers->supplier_name) ?></td>
                                <td><?= h($suppliers->supplier_description) ?></td>
                                <td><?= h($suppliers->supplier_keyword) ?></td>
                                <td><?= h($suppliers->enabled) ?></td>
                                <td><?= h($suppliers->created) ?></td>
                                <td><?= h($suppliers->modified) ?></td>
                                <td class="actions">
                                    <?= $this->Html->link(__('View'), ['controller' => 'Suppliers', 'action' => 'view', $suppliers->id]) ?>
                                    <?= $this->Html->link(__('Edit'), ['controller' => 'Suppliers', 'action' => 'edit', $suppliers->id]) ?>
                                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Suppliers', 'action' => 'delete', $suppliers->id], ['confirm' => __('Are you sure you want to delete # {0}?', $suppliers->id)]) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif;?>

<!--<pre>
<?php //print_r($company);?>
</pre>-->

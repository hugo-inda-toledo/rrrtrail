<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Location $location
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Location'), ['action' => 'edit', $location->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Location'), ['action' => 'delete', $location->id], ['confirm' => __('Are you sure you want to delete # {0}?', $location->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Locations'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Location'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Countries'), ['controller' => 'Countries', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Country'), ['controller' => 'Countries', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Regions'), ['controller' => 'Regions', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Region'), ['controller' => 'Regions', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Communes'), ['controller' => 'Communes', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Commune'), ['controller' => 'Communes', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="locations view large-9 medium-8 columns content">
    <h3><?= h($location->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Country') ?></th>
            <td><?= $location->has('country') ? $this->Html->link($location->country->id, ['controller' => 'Countries', 'action' => 'view', $location->country->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Region') ?></th>
            <td><?= $location->has('region') ? $this->Html->link($location->region->id, ['controller' => 'Regions', 'action' => 'view', $location->region->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Commune') ?></th>
            <td><?= $location->has('commune') ? $this->Html->link($location->commune->id, ['controller' => 'Communes', 'action' => 'view', $location->commune->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Street Name') ?></th>
            <td><?= h($location->street_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Street Name 2') ?></th>
            <td><?= h($location->street_name_2) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Street Number') ?></th>
            <td><?= h($location->street_number) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Complement') ?></th>
            <td><?= h($location->complement) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Latitude') ?></th>
            <td><?= h($location->latitude) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Longitude') ?></th>
            <td><?= h($location->longitude) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($location->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Enabled') ?></th>
            <td><?= $this->Number->format($location->enabled) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($location->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($location->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Stores') ?></h4>
        <?php if (!empty($location->stores)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Company Id') ?></th>
                <th scope="col"><?= __('Location Id') ?></th>
                <th scope="col"><?= __('Store Code') ?></th>
                <th scope="col"><?= __('Store Name') ?></th>
                <th scope="col"><?= __('Store Address') ?></th>
                <th scope="col"><?= __('Active') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($location->stores as $stores): ?>
            <tr>
                <td><?= h($stores->id) ?></td>
                <td><?= h($stores->company_id) ?></td>
                <td><?= h($stores->location_id) ?></td>
                <td><?= h($stores->store_code) ?></td>
                <td><?= h($stores->store_name) ?></td>
                <td><?= h($stores->store_address) ?></td>
                <td><?= h($stores->active) ?></td>
                <td><?= h($stores->created) ?></td>
                <td><?= h($stores->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Stores', 'action' => 'view', $stores->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Stores', 'action' => 'edit', $stores->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Stores', 'action' => 'delete', $stores->id], ['confirm' => __('Are you sure you want to delete # {0}?', $stores->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>

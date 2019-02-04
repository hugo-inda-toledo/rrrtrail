<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Commune $commune
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Commune'), ['action' => 'edit', $commune->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Commune'), ['action' => 'delete', $commune->id], ['confirm' => __('Are you sure you want to delete # {0}?', $commune->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Communes'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Commune'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Countries'), ['controller' => 'Countries', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Country'), ['controller' => 'Countries', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Regions'), ['controller' => 'Regions', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Region'), ['controller' => 'Regions', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Locations'), ['controller' => 'Locations', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Location'), ['controller' => 'Locations', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="communes view large-9 medium-8 columns content">
    <h3><?= h($commune->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Country') ?></th>
            <td><?= $commune->has('country') ? $this->Html->link($commune->country->id, ['controller' => 'Countries', 'action' => 'view', $commune->country->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Region') ?></th>
            <td><?= $commune->has('region') ? $this->Html->link($commune->region->id, ['controller' => 'Regions', 'action' => 'view', $commune->region->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Commune Name') ?></th>
            <td><?= h($commune->commune_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Commune Keyword') ?></th>
            <td><?= h($commune->commune_keyword) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($commune->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Enabled') ?></th>
            <td><?= $this->Number->format($commune->enabled) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($commune->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($commune->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Locations') ?></h4>
        <?php if (!empty($commune->locations)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Country Id') ?></th>
                <th scope="col"><?= __('Region Id') ?></th>
                <th scope="col"><?= __('Commune Id') ?></th>
                <th scope="col"><?= __('Street Name') ?></th>
                <th scope="col"><?= __('Street Name 2') ?></th>
                <th scope="col"><?= __('Street Number') ?></th>
                <th scope="col"><?= __('Complement') ?></th>
                <th scope="col"><?= __('Latitude') ?></th>
                <th scope="col"><?= __('Longitude') ?></th>
                <th scope="col"><?= __('Enabled') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($commune->locations as $locations): ?>
            <tr>
                <td><?= h($locations->id) ?></td>
                <td><?= h($locations->country_id) ?></td>
                <td><?= h($locations->region_id) ?></td>
                <td><?= h($locations->commune_id) ?></td>
                <td><?= h($locations->street_name) ?></td>
                <td><?= h($locations->street_name_2) ?></td>
                <td><?= h($locations->street_number) ?></td>
                <td><?= h($locations->complement) ?></td>
                <td><?= h($locations->latitude) ?></td>
                <td><?= h($locations->longitude) ?></td>
                <td><?= h($locations->enabled) ?></td>
                <td><?= h($locations->created) ?></td>
                <td><?= h($locations->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Locations', 'action' => 'view', $locations->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Locations', 'action' => 'edit', $locations->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Locations', 'action' => 'delete', $locations->id], ['confirm' => __('Are you sure you want to delete # {0}?', $locations->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>

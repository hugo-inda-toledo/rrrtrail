<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Region $region
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Region'), ['action' => 'edit', $region->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Region'), ['action' => 'delete', $region->id], ['confirm' => __('Are you sure you want to delete # {0}?', $region->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Regions'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Region'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Countries'), ['controller' => 'Countries', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Country'), ['controller' => 'Countries', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Communes'), ['controller' => 'Communes', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Commune'), ['controller' => 'Communes', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Locations'), ['controller' => 'Locations', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Location'), ['controller' => 'Locations', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="regions view large-9 medium-8 columns content">
    <h3><?= h($region->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Country') ?></th>
            <td><?= $region->has('country') ? $this->Html->link($region->country->id, ['controller' => 'Countries', 'action' => 'view', $region->country->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Region Name') ?></th>
            <td><?= h($region->region_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Region Keyword') ?></th>
            <td><?= h($region->region_keyword) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($region->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Enabled') ?></th>
            <td><?= $this->Number->format($region->enabled) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($region->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($region->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Communes') ?></h4>
        <?php if (!empty($region->communes)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Country Id') ?></th>
                <th scope="col"><?= __('Region Id') ?></th>
                <th scope="col"><?= __('Commune Name') ?></th>
                <th scope="col"><?= __('Commune Keyword') ?></th>
                <th scope="col"><?= __('Enabled') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($region->communes as $communes): ?>
            <tr>
                <td><?= h($communes->id) ?></td>
                <td><?= h($communes->country_id) ?></td>
                <td><?= h($communes->region_id) ?></td>
                <td><?= h($communes->commune_name) ?></td>
                <td><?= h($communes->commune_keyword) ?></td>
                <td><?= h($communes->enabled) ?></td>
                <td><?= h($communes->created) ?></td>
                <td><?= h($communes->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Communes', 'action' => 'view', $communes->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Communes', 'action' => 'edit', $communes->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Communes', 'action' => 'delete', $communes->id], ['confirm' => __('Are you sure you want to delete # {0}?', $communes->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Locations') ?></h4>
        <?php if (!empty($region->locations)): ?>
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
            <?php foreach ($region->locations as $locations): ?>
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

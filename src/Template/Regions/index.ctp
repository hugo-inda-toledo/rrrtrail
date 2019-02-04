<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Region[]|\Cake\Collection\CollectionInterface $regions
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Region'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Countries'), ['controller' => 'Countries', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Country'), ['controller' => 'Countries', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Communes'), ['controller' => 'Communes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Commune'), ['controller' => 'Communes', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Locations'), ['controller' => 'Locations', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Location'), ['controller' => 'Locations', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="regions index large-9 medium-8 columns content">
    <h3><?= __('Regions') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('country_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('region_name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('region_keyword') ?></th>
                <th scope="col"><?= $this->Paginator->sort('enabled') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($regions as $region): ?>
            <tr>
                <td><?= $this->Number->format($region->id) ?></td>
                <td><?= $region->has('country') ? $this->Html->link($region->country->id, ['controller' => 'Countries', 'action' => 'view', $region->country->id]) : '' ?></td>
                <td><?= h($region->region_name) ?></td>
                <td><?= h($region->region_keyword) ?></td>
                <td><?= $this->Number->format($region->enabled) ?></td>
                <td><?= h($region->created) ?></td>
                <td><?= h($region->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $region->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $region->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $region->id], ['confirm' => __('Are you sure you want to delete # {0}?', $region->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>

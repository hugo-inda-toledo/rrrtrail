<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Commune[]|\Cake\Collection\CollectionInterface $communes
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Commune'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Countries'), ['controller' => 'Countries', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Country'), ['controller' => 'Countries', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Regions'), ['controller' => 'Regions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Region'), ['controller' => 'Regions', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Locations'), ['controller' => 'Locations', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Location'), ['controller' => 'Locations', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="communes index large-9 medium-8 columns content">
    <h3><?= __('Communes') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('country_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('region_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('commune_name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('commune_keyword') ?></th>
                <th scope="col"><?= $this->Paginator->sort('enabled') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($communes as $commune): ?>
            <tr>
                <td><?= $this->Number->format($commune->id) ?></td>
                <td><?= $commune->has('country') ? $this->Html->link($commune->country->id, ['controller' => 'Countries', 'action' => 'view', $commune->country->id]) : '' ?></td>
                <td><?= $commune->has('region') ? $this->Html->link($commune->region->id, ['controller' => 'Regions', 'action' => 'view', $commune->region->id]) : '' ?></td>
                <td><?= h($commune->commune_name) ?></td>
                <td><?= h($commune->commune_keyword) ?></td>
                <td><?= $this->Number->format($commune->enabled) ?></td>
                <td><?= h($commune->created) ?></td>
                <td><?= h($commune->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $commune->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $commune->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $commune->id], ['confirm' => __('Are you sure you want to delete # {0}?', $commune->id)]) ?>
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

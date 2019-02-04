<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MeasurementUnit[]|\Cake\Collection\CollectionInterface $measurementUnits
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Measurement Unit'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="measurementUnits index large-9 medium-8 columns content">
    <h3><?= __('Measurement Units') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('unit_name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('unit_plural_name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('unit_code') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($measurementUnits as $measurementUnit): ?>
            <tr>
                <td><?= $this->Number->format($measurementUnit->id) ?></td>
                <td><?= h($measurementUnit->unit_name) ?></td>
                <td><?= h($measurementUnit->unit_plural_name) ?></td>
                <td><?= h($measurementUnit->unit_code) ?></td>
                <td><?= h($measurementUnit->created) ?></td>
                <td><?= h($measurementUnit->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $measurementUnit->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $measurementUnit->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $measurementUnit->id], ['confirm' => __('Are you sure you want to delete # {0}?', $measurementUnit->id)]) ?>
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

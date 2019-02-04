<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UsersSupplier[]|\Cake\Collection\CollectionInterface $usersSuppliers
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Users Supplier'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Suppliers'), ['controller' => 'Suppliers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Supplier'), ['controller' => 'Suppliers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sections'), ['controller' => 'Sections', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Section'), ['controller' => 'Sections', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="usersSuppliers index large-9 medium-8 columns content">
    <h3><?= __('Users Suppliers') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('supplier_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('company_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('store_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('section_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('enabled') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usersSuppliers as $usersSupplier): ?>
            <tr>
                <td><?= $this->Number->format($usersSupplier->id) ?></td>
                <td><?= $usersSupplier->has('user') ? $this->Html->link($usersSupplier->user->full_name, ['controller' => 'Users', 'action' => 'view', $usersSupplier->user->id]) : '' ?></td>
                <td><?= $usersSupplier->has('supplier') ? $this->Html->link($usersSupplier->supplier->id, ['controller' => 'Suppliers', 'action' => 'view', $usersSupplier->supplier->id]) : '' ?></td>
                <td><?= $usersSupplier->has('company') ? $this->Html->link($usersSupplier->company->company_name, ['controller' => 'Companies', 'action' => 'view', $usersSupplier->company->id]) : '' ?></td>
                <td><?= $usersSupplier->has('store') ? $this->Html->link($usersSupplier->store->full_name, ['controller' => 'Stores', 'action' => 'view', $usersSupplier->store->id]) : '' ?></td>
                <td><?= $usersSupplier->has('section') ? $this->Html->link($usersSupplier->section->section_name, ['controller' => 'Sections', 'action' => 'view', $usersSupplier->section->id]) : '' ?></td>
                <td><?= $this->Number->format($usersSupplier->enabled) ?></td>
                <td><?= h($usersSupplier->created) ?></td>
                <td><?= h($usersSupplier->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $usersSupplier->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $usersSupplier->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $usersSupplier->id], ['confirm' => __('Are you sure you want to delete # {0}?', $usersSupplier->id)]) ?>
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

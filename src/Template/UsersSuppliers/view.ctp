<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UsersSupplier $usersSupplier
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Users Supplier'), ['action' => 'edit', $usersSupplier->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Users Supplier'), ['action' => 'delete', $usersSupplier->id], ['confirm' => __('Are you sure you want to delete # {0}?', $usersSupplier->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Users Suppliers'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Users Supplier'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Suppliers'), ['controller' => 'Suppliers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Supplier'), ['controller' => 'Suppliers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sections'), ['controller' => 'Sections', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Section'), ['controller' => 'Sections', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="usersSuppliers view large-9 medium-8 columns content">
    <h3><?= h($usersSupplier->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $usersSupplier->has('user') ? $this->Html->link($usersSupplier->user->full_name, ['controller' => 'Users', 'action' => 'view', $usersSupplier->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Supplier') ?></th>
            <td><?= $usersSupplier->has('supplier') ? $this->Html->link($usersSupplier->supplier->id, ['controller' => 'Suppliers', 'action' => 'view', $usersSupplier->supplier->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Company') ?></th>
            <td><?= $usersSupplier->has('company') ? $this->Html->link($usersSupplier->company->company_name, ['controller' => 'Companies', 'action' => 'view', $usersSupplier->company->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Store') ?></th>
            <td><?= $usersSupplier->has('store') ? $this->Html->link($usersSupplier->store->full_name, ['controller' => 'Stores', 'action' => 'view', $usersSupplier->store->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Section') ?></th>
            <td><?= $usersSupplier->has('section') ? $this->Html->link($usersSupplier->section->section_name, ['controller' => 'Sections', 'action' => 'view', $usersSupplier->section->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($usersSupplier->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Enabled') ?></th>
            <td><?= $this->Number->format($usersSupplier->enabled) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($usersSupplier->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($usersSupplier->modified) ?></td>
        </tr>
    </table>
</div>

<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SuppliersCompany $suppliersCompany
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Suppliers Company'), ['action' => 'edit', $suppliersCompany->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Suppliers Company'), ['action' => 'delete', $suppliersCompany->id], ['confirm' => __('Are you sure you want to delete # {0}?', $suppliersCompany->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Suppliers Companies'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Suppliers Company'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Suppliers'), ['controller' => 'Suppliers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Supplier'), ['controller' => 'Suppliers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="suppliersCompanies view large-9 medium-8 columns content">
    <h3><?= h($suppliersCompany->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Supplier') ?></th>
            <td><?= $suppliersCompany->has('supplier') ? $this->Html->link($suppliersCompany->supplier->id, ['controller' => 'Suppliers', 'action' => 'view', $suppliersCompany->supplier->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Company') ?></th>
            <td><?= $suppliersCompany->has('company') ? $this->Html->link($suppliersCompany->company->company_name, ['controller' => 'Companies', 'action' => 'view', $suppliersCompany->company->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($suppliersCompany->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Enabled') ?></th>
            <td><?= $this->Number->format($suppliersCompany->enabled) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($suppliersCompany->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($suppliersCompany->modified) ?></td>
        </tr>
    </table>
</div>

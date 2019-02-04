<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SuppliersCompany[]|\Cake\Collection\CollectionInterface $suppliersCompanies
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Suppliers Company'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Suppliers'), ['controller' => 'Suppliers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Supplier'), ['controller' => 'Suppliers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="suppliersCompanies index large-9 medium-8 columns content">
    <h3><?= __('Suppliers Companies') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('supplier_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('company_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('enabled') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($suppliersCompanies as $suppliersCompany): ?>
            <tr>
                <td><?= $this->Number->format($suppliersCompany->id) ?></td>
                <td><?= $suppliersCompany->has('supplier') ? $this->Html->link($suppliersCompany->supplier->id, ['controller' => 'Suppliers', 'action' => 'view', $suppliersCompany->supplier->id]) : '' ?></td>
                <td><?= $suppliersCompany->has('company') ? $this->Html->link($suppliersCompany->company->company_name, ['controller' => 'Companies', 'action' => 'view', $suppliersCompany->company->id]) : '' ?></td>
                <td><?= $this->Number->format($suppliersCompany->enabled) ?></td>
                <td><?= h($suppliersCompany->created) ?></td>
                <td><?= h($suppliersCompany->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $suppliersCompany->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $suppliersCompany->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $suppliersCompany->id], ['confirm' => __('Are you sure you want to delete # {0}?', $suppliersCompany->id)]) ?>
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

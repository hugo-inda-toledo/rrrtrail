<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UsersCompany[]|\Cake\Collection\CollectionInterface $usersCompanies
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Users Company'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sections'), ['controller' => 'Sections', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Section'), ['controller' => 'Sections', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="usersCompanies index large-9 medium-8 columns content">
    <h3><?= __('Users Companies') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
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
            <?php foreach ($usersCompanies as $usersCompany): ?>
            <tr>
                <td><?= $this->Number->format($usersCompany->id) ?></td>
                <td><?= $usersCompany->has('user') ? $this->Html->link($usersCompany->user->full_name, ['controller' => 'Users', 'action' => 'view', $usersCompany->user->id]) : '' ?></td>
                <td><?= $usersCompany->has('company') ? $this->Html->link($usersCompany->company->company_name, ['controller' => 'Companies', 'action' => 'view', $usersCompany->company->id]) : '' ?></td>
                <td><?= $usersCompany->has('store') ? $this->Html->link($usersCompany->store->full_name, ['controller' => 'Stores', 'action' => 'view', $usersCompany->store->id]) : '' ?></td>
                <td><?= $usersCompany->has('section') ? $this->Html->link($usersCompany->section->section_name, ['controller' => 'Sections', 'action' => 'view', $usersCompany->section->id]) : '' ?></td>
                <td><?= $this->Number->format($usersCompany->enabled) ?></td>
                <td><?= h($usersCompany->created) ?></td>
                <td><?= h($usersCompany->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $usersCompany->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $usersCompany->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $usersCompany->id], ['confirm' => __('Are you sure you want to delete # {0}?', $usersCompany->id)]) ?>
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

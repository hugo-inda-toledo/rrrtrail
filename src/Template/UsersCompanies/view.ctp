<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UsersCompany $usersCompany
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Users Company'), ['action' => 'edit', $usersCompany->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Users Company'), ['action' => 'delete', $usersCompany->id], ['confirm' => __('Are you sure you want to delete # {0}?', $usersCompany->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Users Companies'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Users Company'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sections'), ['controller' => 'Sections', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Section'), ['controller' => 'Sections', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="usersCompanies view large-9 medium-8 columns content">
    <h3><?= h($usersCompany->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $usersCompany->has('user') ? $this->Html->link($usersCompany->user->full_name, ['controller' => 'Users', 'action' => 'view', $usersCompany->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Company') ?></th>
            <td><?= $usersCompany->has('company') ? $this->Html->link($usersCompany->company->company_name, ['controller' => 'Companies', 'action' => 'view', $usersCompany->company->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Store') ?></th>
            <td><?= $usersCompany->has('store') ? $this->Html->link($usersCompany->store->full_name, ['controller' => 'Stores', 'action' => 'view', $usersCompany->store->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Section') ?></th>
            <td><?= $usersCompany->has('section') ? $this->Html->link($usersCompany->section->section_name, ['controller' => 'Sections', 'action' => 'view', $usersCompany->section->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($usersCompany->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Enabled') ?></th>
            <td><?= $this->Number->format($usersCompany->enabled) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($usersCompany->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($usersCompany->modified) ?></td>
        </tr>
    </table>
</div>

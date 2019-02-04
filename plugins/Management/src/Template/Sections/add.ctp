<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface $section
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Sections'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Products Stores'), ['controller' => 'ProductsStores', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Products Store'), ['controller' => 'ProductsStores', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users Companies'), ['controller' => 'UsersCompanies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Users Company'), ['controller' => 'UsersCompanies', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users Suppliers'), ['controller' => 'UsersSuppliers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Users Supplier'), ['controller' => 'UsersSuppliers', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sections form large-9 medium-8 columns content">
    <?= $this->Form->create($section) ?>
    <fieldset>
        <legend><?= __('Add Section') ?></legend>
        <?php
            echo $this->Form->control('company_id', ['options' => $companies]);
            echo $this->Form->control('section_name');
            echo $this->Form->control('section_code');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

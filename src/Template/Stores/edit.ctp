<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Store $store
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $store->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $store->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Stores'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Locations'), ['controller' => 'Locations', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Location'), ['controller' => 'Locations', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Aisles'), ['controller' => 'Aisles', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Aisle'), ['controller' => 'Aisles', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users Companies'), ['controller' => 'UsersCompanies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Users Company'), ['controller' => 'UsersCompanies', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users Suppliers'), ['controller' => 'UsersSuppliers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Users Supplier'), ['controller' => 'UsersSuppliers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="stores form large-9 medium-8 columns content">
    <?= $this->Form->create($store) ?>
    <fieldset>
        <legend><?= __('Edit Store') ?></legend>
        <?php
            echo $this->Form->control('company_id', ['options' => $companies]);
            echo $this->Form->control('location_id', ['options' => $locations]);
            echo $this->Form->control('store_code');
            echo $this->Form->control('store_name');
            echo $this->Form->control('store_address');
            echo $this->Form->control('active');
            echo $this->Form->control('products._ids', ['options' => $products]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

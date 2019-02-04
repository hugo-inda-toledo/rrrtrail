<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $product->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $product->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Products'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Measurement Units'), ['controller' => 'MeasurementUnits', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Measurement Unit'), ['controller' => 'MeasurementUnits', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Suppliers'), ['controller' => 'Suppliers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Supplier'), ['controller' => 'Suppliers', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="products form large-9 medium-8 columns content">
    <?= $this->Form->create($product) ?>
    <fieldset>
        <legend><?= __('Edit Product') ?></legend>
        <?php
            echo $this->Form->control('measurement_unit_id', ['options' => $measurementUnits, 'empty' => true]);
            echo $this->Form->control('product_name');
            echo $this->Form->control('product_description');
            echo $this->Form->control('stripped');
            echo $this->Form->control('ean13');
            echo $this->Form->control('ean13_digit');
            echo $this->Form->control('bar_type');
            echo $this->Form->control('hierarchy');
            echo $this->Form->control('last_update');
            echo $this->Form->control('tax');
            echo $this->Form->control('ppums_amount');
            echo $this->Form->control('weighable');
            echo $this->Form->control('stores._ids', ['options' => $stores]);
            echo $this->Form->control('suppliers._ids', ['options' => $suppliers]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MeasurementUnit $measurementUnit
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $measurementUnit->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $measurementUnit->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Measurement Units'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="measurementUnits form large-9 medium-8 columns content">
    <?= $this->Form->create($measurementUnit) ?>
    <fieldset>
        <legend><?= __('Edit Measurement Unit') ?></legend>
        <?php
            echo $this->Form->control('unit_name');
            echo $this->Form->control('unit_plural_name');
            echo $this->Form->control('unit_code');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

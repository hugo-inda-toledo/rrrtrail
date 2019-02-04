<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SuppliersCompany $suppliersCompany
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Suppliers Companies'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Suppliers'), ['controller' => 'Suppliers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Supplier'), ['controller' => 'Suppliers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="suppliersCompanies form large-9 medium-8 columns content">
    <?= $this->Form->create($suppliersCompany) ?>
    <fieldset>
        <legend><?= __('Add Suppliers Company') ?></legend>
        <?php
            echo $this->Form->control('supplier_id', ['options' => $suppliers]);
            echo $this->Form->control('company_id', ['options' => $companies]);
            echo $this->Form->control('enabled');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

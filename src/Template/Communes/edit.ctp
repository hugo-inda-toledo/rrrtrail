<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Commune $commune
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $commune->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $commune->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Communes'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Countries'), ['controller' => 'Countries', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Country'), ['controller' => 'Countries', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Regions'), ['controller' => 'Regions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Region'), ['controller' => 'Regions', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Locations'), ['controller' => 'Locations', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Location'), ['controller' => 'Locations', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="communes form large-9 medium-8 columns content">
    <?= $this->Form->create($commune) ?>
    <fieldset>
        <legend><?= __('Edit Commune') ?></legend>
        <?php
            echo $this->Form->control('country_id', ['options' => $countries]);
            echo $this->Form->control('region_id', ['options' => $regions]);
            echo $this->Form->control('commune_name');
            echo $this->Form->control('commune_keyword');
            echo $this->Form->control('enabled');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

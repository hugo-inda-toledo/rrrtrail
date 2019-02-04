<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface $robotReport
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $robotReport->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $robotReport->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Robot Reports'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="robotReports form large-9 medium-8 columns content">
    <?= $this->Form->create($robotReport) ?>
    <fieldset>
        <legend><?= __('Edit Robot Report') ?></legend>
        <?php
            echo $this->Form->control('report_name');
            echo $this->Form->control('report_description');
            echo $this->Form->control('report_keyword');
            echo $this->Form->control('report_icon');
            echo $this->Form->control('active');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

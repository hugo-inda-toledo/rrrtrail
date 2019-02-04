<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface $robotReport
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Robot Report'), ['action' => 'edit', $robotReport->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Robot Report'), ['action' => 'delete', $robotReport->id], ['confirm' => __('Are you sure you want to delete # {0}?', $robotReport->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Robot Reports'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Robot Report'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="robotReports view large-9 medium-8 columns content">
    <h3><?= h($robotReport->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Report Name') ?></th>
            <td><?= h($robotReport->report_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Report Keyword') ?></th>
            <td><?= h($robotReport->report_keyword) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Report Icon') ?></th>
            <td><?= h($robotReport->report_icon) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($robotReport->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Active') ?></th>
            <td><?= $this->Number->format($robotReport->active) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($robotReport->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($robotReport->modified) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Report Description') ?></h4>
        <?= $this->Text->autoParagraph(h($robotReport->report_description)); ?>
    </div>
</div>

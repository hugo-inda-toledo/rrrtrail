<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MeasurementUnit $measurementUnit
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Measurement Unit'), ['action' => 'edit', $measurementUnit->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Measurement Unit'), ['action' => 'delete', $measurementUnit->id], ['confirm' => __('Are you sure you want to delete # {0}?', $measurementUnit->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Measurement Units'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Measurement Unit'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="measurementUnits view large-9 medium-8 columns content">
    <h3><?= h($measurementUnit->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Unit Name') ?></th>
            <td><?= h($measurementUnit->unit_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Unit Plural Name') ?></th>
            <td><?= h($measurementUnit->unit_plural_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Unit Code') ?></th>
            <td><?= h($measurementUnit->unit_code) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($measurementUnit->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($measurementUnit->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($measurementUnit->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Products') ?></h4>
        <?php if (!empty($measurementUnit->products)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Measurement Unit Id') ?></th>
                <th scope="col"><?= __('Product Name') ?></th>
                <th scope="col"><?= __('Product Description') ?></th>
                <th scope="col"><?= __('Stripped') ?></th>
                <th scope="col"><?= __('Ean13') ?></th>
                <th scope="col"><?= __('Ean13 Digit') ?></th>
                <th scope="col"><?= __('Bar Type') ?></th>
                <th scope="col"><?= __('Hierarchy') ?></th>
                <th scope="col"><?= __('Last Update') ?></th>
                <th scope="col"><?= __('Tax') ?></th>
                <th scope="col"><?= __('Ppums Amount') ?></th>
                <th scope="col"><?= __('Weighable') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($measurementUnit->products as $products): ?>
            <tr>
                <td><?= h($products->id) ?></td>
                <td><?= h($products->measurement_unit_id) ?></td>
                <td><?= h($products->product_name) ?></td>
                <td><?= h($products->product_description) ?></td>
                <td><?= h($products->stripped) ?></td>
                <td><?= h($products->ean13) ?></td>
                <td><?= h($products->ean13_digit) ?></td>
                <td><?= h($products->bar_type) ?></td>
                <td><?= h($products->hierarchy) ?></td>
                <td><?= h($products->last_update) ?></td>
                <td><?= h($products->tax) ?></td>
                <td><?= h($products->ppums_amount) ?></td>
                <td><?= h($products->weighable) ?></td>
                <td><?= h($products->created) ?></td>
                <td><?= h($products->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Products', 'action' => 'view', $products->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Products', 'action' => 'edit', $products->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Products', 'action' => 'delete', $products->id], ['confirm' => __('Are you sure you want to delete # {0}?', $products->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>

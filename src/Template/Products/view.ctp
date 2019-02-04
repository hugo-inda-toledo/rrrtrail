<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Product'), ['action' => 'edit', $product->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Product'), ['action' => 'delete', $product->id], ['confirm' => __('Are you sure you want to delete # {0}?', $product->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Products'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Measurement Units'), ['controller' => 'MeasurementUnits', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Measurement Unit'), ['controller' => 'MeasurementUnits', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Suppliers'), ['controller' => 'Suppliers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Supplier'), ['controller' => 'Suppliers', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="products view large-9 medium-8 columns content">
    <h3><?= h($product->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Measurement Unit') ?></th>
            <td><?= $product->has('measurement_unit') ? $this->Html->link($product->measurement_unit->id, ['controller' => 'MeasurementUnits', 'action' => 'view', $product->measurement_unit->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Product Name') ?></th>
            <td><?= h($product->product_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Product Description') ?></th>
            <td><?= h($product->product_description) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ean13') ?></th>
            <td><?= h($product->ean13) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ean13 Digit') ?></th>
            <td><?= h($product->ean13_digit) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Bar Type') ?></th>
            <td><?= h($product->bar_type) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Hierarchy') ?></th>
            <td><?= h($product->hierarchy) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($product->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Stripped') ?></th>
            <td><?= $this->Number->format($product->stripped) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tax') ?></th>
            <td><?= $this->Number->format($product->tax) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ppums Amount') ?></th>
            <td><?= $this->Number->format($product->ppums_amount) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Weighable') ?></th>
            <td><?= $this->Number->format($product->weighable) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Last Update') ?></th>
            <td><?= h($product->last_update) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($product->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($product->modified) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Product Description') ?></h4>
        <?= $this->Text->autoParagraph(h($product->product_description)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Stores') ?></h4>
        <?php if (!empty($product->stores)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Company Id') ?></th>
                <th scope="col"><?= __('Location Id') ?></th>
                <th scope="col"><?= __('Store Code') ?></th>
                <th scope="col"><?= __('Store Name') ?></th>
                <th scope="col"><?= __('Store Address') ?></th>
                <th scope="col"><?= __('Active') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($product->stores as $stores): ?>
            <tr>
                <td><?= h($stores->id) ?></td>
                <td><?= h($stores->company_id) ?></td>
                <td><?= h($stores->location_id) ?></td>
                <td><?= h($stores->store_code) ?></td>
                <td><?= h($stores->store_name) ?></td>
                <td><?= h($stores->store_address) ?></td>
                <td><?= h($stores->active) ?></td>
                <td><?= h($stores->created) ?></td>
                <td><?= h($stores->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Stores', 'action' => 'view', $stores->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Stores', 'action' => 'edit', $stores->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Stores', 'action' => 'delete', $stores->id], ['confirm' => __('Are you sure you want to delete # {0}?', $stores->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Suppliers') ?></h4>
        <?php if (!empty($product->suppliers)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Supplier Name') ?></th>
                <th scope="col"><?= __('Supplier Description') ?></th>
                <th scope="col"><?= __('Supplier Keyword') ?></th>
                <th scope="col"><?= __('Enabled') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($product->suppliers as $suppliers): ?>
            <tr>
                <td><?= h($suppliers->id) ?></td>
                <td><?= h($suppliers->supplier_name) ?></td>
                <td><?= h($suppliers->supplier_description) ?></td>
                <td><?= h($suppliers->supplier_keyword) ?></td>
                <td><?= h($suppliers->enabled) ?></td>
                <td><?= h($suppliers->created) ?></td>
                <td><?= h($suppliers->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Suppliers', 'action' => 'view', $suppliers->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Suppliers', 'action' => 'edit', $suppliers->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Suppliers', 'action' => 'delete', $suppliers->id], ['confirm' => __('Are you sure you want to delete # {0}?', $suppliers->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>

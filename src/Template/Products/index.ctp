<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product[]|\Cake\Collection\CollectionInterface $products
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Product'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Measurement Units'), ['controller' => 'MeasurementUnits', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Measurement Unit'), ['controller' => 'MeasurementUnits', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Suppliers'), ['controller' => 'Suppliers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Supplier'), ['controller' => 'Suppliers', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="table-responsive">
    <h3><?= __('Products') ?></h3>
    <table cellpadding="0" cellspacing="0" class="table">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('measurement_unit_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('product_name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('stripped') ?></th>
                <th scope="col"><?= $this->Paginator->sort('ean13') ?></th>
                <th scope="col"><?= $this->Paginator->sort('bar_type') ?></th>
                <th scope="col"><?= $this->Paginator->sort('hierarchy') ?></th>
                <th scope="col"><?= $this->Paginator->sort('last_update') ?></th>
                <th scope="col"><?= $this->Paginator->sort('tax') ?></th>
                <th scope="col"><?= $this->Paginator->sort('ppums_amount') ?></th>
                <th scope="col"><?= $this->Paginator->sort('weighable') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?= $this->Number->format($product->id) ?></td>
                <td><?= $product->has('measurement_unit') ? $this->Html->link($product->measurement_unit->id, ['controller' => 'MeasurementUnits', 'action' => 'view', $product->measurement_unit->id]) : '' ?></td>
                <td><?= h($product->product_name) ?></td>
                <td><?= $this->Number->format($product->stripped) ?></td>
                <td><?php echo $this->Ean->format($product->ean13, $product->ean13_digit); ?></td>
                <td><?= h($product->bar_type) ?></td>
                <td><?= h($product->hierarchy) ?></td>
                <td><?= h($product->last_update) ?></td>
                <td><?= $this->Number->format($product->tax) ?></td>
                <td><?= $this->Number->format($product->ppums_amount) ?></td>
                <td><?= $this->Number->format($product->weighable) ?></td>
                <td><?= h($product->created) ?></td>
                <td><?= h($product->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $product->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $product->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $product->id], ['confirm' => __('Are you sure you want to delete # {0}?', $product->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>

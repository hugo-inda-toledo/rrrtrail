<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductsStore[]|\Cake\Collection\CollectionInterface $productsStores
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Products Store'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Aisles'), ['controller' => 'Aisles', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Aisle'), ['controller' => 'Aisles', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sections'), ['controller' => 'Sections', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Section'), ['controller' => 'Sections', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sub Categories'), ['controller' => 'SubCategories', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sub Category'), ['controller' => 'SubCategories', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="productsStores index large-9 medium-8 columns content">
    <h3><?= __('Products Stores') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('product_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('company_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('store_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('aisle_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('section_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('category_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sub_category_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('strip_price') ?></th>
                <th scope="col"><?= $this->Paginator->sort('readed_date') ?></th>
                <th scope="col"><?= $this->Paginator->sort('company_internal_code') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productsStores as $productsStore): ?>
            <tr>
                <td><?= $this->Number->format($productsStore->id) ?></td>
                <td><?= $productsStore->has('product') ? $this->Html->link($productsStore->product->id, ['controller' => 'Products', 'action' => 'view', $productsStore->product->id]) : '' ?></td>
                <td><?= $productsStore->has('company') ? $this->Html->link($productsStore->company->company_name, ['controller' => 'Companies', 'action' => 'view', $productsStore->company->id]) : '' ?></td>
                <td><?= $productsStore->has('store') ? $this->Html->link($productsStore->store->full_name, ['controller' => 'Stores', 'action' => 'view', $productsStore->store->id]) : '' ?></td>
                <td><?= $productsStore->has('aisle') ? $this->Html->link($productsStore->aisle->id, ['controller' => 'Aisles', 'action' => 'view', $productsStore->aisle->id]) : '' ?></td>
                <td><?= $productsStore->has('section') ? $this->Html->link($productsStore->section->section_name, ['controller' => 'Sections', 'action' => 'view', $productsStore->section->id]) : '' ?></td>
                <td><?= $productsStore->has('category') ? $this->Html->link($productsStore->category->id, ['controller' => 'Categories', 'action' => 'view', $productsStore->category->id]) : '' ?></td>
                <td><?= $productsStore->has('sub_category') ? $this->Html->link($productsStore->sub_category->id, ['controller' => 'SubCategories', 'action' => 'view', $productsStore->sub_category->id]) : '' ?></td>
                <td><?= h($productsStore->strip_price) ?></td>
                <td><?= h($productsStore->readed_date) ?></td>
                <td><?= h($productsStore->company_internal_code) ?></td>
                <td><?= h($productsStore->created) ?></td>
                <td><?= h($productsStore->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $productsStore->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $productsStore->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $productsStore->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productsStore->id)]) ?>
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

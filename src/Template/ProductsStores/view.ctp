<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductsStore $productsStore
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Products Store'), ['action' => 'edit', $productsStore->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Products Store'), ['action' => 'delete', $productsStore->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productsStore->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Products Stores'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Products Store'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Aisles'), ['controller' => 'Aisles', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Aisle'), ['controller' => 'Aisles', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sections'), ['controller' => 'Sections', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Section'), ['controller' => 'Sections', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sub Categories'), ['controller' => 'SubCategories', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sub Category'), ['controller' => 'SubCategories', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="productsStores view large-9 medium-8 columns content">
    <h3><?= h($productsStore->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Product') ?></th>
            <td><?= $productsStore->has('product') ? $this->Html->link($productsStore->product->id, ['controller' => 'Products', 'action' => 'view', $productsStore->product->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Company') ?></th>
            <td><?= $productsStore->has('company') ? $this->Html->link($productsStore->company->company_name, ['controller' => 'Companies', 'action' => 'view', $productsStore->company->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Store') ?></th>
            <td><?= $productsStore->has('store') ? $this->Html->link($productsStore->store->full_name, ['controller' => 'Stores', 'action' => 'view', $productsStore->store->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Aisle') ?></th>
            <td><?= $productsStore->has('aisle') ? $this->Html->link($productsStore->aisle->id, ['controller' => 'Aisles', 'action' => 'view', $productsStore->aisle->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Section') ?></th>
            <td><?= $productsStore->has('section') ? $this->Html->link($productsStore->section->section_name, ['controller' => 'Sections', 'action' => 'view', $productsStore->section->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Category') ?></th>
            <td><?= $productsStore->has('category') ? $this->Html->link($productsStore->category->id, ['controller' => 'Categories', 'action' => 'view', $productsStore->category->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sub Category') ?></th>
            <td><?= $productsStore->has('sub_category') ? $this->Html->link($productsStore->sub_category->id, ['controller' => 'SubCategories', 'action' => 'view', $productsStore->sub_category->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Strip Price') ?></th>
            <td><?= h($productsStore->strip_price) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Readed Date') ?></th>
            <td><?= h($productsStore->readed_date) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Company Internal Code') ?></th>
            <td><?= h($productsStore->company_internal_code) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($productsStore->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($productsStore->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($productsStore->modified) ?></td>
        </tr>
    </table>
</div>

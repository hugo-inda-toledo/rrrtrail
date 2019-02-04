<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface $category
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Category'), ['action' => 'edit', $category->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Category'), ['action' => 'delete', $category->id], ['confirm' => __('Are you sure you want to delete # {0}?', $category->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Categories'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Category'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sections'), ['controller' => 'Sections', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Section'), ['controller' => 'Sections', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Products Stores'), ['controller' => 'ProductsStores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Products Store'), ['controller' => 'ProductsStores', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sub Categories'), ['controller' => 'SubCategories', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sub Category'), ['controller' => 'SubCategories', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="categories view large-9 medium-8 columns content">
    <h3><?= h($category->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Company') ?></th>
            <td><?= $category->has('company') ? $this->Html->link($category->company->id, ['controller' => 'Companies', 'action' => 'view', $category->company->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Section') ?></th>
            <td><?= $category->has('section') ? $this->Html->link($category->section->id, ['controller' => 'Sections', 'action' => 'view', $category->section->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Category Name') ?></th>
            <td><?= h($category->category_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Category Code') ?></th>
            <td><?= h($category->category_code) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($category->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($category->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($category->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Products Stores') ?></h4>
        <?php if (!empty($category->products_stores)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Product Id') ?></th>
                <th scope="col"><?= __('Company Id') ?></th>
                <th scope="col"><?= __('Store Id') ?></th>
                <th scope="col"><?= __('Aisle Id') ?></th>
                <th scope="col"><?= __('Section Id') ?></th>
                <th scope="col"><?= __('Category Id') ?></th>
                <th scope="col"><?= __('Sub Category Id') ?></th>
                <th scope="col"><?= __('Strip Price') ?></th>
                <th scope="col"><?= __('Company Update') ?></th>
                <th scope="col"><?= __('Company Internal Code') ?></th>
                <th scope="col"><?= __('Master Catalog Date') ?></th>
                <th scope="col"><?= __('Session Date') ?></th>
                <th scope="col"><?= __('Enabled') ?></th>
                <th scope="col"><?= __('Cataloged') ?></th>
                <th scope="col"><?= __('Stock Up To Date') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($category->products_stores as $productsStores): ?>
            <tr>
                <td><?= h($productsStores->id) ?></td>
                <td><?= h($productsStores->product_id) ?></td>
                <td><?= h($productsStores->company_id) ?></td>
                <td><?= h($productsStores->store_id) ?></td>
                <td><?= h($productsStores->aisle_id) ?></td>
                <td><?= h($productsStores->section_id) ?></td>
                <td><?= h($productsStores->category_id) ?></td>
                <td><?= h($productsStores->sub_category_id) ?></td>
                <td><?= h($productsStores->strip_price) ?></td>
                <td><?= h($productsStores->company_update) ?></td>
                <td><?= h($productsStores->company_internal_code) ?></td>
                <td><?= h($productsStores->master_catalog_date) ?></td>
                <td><?= h($productsStores->session_date) ?></td>
                <td><?= h($productsStores->enabled) ?></td>
                <td><?= h($productsStores->cataloged) ?></td>
                <td><?= h($productsStores->stock_up_to_date) ?></td>
                <td><?= h($productsStores->created) ?></td>
                <td><?= h($productsStores->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ProductsStores', 'action' => 'view', $productsStores->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ProductsStores', 'action' => 'edit', $productsStores->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ProductsStores', 'action' => 'delete', $productsStores->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productsStores->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Sub Categories') ?></h4>
        <?php if (!empty($category->sub_categories)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Category Id') ?></th>
                <th scope="col"><?= __('Company Id') ?></th>
                <th scope="col"><?= __('Sub Category Name') ?></th>
                <th scope="col"><?= __('Sub Category Code') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($category->sub_categories as $subCategories): ?>
            <tr>
                <td><?= h($subCategories->id) ?></td>
                <td><?= h($subCategories->category_id) ?></td>
                <td><?= h($subCategories->company_id) ?></td>
                <td><?= h($subCategories->sub_category_name) ?></td>
                <td><?= h($subCategories->sub_category_code) ?></td>
                <td><?= h($subCategories->created) ?></td>
                <td><?= h($subCategories->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'SubCategories', 'action' => 'view', $subCategories->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'SubCategories', 'action' => 'edit', $subCategories->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'SubCategories', 'action' => 'delete', $subCategories->id], ['confirm' => __('Are you sure you want to delete # {0}?', $subCategories->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>

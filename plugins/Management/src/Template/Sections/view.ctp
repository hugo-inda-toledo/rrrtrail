<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface $section
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Section'), ['action' => 'edit', $section->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Section'), ['action' => 'delete', $section->id], ['confirm' => __('Are you sure you want to delete # {0}?', $section->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Sections'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Section'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Products Stores'), ['controller' => 'ProductsStores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Products Store'), ['controller' => 'ProductsStores', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users Companies'), ['controller' => 'UsersCompanies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Users Company'), ['controller' => 'UsersCompanies', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users Suppliers'), ['controller' => 'UsersSuppliers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Users Supplier'), ['controller' => 'UsersSuppliers', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="sections view large-9 medium-8 columns content">
    <h3><?= h($section->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Company') ?></th>
            <td><?= $section->has('company') ? $this->Html->link($section->company->id, ['controller' => 'Companies', 'action' => 'view', $section->company->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Section Name') ?></th>
            <td><?= h($section->section_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Section Code') ?></th>
            <td><?= h($section->section_code) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($section->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($section->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($section->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Categories') ?></h4>
        <?php if (!empty($section->categories)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Company Id') ?></th>
                <th scope="col"><?= __('Section Id') ?></th>
                <th scope="col"><?= __('Category Name') ?></th>
                <th scope="col"><?= __('Category Code') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($section->categories as $categories): ?>
            <tr>
                <td><?= h($categories->id) ?></td>
                <td><?= h($categories->company_id) ?></td>
                <td><?= h($categories->section_id) ?></td>
                <td><?= h($categories->category_name) ?></td>
                <td><?= h($categories->category_code) ?></td>
                <td><?= h($categories->created) ?></td>
                <td><?= h($categories->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Categories', 'action' => 'view', $categories->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Categories', 'action' => 'edit', $categories->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Categories', 'action' => 'delete', $categories->id], ['confirm' => __('Are you sure you want to delete # {0}?', $categories->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Products Stores') ?></h4>
        <?php if (!empty($section->products_stores)): ?>
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
            <?php foreach ($section->products_stores as $productsStores): ?>
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
        <h4><?= __('Related Users Companies') ?></h4>
        <?php if (!empty($section->users_companies)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col"><?= __('Company Id') ?></th>
                <th scope="col"><?= __('Store Id') ?></th>
                <th scope="col"><?= __('Section Id') ?></th>
                <th scope="col"><?= __('Enabled') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($section->users_companies as $usersCompanies): ?>
            <tr>
                <td><?= h($usersCompanies->id) ?></td>
                <td><?= h($usersCompanies->user_id) ?></td>
                <td><?= h($usersCompanies->company_id) ?></td>
                <td><?= h($usersCompanies->store_id) ?></td>
                <td><?= h($usersCompanies->section_id) ?></td>
                <td><?= h($usersCompanies->enabled) ?></td>
                <td><?= h($usersCompanies->created) ?></td>
                <td><?= h($usersCompanies->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'UsersCompanies', 'action' => 'view', $usersCompanies->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'UsersCompanies', 'action' => 'edit', $usersCompanies->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'UsersCompanies', 'action' => 'delete', $usersCompanies->id], ['confirm' => __('Are you sure you want to delete # {0}?', $usersCompanies->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Users Suppliers') ?></h4>
        <?php if (!empty($section->users_suppliers)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col"><?= __('Supplier Id') ?></th>
                <th scope="col"><?= __('Company Id') ?></th>
                <th scope="col"><?= __('Store Id') ?></th>
                <th scope="col"><?= __('Section Id') ?></th>
                <th scope="col"><?= __('Enabled') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($section->users_suppliers as $usersSuppliers): ?>
            <tr>
                <td><?= h($usersSuppliers->id) ?></td>
                <td><?= h($usersSuppliers->user_id) ?></td>
                <td><?= h($usersSuppliers->supplier_id) ?></td>
                <td><?= h($usersSuppliers->company_id) ?></td>
                <td><?= h($usersSuppliers->store_id) ?></td>
                <td><?= h($usersSuppliers->section_id) ?></td>
                <td><?= h($usersSuppliers->enabled) ?></td>
                <td><?= h($usersSuppliers->created) ?></td>
                <td><?= h($usersSuppliers->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'UsersSuppliers', 'action' => 'view', $usersSuppliers->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'UsersSuppliers', 'action' => 'edit', $usersSuppliers->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'UsersSuppliers', 'action' => 'delete', $usersSuppliers->id], ['confirm' => __('Are you sure you want to delete # {0}?', $usersSuppliers->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>

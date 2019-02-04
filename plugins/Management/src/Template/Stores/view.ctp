<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface $store
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Store'), ['action' => 'edit', $store->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Store'), ['action' => 'delete', $store->id], ['confirm' => __('Are you sure you want to delete # {0}?', $store->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Stores'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Locations'), ['controller' => 'Locations', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Location'), ['controller' => 'Locations', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Aisles'), ['controller' => 'Aisles', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Aisle'), ['controller' => 'Aisles', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users Companies'), ['controller' => 'UsersCompanies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Users Company'), ['controller' => 'UsersCompanies', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users Suppliers'), ['controller' => 'UsersSuppliers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Users Supplier'), ['controller' => 'UsersSuppliers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="stores view large-9 medium-8 columns content">
    <h3><?= h($store->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Company') ?></th>
            <td><?= $store->has('company') ? $this->Html->link($store->company->id, ['controller' => 'Companies', 'action' => 'view', $store->company->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Location') ?></th>
            <td><?= $store->has('location') ? $this->Html->link($store->location->id, ['controller' => 'Locations', 'action' => 'view', $store->location->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Store Code') ?></th>
            <td><?= h($store->store_code) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Store Name') ?></th>
            <td><?= h($store->store_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Store Address') ?></th>
            <td><?= h($store->store_address) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($store->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Active') ?></th>
            <td><?= $this->Number->format($store->active) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($store->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($store->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Products') ?></h4>
        <?php if (!empty($store->products)): ?>
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
            <?php foreach ($store->products as $products): ?>
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
    <div class="related">
        <h4><?= __('Related Aisles') ?></h4>
        <?php if (!empty($store->aisles)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Company Id') ?></th>
                <th scope="col"><?= __('Store Id') ?></th>
                <th scope="col"><?= __('Aisle Number') ?></th>
                <th scope="col"><?= __('Enabled') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($store->aisles as $aisles): ?>
            <tr>
                <td><?= h($aisles->id) ?></td>
                <td><?= h($aisles->company_id) ?></td>
                <td><?= h($aisles->store_id) ?></td>
                <td><?= h($aisles->aisle_number) ?></td>
                <td><?= h($aisles->enabled) ?></td>
                <td><?= h($aisles->created) ?></td>
                <td><?= h($aisles->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Aisles', 'action' => 'view', $aisles->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Aisles', 'action' => 'edit', $aisles->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Aisles', 'action' => 'delete', $aisles->id], ['confirm' => __('Are you sure you want to delete # {0}?', $aisles->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Users Companies') ?></h4>
        <?php if (!empty($store->users_companies)): ?>
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
            <?php foreach ($store->users_companies as $usersCompanies): ?>
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
        <?php if (!empty($store->users_suppliers)): ?>
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
            <?php foreach ($store->users_suppliers as $usersSuppliers): ?>
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

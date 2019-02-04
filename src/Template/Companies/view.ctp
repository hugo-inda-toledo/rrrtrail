<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Company $company
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Company'), ['action' => 'edit', $company->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Company'), ['action' => 'delete', $company->id], ['confirm' => __('Are you sure you want to delete # {0}?', $company->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Companies'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Aisles'), ['controller' => 'Aisles', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Aisle'), ['controller' => 'Aisles', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Products Stores'), ['controller' => 'ProductsStores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Products Store'), ['controller' => 'ProductsStores', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sections'), ['controller' => 'Sections', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Section'), ['controller' => 'Sections', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sub Categories'), ['controller' => 'SubCategories', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sub Category'), ['controller' => 'SubCategories', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users Suppliers'), ['controller' => 'UsersSuppliers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Users Supplier'), ['controller' => 'UsersSuppliers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Suppliers'), ['controller' => 'Suppliers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Supplier'), ['controller' => 'Suppliers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="companies view large-9 medium-8 columns content">
    <h3><?= h($company->company_name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Company Name') ?></th>
            <td><?= h($company->company_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Company Logo') ?></th>
            <td><?= h($company->company_logo) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Company Keyword') ?></th>
            <td><?= h($company->company_keyword) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($company->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Active') ?></th>
            <td><?= $this->Number->format($company->active) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($company->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($company->modified) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Company Description') ?></h4>
        <?= $this->Text->autoParagraph(h($company->company_description)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Suppliers') ?></h4>
        <?php if (!empty($company->suppliers)): ?>
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
            <?php foreach ($company->suppliers as $suppliers): ?>
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
    <div class="related">
        <h4><?= __('Related Users') ?></h4>
        <?php if (!empty($company->users)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Last Name') ?></th>
                <th scope="col"><?= __('Username') ?></th>
                <th scope="col"><?= __('Email') ?></th>
                <th scope="col"><?= __('Password') ?></th>
                <th scope="col"><?= __('Active') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($company->users as $users): ?>
            <tr>
                <td><?= h($users->id) ?></td>
                <td><?= h($users->name) ?></td>
                <td><?= h($users->last_name) ?></td>
                <td><?= h($users->username) ?></td>
                <td><?= h($users->email) ?></td>
                <td><?= h($users->password) ?></td>
                <td><?= h($users->active) ?></td>
                <td><?= h($users->created) ?></td>
                <td><?= h($users->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Users', 'action' => 'view', $users->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Users', 'action' => 'edit', $users->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Users', 'action' => 'delete', $users->id], ['confirm' => __('Are you sure you want to delete # {0}?', $users->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Aisles') ?></h4>
        <?php if (!empty($company->aisles)): ?>
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
            <?php foreach ($company->aisles as $aisles): ?>
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
        <h4><?= __('Related Categories') ?></h4>
        <?php if (!empty($company->categories)): ?>
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
            <?php foreach ($company->categories as $categories): ?>
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
        <?php if (!empty($company->products_stores)): ?>
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
                <th scope="col"><?= __('Readed Date') ?></th>
                <th scope="col"><?= __('Company Internal Code') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($company->products_stores as $productsStores): ?>
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
                <td><?= h($productsStores->readed_date) ?></td>
                <td><?= h($productsStores->company_internal_code) ?></td>
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
        <h4><?= __('Related Sections') ?></h4>
        <?php if (!empty($company->sections)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Company Id') ?></th>
                <th scope="col"><?= __('Section Name') ?></th>
                <th scope="col"><?= __('Section Code') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($company->sections as $sections): ?>
            <tr>
                <td><?= h($sections->id) ?></td>
                <td><?= h($sections->company_id) ?></td>
                <td><?= h($sections->section_name) ?></td>
                <td><?= h($sections->section_code) ?></td>
                <td><?= h($sections->created) ?></td>
                <td><?= h($sections->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Sections', 'action' => 'view', $sections->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Sections', 'action' => 'edit', $sections->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Sections', 'action' => 'delete', $sections->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sections->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Stores') ?></h4>
        <?php if (!empty($company->stores)): ?>
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
            <?php foreach ($company->stores as $stores): ?>
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
        <h4><?= __('Related Sub Categories') ?></h4>
        <?php if (!empty($company->sub_categories)): ?>
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
            <?php foreach ($company->sub_categories as $subCategories): ?>
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
    <div class="related">
        <h4><?= __('Related Users Suppliers') ?></h4>
        <?php if (!empty($company->users_suppliers)): ?>
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
            <?php foreach ($company->users_suppliers as $usersSuppliers): ?>
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

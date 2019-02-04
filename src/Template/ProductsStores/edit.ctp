<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductsStore $productsStore
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $productsStore->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $productsStore->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Products Stores'), ['action' => 'index']) ?></li>
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
<div class="productsStores form large-9 medium-8 columns content">
    <?= $this->Form->create($productsStore) ?>
    <fieldset>
        <legend><?= __('Edit Products Store') ?></legend>
        <?php
            echo $this->Form->control('product_id', ['options' => $products]);
            echo $this->Form->control('company_id', ['options' => $companies]);
            echo $this->Form->control('store_id', ['options' => $stores]);
            echo $this->Form->control('aisle_id', ['options' => $aisles]);
            echo $this->Form->control('section_id', ['options' => $sections]);
            echo $this->Form->control('category_id', ['options' => $categories]);
            echo $this->Form->control('sub_category_id', ['options' => $subCategories]);
            echo $this->Form->control('strip_price');
            echo $this->Form->control('readed_date');
            echo $this->Form->control('company_internal_code');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

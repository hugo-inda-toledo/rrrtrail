<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Country $country
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Country'), ['action' => 'edit', $country->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Country'), ['action' => 'delete', $country->id], ['confirm' => __('Are you sure you want to delete # {0}?', $country->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Countries'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Country'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Communes'), ['controller' => 'Communes', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Commune'), ['controller' => 'Communes', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Locations'), ['controller' => 'Locations', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Location'), ['controller' => 'Locations', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Regions'), ['controller' => 'Regions', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Region'), ['controller' => 'Regions', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="countries view large-9 medium-8 columns content">
    <h3><?= h($country->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Country Name') ?></th>
            <td><?= h($country->country_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Country Keyword') ?></th>
            <td><?= h($country->country_keyword) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Country Iso Code2') ?></th>
            <td><?= h($country->country_iso_code2) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Country Iso Code3') ?></th>
            <td><?= h($country->country_iso_code3) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($country->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Enabled') ?></th>
            <td><?= $this->Number->format($country->enabled) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($country->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($country->modified) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Country Flag Path') ?></h4>
        <?= $this->Text->autoParagraph(h($country->country_flag_path)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Communes') ?></h4>
        <?php if (!empty($country->communes)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Country Id') ?></th>
                <th scope="col"><?= __('Region Id') ?></th>
                <th scope="col"><?= __('Commune Name') ?></th>
                <th scope="col"><?= __('Commune Keyword') ?></th>
                <th scope="col"><?= __('Enabled') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($country->communes as $communes): ?>
            <tr>
                <td><?= h($communes->id) ?></td>
                <td><?= h($communes->country_id) ?></td>
                <td><?= h($communes->region_id) ?></td>
                <td><?= h($communes->commune_name) ?></td>
                <td><?= h($communes->commune_keyword) ?></td>
                <td><?= h($communes->enabled) ?></td>
                <td><?= h($communes->created) ?></td>
                <td><?= h($communes->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Communes', 'action' => 'view', $communes->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Communes', 'action' => 'edit', $communes->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Communes', 'action' => 'delete', $communes->id], ['confirm' => __('Are you sure you want to delete # {0}?', $communes->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Locations') ?></h4>
        <?php if (!empty($country->locations)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Country Id') ?></th>
                <th scope="col"><?= __('Region Id') ?></th>
                <th scope="col"><?= __('Commune Id') ?></th>
                <th scope="col"><?= __('Street Name') ?></th>
                <th scope="col"><?= __('Street Name 2') ?></th>
                <th scope="col"><?= __('Street Number') ?></th>
                <th scope="col"><?= __('Complement') ?></th>
                <th scope="col"><?= __('Latitude') ?></th>
                <th scope="col"><?= __('Longitude') ?></th>
                <th scope="col"><?= __('Enabled') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($country->locations as $locations): ?>
            <tr>
                <td><?= h($locations->id) ?></td>
                <td><?= h($locations->country_id) ?></td>
                <td><?= h($locations->region_id) ?></td>
                <td><?= h($locations->commune_id) ?></td>
                <td><?= h($locations->street_name) ?></td>
                <td><?= h($locations->street_name_2) ?></td>
                <td><?= h($locations->street_number) ?></td>
                <td><?= h($locations->complement) ?></td>
                <td><?= h($locations->latitude) ?></td>
                <td><?= h($locations->longitude) ?></td>
                <td><?= h($locations->enabled) ?></td>
                <td><?= h($locations->created) ?></td>
                <td><?= h($locations->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Locations', 'action' => 'view', $locations->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Locations', 'action' => 'edit', $locations->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Locations', 'action' => 'delete', $locations->id], ['confirm' => __('Are you sure you want to delete # {0}?', $locations->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Regions') ?></h4>
        <?php if (!empty($country->regions)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Country Id') ?></th>
                <th scope="col"><?= __('Region Name') ?></th>
                <th scope="col"><?= __('Region Keyword') ?></th>
                <th scope="col"><?= __('Enabled') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($country->regions as $regions): ?>
            <tr>
                <td><?= h($regions->id) ?></td>
                <td><?= h($regions->country_id) ?></td>
                <td><?= h($regions->region_name) ?></td>
                <td><?= h($regions->region_keyword) ?></td>
                <td><?= h($regions->enabled) ?></td>
                <td><?= h($regions->created) ?></td>
                <td><?= h($regions->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Regions', 'action' => 'view', $regions->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Regions', 'action' => 'edit', $regions->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Regions', 'action' => 'delete', $regions->id], ['confirm' => __('Are you sure you want to delete # {0}?', $regions->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>

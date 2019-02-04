<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * StockUpdate Entity
 *
 * @property int $id
 * @property int $product_store_id
 * @property float $current_stock
 * @property float $last_stock
 * @property \Cake\I18n\FrozenTime $stock_updated
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\ProductsStore $products_store
 */
class StockUpdate extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}

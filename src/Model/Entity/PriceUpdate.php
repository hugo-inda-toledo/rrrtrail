<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PriceUpdate Entity
 *
 * @property int $id
 * @property int $product_store_id
 * @property float $price
 * @property float $previous_price
 * @property float $ppums_price
 * @property float $previous_ppums_price
 * @property \Cake\I18n\FrozenTime $company_updated
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\ProductsStore $products_store
 */
class PriceUpdate extends Entity
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
        'product_store_id' => true,
        'price' => true,
        'previous_price' => true,
        'ppums_price' => true,
        'previous_ppums_price' => true,
        'company_updated' => true,
        'created' => true,
        'modified' => true,
        'product_store' => true
    ];
}

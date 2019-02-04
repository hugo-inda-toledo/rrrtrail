<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DealUpdate Entity
 *
 * @property int $id
 * @property int $product_store_id
 * @property string $deal_description
 * @property float $value
 * @property \Cake\I18n\FrozenTime $start_date
 * @property \Cake\I18n\FrozenTime $end_date
 * @property string $deal_code
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\ProductsStore $products_store
 */
class DealUpdate extends Entity
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
        'deal_description' => true,
        'value' => true,
        'start_date' => true,
        'end_date' => true,
        'deal_code' => true,
        'created' => true,
        'modified' => true,
        'product_store' => true
    ];
}

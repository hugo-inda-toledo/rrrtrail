<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CatalogUpdate Entity
 *
 * @property int $id
 * @property int $product_store_id
 * @property int $enabled
 * @property int $cataloged
 * @property \Cake\I18n\FrozenTime $catalog_date
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\ProductsStore $products_store
 */
class CatalogUpdate extends Entity
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

<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CatalogUpdateLog Entity
 *
 * @property int $id
 * @property int $catalog_update_id
 * @property int $product_state_id
 * @property int $marked_by_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\CatalogUpdate $catalog_update
 * @property \App\Model\Entity\ProductState $product_state
 * @property \App\Model\Entity\MarkedBy $marked_by
 */
class CatalogUpdateLog extends Entity
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
        'catalog_update_id' => true,
        'product_state_id' => true,
        'marked_by_id' => true,
        'created' => true,
        'modified' => true,
        'catalog_update' => true,
        'product_state' => true,
        'marked_by' => true
    ];
}
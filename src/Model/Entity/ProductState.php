<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductState Entity
 *
 * @property int $id
 * @property string $state_name
 * @property string $state_class
 * @property string $state_keyword
 * @property string $state_type
 * @property int $active
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\ProductsStore[] $products_stores
 */
class ProductState extends Entity
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
        'state_name' => true,
        'state_class' => true,
        'state_keyword' => true,
        'state_type' => true,
        'active' => true,
        'created' => true,
        'modified' => true,
        'products_store' => true
    ];
}

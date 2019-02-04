<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MeasurementUnit Entity
 *
 * @property int $id
 * @property string $unit_name
 * @property string $unit_plural_name
 * @property string $unit_code
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Product[] $products
 */
class MeasurementUnit extends Entity
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
        'unit_name' => true,
        'unit_plural_name' => true,
        'unit_code' => true,
        'created' => true,
        'modified' => true,
        'products' => true
    ];
}

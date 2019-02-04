<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Product Entity
 *
 * @property int $id
 * @property int $measurement_unit_id
 * @property string $product_name
 * @property string $product_description
 * @property int $stripped
 * @property string $ean13
 * @property string $ean13_digit
 * @property string $ean128
 * @property string $ean128_digit
 * @property string $custom_code
 * @property string $bar_type
 * @property int $weighable
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\MeasurementUnit $measurement_unit
 * @property \App\Model\Entity\Store[] $stores
 * @property \App\Model\Entity\Supplier[] $suppliers
 */
class Product extends Entity
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
        'measurement_unit_id' => true,
        'product_name' => true,
        'product_description' => true,
        'stripped' => true,
        'ean13' => true,
        'ean13_digit' => true,
        'ean128' => true,
        'ean128_digit' => true,
        'custom_code' => true,
        'bar_type' => true,
        'weighable' => true,
        'created' => true,
        'modified' => true,
        'measurement_unit' => true,
        'stores' => true,
        'suppliers' => true
    ];
}

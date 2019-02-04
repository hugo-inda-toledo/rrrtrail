<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Detection Entity
 *
 * @property int $id
 * @property int $robot_session_id
 * @property int $product_store_id
 * @property int $aisle_id
 * @property int $detection_id
 * @property float $label_price
 * @property float $location_x
 * @property float $location_y
 * @property float $location_z
 * @property int $stock_alert
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\RobotSession $robot_session
 * @property \App\Model\Entity\ProductsStore $products_store
 * @property \App\Model\Entity\Aisle $aisle
 * @property \App\Model\Entity\Detection[] $detections
 */
class Detection extends Entity
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

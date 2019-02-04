<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RobotSession Entity
 *
 * @property int $id
 * @property int $store_id
 * @property int $session_code
 * @property int $session_date
 * @property int $includes_qa
 * @property int $is_test
 * @property int $processing
 * @property int $finished
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Store $store
 * @property \App\Model\Entity\Detection[] $detections
 */
class RobotSession extends Entity
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

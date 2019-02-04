<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UsersSuppliersRobotReport Entity
 *
 * @property int $id
 * @property int $user_supplier_id
 * @property int $robot_report_id
 * @property int $enabled
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\UsersSupplier $users_supplier
 * @property \App\Model\Entity\RobotReport $robot_report
 */
class UsersSuppliersRobotReport extends Entity
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
        'user_supplier_id' => true,
        'robot_report_id' => true,
        'enabled' => true,
        'created' => true,
        'modified' => true,
        'users_supplier' => true,
        'robot_report' => true
    ];
}

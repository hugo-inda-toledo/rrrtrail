<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UsersRobotReport Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $robot_report_id
 * @property int $newsletter_suscribe
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\RobotReport $robot_report
 */
class UsersRobotReport extends Entity
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
        'user_id' => true,
        'robot_report_id' => true,
        'newsletter_suscribe' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'robot_report' => true
    ];
}

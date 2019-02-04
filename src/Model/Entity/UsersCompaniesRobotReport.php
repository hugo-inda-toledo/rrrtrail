<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UsersCompaniesRobotReport Entity
 *
 * @property int $id
 * @property int $user_company_id
 * @property int $robot_report_id
 * @property int $enabled
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\UsersCompany $users_company
 * @property \App\Model\Entity\RobotReport $robot_report
 */
class UsersCompaniesRobotReport extends Entity
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
        'user_company_id' => true,
        'robot_report_id' => true,
        'enabled' => true,
        'created' => true,
        'modified' => true,
        'users_company' => true,
        'robot_report' => true
    ];
}

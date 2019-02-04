<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RobotReport Entity
 *
 * @property int $id
 * @property string $report_name
 * @property string $report_description
 * @property string $report_keyword
 * @property string $report_icon
 * @property int $active
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class RobotReport extends Entity
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
        'report_name' => true,
        'report_description' => true,
        'report_keyword' => true,
        'report_icon' => true,
        'active' => true,
        'created' => true,
        'modified' => true
    ];
}

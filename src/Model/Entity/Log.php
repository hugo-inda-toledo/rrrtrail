<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Log Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $controller
 * @property string $action
 * @property string $params
 * @property string $plugin
 * @property string $ip
 * @property string $method
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 */
class Log extends Entity
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
        'controller' => true,
        'action' => true,
        'params' => true,
        'plugin' => true,
        'ip' => true,
        'method' => true,
        'created' => true,
        'modified' => true,
        'user' => true
    ];
}

<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Invitation Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $last_name
 * @property string $email
 * @property string $short_code
 * @property string $hash_code
 * @property int $active
 * @property int $submitted
 * @property int $requested_public
 * @property \Cake\I18n\FrozenTime $expired_date
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 */
class Invitation extends Entity
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
        'name' => true,
        'last_name' => true,
        'email' => true,
        'short_code' => true,
        'hash_code' => true,
        'active' => true,
        'submitted' => true,
        'requested_public' => true,
        'expired_date' => true,
        'created' => true,
        'modified' => true,
        'user' => true
    ];
}

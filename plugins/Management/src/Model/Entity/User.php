<?php
namespace Management\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $name
 * @property string $last_name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property int $active
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \Management\Model\Entity\Log[] $logs
 * @property \Management\Model\Entity\Company[] $companies
 * @property \Management\Model\Entity\Group[] $groups
 * @property \Management\Model\Entity\Permission[] $permissions
 * @property \Management\Model\Entity\Supplier[] $suppliers
 */
class User extends Entity
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
        'name' => true,
        'last_name' => true,
        'username' => true,
        'email' => true,
        'password' => true,
        'active' => true,
        'created' => true,
        'modified' => true,
        'logs' => true,
        'companies' => true,
        'groups' => true,
        'permissions' => true,
        'suppliers' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];
}

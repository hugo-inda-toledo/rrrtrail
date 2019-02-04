<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UsersSupplier Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $supplier_id
 * @property int $company_id
 * @property int $store_id
 * @property int $section_id
 * @property int $enabled
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Supplier $supplier
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\Store $store
 * @property \App\Model\Entity\Section $section
 */
class UsersSupplier extends Entity
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

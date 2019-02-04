<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SuppliersCompany Entity
 *
 * @property int $id
 * @property int $supplier_id
 * @property int $company_id
 * @property int $enabled
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Supplier $supplier
 * @property \App\Model\Entity\Company $company
 */
class SuppliersCompany extends Entity
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
        'supplier_id' => true,
        'company_id' => true,
        'enabled' => true,
        'created' => true,
        'modified' => true,
        'supplier' => true,
        'company' => true
    ];
}

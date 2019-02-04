<?php
namespace Management\Model\Entity;

use Cake\ORM\Entity;

/**
 * Store Entity
 *
 * @property int $id
 * @property int $company_id
 * @property int $location_id
 * @property string $store_code
 * @property string $store_name
 * @property string $store_address
 * @property int $active
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \Management\Model\Entity\Company $company
 * @property \Management\Model\Entity\Location $location
 * @property \Management\Model\Entity\Aisle[] $aisles
 * @property \Management\Model\Entity\UsersCompany[] $users_companies
 * @property \Management\Model\Entity\UsersSupplier[] $users_suppliers
 * @property \Management\Model\Entity\Product[] $products
 */
class Store extends Entity
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
        'company_id' => true,
        'location_id' => true,
        'store_code' => true,
        'store_name' => true,
        'store_address' => true,
        'active' => true,
        'created' => true,
        'modified' => true,
        'company' => true,
        'location' => true,
        'aisles' => true,
        'users_companies' => true,
        'users_suppliers' => true,
        'products' => true
    ];
}

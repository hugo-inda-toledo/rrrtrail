<?php
namespace App\Model\Entity;

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
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\Location $location
 * @property \App\Model\Entity\Aisle[] $aisles
 * @property \App\Model\Entity\CatalogUpdate[] $catalog_updates
 * @property \App\Model\Entity\DealUpdate[] $deal_updates
 * @property \App\Model\Entity\PriceUpdate[] $price_updates
 * @property \App\Model\Entity\RobotSession[] $robot_sessions
 * @property \App\Model\Entity\StockUpdate[] $stock_updates
 * @property \App\Model\Entity\UsersCompany[] $users_companies
 * @property \App\Model\Entity\UsersSupplier[] $users_suppliers
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
        'catalog_updates' => true,
        'deal_updates' => true,
        'price_updates' => true,
        'robot_sessions' => true,
        'stock_updates' => true,
        'users_companies' => true,
        'users_suppliers' => true
    ];

    protected function _getFullName()
    {
        return $this->_properties['store_name'].' ('.$this->_properties['store_code'].')';
    }
}

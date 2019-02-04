<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Company Entity
 *
 * @property int $id
 * @property string $company_name
 * @property string $company_description
 * @property string $company_logo
 * @property string $company_keyword
 * @property int $active
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Aisle[] $aisles
 * @property \App\Model\Entity\Category[] $categories
 * @property \App\Model\Entity\ProductsStore[] $products_stores
 * @property \App\Model\Entity\Section[] $sections
 * @property \App\Model\Entity\Store[] $stores
 * @property \App\Model\Entity\SubCategory[] $sub_categories
 * @property \App\Model\Entity\UsersSupplier[] $users_suppliers
 * @property \App\Model\Entity\Supplier[] $suppliers
 * @property \App\Model\Entity\User[] $users
 */
class Company extends Entity
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

<?php
namespace Management\Model\Entity;

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
 * @property \Management\Model\Entity\Aisle[] $aisles
 * @property \Management\Model\Entity\Category[] $categories
 * @property \Management\Model\Entity\ProductsStore[] $products_stores
 * @property \Management\Model\Entity\Section[] $sections
 * @property \Management\Model\Entity\Store[] $stores
 * @property \Management\Model\Entity\SubCategory[] $sub_categories
 * @property \Management\Model\Entity\UsersSupplier[] $users_suppliers
 * @property \Management\Model\Entity\Supplier[] $suppliers
 * @property \Management\Model\Entity\User[] $users
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
        'company_name' => true,
        'company_description' => true,
        'company_logo' => true,
        'company_keyword' => true,
        'active' => true,
        'created' => true,
        'modified' => true,
        'aisles' => true,
        'categories' => true,
        'products_stores' => true,
        'sections' => true,
        'stores' => true,
        'sub_categories' => true,
        'users_suppliers' => true,
        'suppliers' => true,
        'users' => true
    ];
}

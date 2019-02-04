<?php
namespace Management\Model\Entity;

use Cake\ORM\Entity;

/**
 * Section Entity
 *
 * @property int $id
 * @property int $company_id
 * @property string $section_name
 * @property string $section_code
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \Management\Model\Entity\Company $company
 * @property \Management\Model\Entity\Category[] $categories
 * @property \Management\Model\Entity\ProductsStore[] $products_stores
 * @property \Management\Model\Entity\UsersCompany[] $users_companies
 * @property \Management\Model\Entity\UsersSupplier[] $users_suppliers
 */
class Section extends Entity
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
        'section_name' => true,
        'section_code' => true,
        'created' => true,
        'modified' => true,
        'company' => true,
        'categories' => true,
        'products_stores' => true,
        'users_companies' => true,
        'users_suppliers' => true
    ];
}

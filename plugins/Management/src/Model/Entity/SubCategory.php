<?php
namespace Management\Model\Entity;

use Cake\ORM\Entity;

/**
 * SubCategory Entity
 *
 * @property int $id
 * @property int $category_id
 * @property int $company_id
 * @property string $sub_category_name
 * @property string $sub_category_code
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \Management\Model\Entity\Category $category
 * @property \Management\Model\Entity\Company $company
 * @property \Management\Model\Entity\ProductsStore[] $products_stores
 */
class SubCategory extends Entity
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
        'category_id' => true,
        'company_id' => true,
        'sub_category_name' => true,
        'sub_category_code' => true,
        'created' => true,
        'modified' => true,
        'category' => true,
        'company' => true,
        'products_stores' => true
    ];
}

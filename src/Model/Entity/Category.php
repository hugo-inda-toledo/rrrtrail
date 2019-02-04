<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Category Entity
 *
 * @property int $id
 * @property int $company_id
 * @property int $section_id
 * @property string $category_name
 * @property string $category_code
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\Section $section
 * @property \App\Model\Entity\ProductsStore[] $products_stores
 * @property \App\Model\Entity\SubCategory[] $sub_categories
 */
class Category extends Entity
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
        'section_id' => true,
        'category_name' => true,
        'category_code' => true,
        'created' => true,
        'modified' => true,
        'company' => true,
        'section' => true,
        'products_stores' => true,
        'sub_categories' => true
    ];

    protected function _getFullName()
    {
        return $this->_properties['category_name'].' (Cod: '.$this->_properties['category_code'].')';
    }
}

<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductsStore Entity
 *
 * @property int $id
 * @property int $company_id
 * @property int $store_id
 * @property int $section_id
 * @property int $category_id
 * @property int $sub_category_id
 * @property int $third_category_id
 * @property int $aisle_id
 * @property int $product_state_id
 * @property int $product_state_marked_by_id
 * @property string $obs
 * @property string $description
 * @property string $internal_code
 * @property int $ean13
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\Store $store
 * @property \App\Model\Entity\Section $section
 * @property \App\Model\Entity\Category $category
 * @property \App\Model\Entity\SubCategory $sub_category
 * @property \App\Model\Entity\ThirdCategory $third_category
 * @property \App\Model\Entity\Aisle $aisle
 * @property \App\Model\Entity\ProductState $product_state
 * @property \App\Model\Entity\ProductStateMarkedBy $product_state_marked_by
 */
class ProductsStore extends Entity
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

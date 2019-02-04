<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ThirdCategory Entity
 *
 * @property int $id
 * @property int $sub_category_id
 * @property int $company_id
 * @property string $third_category_name
 * @property string $third_category_code
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\SubCategory $sub_category
 * @property \App\Model\Entity\Company $company
 */
class ThirdCategory extends Entity
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
        'sub_category_id' => true,
        'company_id' => true,
        'third_category_name' => true,
        'third_category_code' => true,
        'created' => true,
        'modified' => true,
        'sub_category' => true,
        'company' => true
    ];
}

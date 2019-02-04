<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Commune Entity
 *
 * @property int $id
 * @property int $country_id
 * @property int $region_id
 * @property string $commune_name
 * @property string $commune_keyword
 * @property int $enabled
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Country $country
 * @property \App\Model\Entity\Region $region
 * @property \App\Model\Entity\Location[] $locations
 */
class Commune extends Entity
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
        'country_id' => true,
        'region_id' => true,
        'commune_name' => true,
        'commune_keyword' => true,
        'enabled' => true,
        'created' => true,
        'modified' => true,
        'country' => true,
        'region' => true,
        'locations' => true
    ];
}

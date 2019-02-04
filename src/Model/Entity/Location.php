<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Location Entity
 *
 * @property int $id
 * @property int $country_id
 * @property int $region_id
 * @property int $commune_id
 * @property string $street_name
 * @property string $street_name_2
 * @property string $street_number
 * @property string $complement
 * @property string $latitude
 * @property string $longitude
 * @property int $enabled
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Country $country
 * @property \App\Model\Entity\Region $region
 * @property \App\Model\Entity\Commune $commune
 * @property \App\Model\Entity\Store[] $stores
 */
class Location extends Entity
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
        'commune_id' => true,
        'street_name' => true,
        'street_name_2' => true,
        'street_number' => true,
        'complement' => true,
        'latitude' => true,
        'longitude' => true,
        'enabled' => true,
        'created' => true,
        'modified' => true,
        'country' => true,
        'region' => true,
        'commune' => true,
        'stores' => true
    ];
}

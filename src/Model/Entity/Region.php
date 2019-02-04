<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Region Entity
 *
 * @property int $id
 * @property int $country_id
 * @property string $region_name
 * @property string $region_keyword
 * @property int $enabled
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Country $country
 * @property \App\Model\Entity\Commune[] $communes
 * @property \App\Model\Entity\Location[] $locations
 */
class Region extends Entity
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
        'region_name' => true,
        'region_keyword' => true,
        'enabled' => true,
        'created' => true,
        'modified' => true,
        'country' => true,
        'communes' => true,
        'locations' => true
    ];
}

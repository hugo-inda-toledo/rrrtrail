<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Country Entity
 *
 * @property int $id
 * @property string $country_name
 * @property string $country_keyword
 * @property string $country_iso_code2
 * @property string $country_iso_code3
 * @property string $country_flag_path
 * @property int $enabled
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Commune[] $communes
 * @property \App\Model\Entity\Location[] $locations
 * @property \App\Model\Entity\Region[] $regions
 */
class Country extends Entity
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
        'country_name' => true,
        'country_keyword' => true,
        'country_iso_code2' => true,
        'country_iso_code3' => true,
        'country_flag_path' => true,
        'enabled' => true,
        'created' => true,
        'modified' => true,
        'communes' => true,
        'locations' => true,
        'regions' => true
    ];
}

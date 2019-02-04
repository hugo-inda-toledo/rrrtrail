<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Countries Model
 *
 * @property \App\Model\Table\CommunesTable|\Cake\ORM\Association\HasMany $Communes
 * @property \App\Model\Table\LocationsTable|\Cake\ORM\Association\HasMany $Locations
 * @property \App\Model\Table\RegionsTable|\Cake\ORM\Association\HasMany $Regions
 *
 * @method \App\Model\Entity\Country get($primaryKey, $options = [])
 * @method \App\Model\Entity\Country newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Country[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Country|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Country patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Country[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Country findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CountriesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('countries');
        $this->setDisplayField('country_name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Communes', [
            'foreignKey' => 'country_id'
        ]);
        $this->hasMany('Locations', [
            'foreignKey' => 'country_id'
        ]);
        $this->hasMany('Regions', [
            'foreignKey' => 'country_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('country_name')
            ->maxLength('country_name', 45)
            ->requirePresence('country_name', 'create')
            ->notEmpty('country_name');

        $validator
            ->scalar('country_keyword')
            ->maxLength('country_keyword', 45)
            ->requirePresence('country_keyword', 'create')
            ->notEmpty('country_keyword');

        $validator
            ->scalar('country_iso_code2')
            ->maxLength('country_iso_code2', 45)
            ->requirePresence('country_iso_code2', 'create')
            ->notEmpty('country_iso_code2');

        $validator
            ->scalar('country_iso_code3')
            ->maxLength('country_iso_code3', 45)
            ->requirePresence('country_iso_code3', 'create')
            ->notEmpty('country_iso_code3');

        $validator
            ->scalar('country_flag_path')
            ->allowEmpty('country_flag_path');

        $validator
            ->integer('enabled')
            ->requirePresence('enabled', 'create')
            ->notEmpty('enabled');

        return $validator;
    }
}

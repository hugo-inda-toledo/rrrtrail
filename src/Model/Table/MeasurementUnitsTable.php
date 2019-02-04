<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MeasurementUnits Model
 *
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\HasMany $Products
 *
 * @method \App\Model\Entity\MeasurementUnit get($primaryKey, $options = [])
 * @method \App\Model\Entity\MeasurementUnit newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MeasurementUnit[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MeasurementUnit|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MeasurementUnit patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MeasurementUnit[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MeasurementUnit findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MeasurementUnitsTable extends Table
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

        $this->setTable('measurement_units');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Products', [
            'foreignKey' => 'measurement_unit_id'
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
            ->scalar('unit_name')
            ->maxLength('unit_name', 45)
            ->requirePresence('unit_name', 'create')
            ->notEmpty('unit_name');

        $validator
            ->scalar('unit_plural_name')
            ->maxLength('unit_plural_name', 45)
            ->requirePresence('unit_plural_name', 'create')
            ->notEmpty('unit_plural_name');

        $validator
            ->scalar('unit_code')
            ->maxLength('unit_code', 45)
            ->requirePresence('unit_code', 'create')
            ->notEmpty('unit_code');

        return $validator;
    }
}

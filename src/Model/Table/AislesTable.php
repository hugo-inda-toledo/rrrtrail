<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Aisles Model
 *
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\StoresTable|\Cake\ORM\Association\BelongsTo $Stores
 * @property \App\Model\Table\ProductsStoresTable|\Cake\ORM\Association\BelongsToMany $ProductsStores
 *
 * @method \App\Model\Entity\Aisle get($primaryKey, $options = [])
 * @method \App\Model\Entity\Aisle newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Aisle[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Aisle|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Aisle patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Aisle[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Aisle findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AislesTable extends Table
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

        $this->setTable('aisles');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Stores', [
            'foreignKey' => 'store_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsToMany('ProductsStores', [
            'foreignKey' => 'aisle_id',
            'targetForeignKey' => 'products_store_id',
            'joinTable' => 'products_stores_aisles'
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
            ->scalar('aisle_number')
            ->maxLength('aisle_number', 45)
            ->requirePresence('aisle_number', 'create')
            ->notEmpty('aisle_number');

        $validator
            ->integer('enabled')
            ->requirePresence('enabled', 'create')
            ->notEmpty('enabled');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['company_id'], 'Companies'));
        $rules->add($rules->existsIn(['store_id'], 'Stores'));

        return $rules;
    }
}

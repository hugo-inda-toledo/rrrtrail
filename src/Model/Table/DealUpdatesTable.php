<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


/**
 * DealUpdates Model
 *
 * @property \App\Model\Table\ProductsStoresTable|\Cake\ORM\Association\BelongsTo $ProductsStores
 *
 * @method \App\Model\Entity\DealUpdate get($primaryKey, $options = [])
 * @method \App\Model\Entity\DealUpdate newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DealUpdate[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DealUpdate|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DealUpdate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DealUpdate[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DealUpdate findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DealUpdatesTable extends Table
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

        $this->setTable('deal_updates');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ProductsStores', [
            'foreignKey' => 'product_store_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Stores', [
            'foreignKey' => 'store_id',
            'joinType' => 'INNER'
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
            ->scalar('deal_description')
            ->maxLength('deal_description', 80)
            ->requirePresence('deal_description', 'create')
            ->notEmpty('deal_description');

        $validator
            ->numeric('value')
            ->requirePresence('value', 'create')
            ->notEmpty('value');

        $validator
            ->dateTime('start_date')
            ->requirePresence('start_date', 'create')
            ->notEmpty('start_date');

        $validator
            ->dateTime('end_date')
            ->requirePresence('end_date', 'create')
            ->notEmpty('end_date');

        $validator
            ->scalar('deal_code')
            ->maxLength('deal_code', 45)
            ->allowEmpty('deal_code');

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
        $rules->add($rules->existsIn(['product_store_id'], 'ProductsStores'));
        $rules->add($rules->existsIn(['store_id'], 'Stores'));

        return $rules;
    }
}

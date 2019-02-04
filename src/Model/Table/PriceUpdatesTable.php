<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PriceUpdates Model
 *
 * @property \App\Model\Table\ProductsStoresTable|\Cake\ORM\Association\BelongsTo $ProductsStores
 *
 * @method \App\Model\Entity\PriceUpdate get($primaryKey, $options = [])
 * @method \App\Model\Entity\PriceUpdate newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PriceUpdate[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PriceUpdate|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PriceUpdate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PriceUpdate[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PriceUpdate findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PriceUpdatesTable extends Table
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

        $this->setTable('price_updates');
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
            ->numeric('price')
            ->requirePresence('price', 'create')
            ->notEmpty('price');

        $validator
            ->numeric('ppums_price')
            ->allowEmpty('ppums_price');

        $validator
            ->numeric('previous_ppums_price')
            ->allowEmpty('previous_ppums_price');

        $validator
            ->dateTime('company_updated')
            ->requirePresence('company_updated', 'create')
            ->notEmpty('company_updated');

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

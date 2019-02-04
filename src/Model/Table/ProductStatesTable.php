<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductStates Model
 *
 * @property \App\Model\Table\ProductsStoresTable|\Cake\ORM\Association\HasMany $ProductsStores
 *
 * @method \App\Model\Entity\ProductState get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProductState newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProductState[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductState|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductState patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProductState[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductState findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProductStatesTable extends Table
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

        $this->setTable('product_states');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('ProductsStores', [
            'foreignKey' => 'product_state_id'
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
            ->scalar('state_name')
            ->maxLength('state_name', 70)
            ->requirePresence('state_name', 'create')
            ->notEmpty('state_name');

        $validator
            ->scalar('state_class')
            ->maxLength('state_class', 40)
            ->allowEmpty('state_class');

        $validator
            ->scalar('state_keyword')
            ->maxLength('state_keyword', 90)
            ->requirePresence('state_keyword', 'create')
            ->notEmpty('state_keyword');

        $validator
            ->scalar('state_type')
            ->maxLength('state_type', 20)
            ->allowEmpty('state_type');

        $validator
            ->integer('active')
            ->requirePresence('active', 'create')
            ->notEmpty('active');

        return $validator;
    }
}

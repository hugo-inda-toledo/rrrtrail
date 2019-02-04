<?php
namespace Management\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Stores Model
 *
 * @property \Management\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \Management\Model\Table\LocationsTable|\Cake\ORM\Association\BelongsTo $Locations
 * @property \Management\Model\Table\AislesTable|\Cake\ORM\Association\HasMany $Aisles
 * @property \Management\Model\Table\UsersCompaniesTable|\Cake\ORM\Association\HasMany $UsersCompanies
 * @property \Management\Model\Table\UsersSuppliersTable|\Cake\ORM\Association\HasMany $UsersSuppliers
 * @property \Management\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsToMany $Products
 *
 * @method \Management\Model\Entity\Store get($primaryKey, $options = [])
 * @method \Management\Model\Entity\Store newEntity($data = null, array $options = [])
 * @method \Management\Model\Entity\Store[] newEntities(array $data, array $options = [])
 * @method \Management\Model\Entity\Store|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Management\Model\Entity\Store patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Management\Model\Entity\Store[] patchEntities($entities, array $data, array $options = [])
 * @method \Management\Model\Entity\Store findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class StoresTable extends Table
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

        $this->setTable('stores');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER',
            'className' => 'Management.Companies'
        ]);
        $this->belongsTo('Locations', [
            'foreignKey' => 'location_id',
            'joinType' => 'INNER',
            'className' => 'Management.Locations'
        ]);
        $this->hasMany('Aisles', [
            'foreignKey' => 'store_id',
            'className' => 'Management.Aisles'
        ]);
        $this->hasMany('UsersCompanies', [
            'foreignKey' => 'store_id',
            'className' => 'Management.UsersCompanies'
        ]);
        $this->hasMany('UsersSuppliers', [
            'foreignKey' => 'store_id',
            'className' => 'Management.UsersSuppliers'
        ]);
        $this->belongsToMany('Products', [
            'foreignKey' => 'store_id',
            'targetForeignKey' => 'product_id',
            'joinTable' => 'products_stores',
            'className' => 'Management.Products'
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
            ->scalar('store_code')
            ->maxLength('store_code', 45)
            ->requirePresence('store_code', 'create')
            ->notEmpty('store_code');

        $validator
            ->scalar('store_name')
            ->maxLength('store_name', 45)
            ->requirePresence('store_name', 'create')
            ->notEmpty('store_name');

        $validator
            ->scalar('store_address')
            ->maxLength('store_address', 150)
            ->allowEmpty('store_address');

        $validator
            ->integer('active')
            ->requirePresence('active', 'create')
            ->notEmpty('active');

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
        $rules->add($rules->existsIn(['location_id'], 'Locations'));

        return $rules;
    }
}

<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * Stores Model
 *
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\LocationsTable|\Cake\ORM\Association\BelongsTo $Locations
 * @property \App\Model\Table\AislesTable|\Cake\ORM\Association\HasMany $Aisles
 * @property \App\Model\Table\CatalogUpdatesTable|\Cake\ORM\Association\HasMany $CatalogUpdates
 * @property \App\Model\Table\DealUpdatesTable|\Cake\ORM\Association\HasMany $DealUpdates
 * @property \App\Model\Table\PriceUpdatesTable|\Cake\ORM\Association\HasMany $PriceUpdates
 * @property \App\Model\Table\RobotSessionsTable|\Cake\ORM\Association\HasMany $RobotSessions
 * @property \App\Model\Table\StockUpdatesTable|\Cake\ORM\Association\HasMany $StockUpdates
 * @property \App\Model\Table\UsersCompaniesTable|\Cake\ORM\Association\HasMany $UsersCompanies
 * @property \App\Model\Table\UsersSuppliersTable|\Cake\ORM\Association\HasMany $UsersSuppliers
 *
 * @method \App\Model\Entity\Store get($primaryKey, $options = [])
 * @method \App\Model\Entity\Store newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Store[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Store|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Store patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Store[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Store findOrCreate($search, callable $callback = null, $options = [])
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
        $this->setDisplayField('full_name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Locations', [
            'foreignKey' => 'location_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Aisles', [
            'foreignKey' => 'store_id'
        ]);
        $this->hasMany('CatalogUpdates', [
            'foreignKey' => 'store_id'
        ]);
        $this->hasMany('DealUpdates', [
            'foreignKey' => 'store_id'
        ]);
        $this->hasMany('PriceUpdates', [
            'foreignKey' => 'store_id'
        ]);
        $this->hasMany('RobotSessions', [
            'foreignKey' => 'store_id'
        ]);
        $this->hasMany('StockUpdates', [
            'foreignKey' => 'store_id'
        ]);
        $this->hasMany('UsersCompanies', [
            'foreignKey' => 'store_id'
        ]);
        $this->hasMany('UsersSuppliers', [
            'foreignKey' => 'store_id'
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

    function getAuthIds($user_id = null){
        $stores_data = array();
        if($user_id != null){
            $this->UsersSuppliers = TableRegistry::get('UsersSuppliers');
            $this->UsersCompanies = TableRegistry::get('UsersCompanies');

            

            $users_suppliers_data = $this->UsersSuppliers->find('all')->select(['UsersSuppliers.store_id'])->where(['UsersSuppliers.user_id' => $user_id])->toArray();

            $users_companies_data = $this->UsersCompanies->find('all')->select(['UsersCompanies.store_id'])->where(['UsersCompanies.user_id' => $user_id])->toArray();

            if(count($users_suppliers_data) > 0){
                foreach($users_suppliers_data as $user_supplier_data){
                    $stores_data[] = $user_supplier_data->store_id;
                }
            }
            
            if(count($users_companies_data) > 0){
                foreach($users_companies_data as $user_company_data){
                    $stores_data[] = $user_company_data->store_id;
                }
            }      
        }

        return array_unique($stores_data);
    }
}
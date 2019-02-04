<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * Companies Model
 *
 * @property \App\Model\Table\AislesTable|\Cake\ORM\Association\HasMany $Aisles
 * @property \App\Model\Table\CategoriesTable|\Cake\ORM\Association\HasMany $Categories
 * @property \App\Model\Table\ProductsStoresTable|\Cake\ORM\Association\HasMany $ProductsStores
 * @property \App\Model\Table\SectionsTable|\Cake\ORM\Association\HasMany $Sections
 * @property \App\Model\Table\StoresTable|\Cake\ORM\Association\HasMany $Stores
 * @property \App\Model\Table\SubCategoriesTable|\Cake\ORM\Association\HasMany $SubCategories
 * @property \App\Model\Table\UsersSuppliersTable|\Cake\ORM\Association\HasMany $UsersSuppliers
 * @property \App\Model\Table\SuppliersTable|\Cake\ORM\Association\BelongsToMany $Suppliers
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsToMany $Users
 *
 * @method \App\Model\Entity\Company get($primaryKey, $options = [])
 * @method \App\Model\Entity\Company newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Company[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Company|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Company patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Company[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Company findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CompaniesTable extends Table
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

        $this->setTable('companies');
        $this->setDisplayField('company_name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Aisles', [
            'foreignKey' => 'company_id'
        ]);
        $this->hasMany('Categories', [
            'foreignKey' => 'company_id'
        ]);
        $this->hasMany('ProductsStores', [
            'foreignKey' => 'company_id'
        ]);
        $this->hasMany('Sections', [
            'foreignKey' => 'company_id'
        ]);
        $this->hasMany('Stores', [
            'foreignKey' => 'company_id'
        ]);
        $this->hasMany('SubCategories', [
            'foreignKey' => 'company_id'
        ]);
        $this->hasMany('UsersSuppliers', [
            'foreignKey' => 'company_id'
        ]);
        $this->hasMany('UsersCompanies', [
            'foreignKey' => 'company_id'
        ]);
        $this->belongsToMany('Suppliers', [
            'foreignKey' => 'company_id',
            'targetForeignKey' => 'supplier_id',
            'joinTable' => 'suppliers_companies'
        ]);
        $this->belongsToMany('Users', [
            'foreignKey' => 'company_id',
            'targetForeignKey' => 'user_id',
            'joinTable' => 'users_companies'
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
            ->scalar('company_name')
            ->maxLength('company_name', 45)
            ->requirePresence('company_name', 'create')
            ->notEmpty('company_name');

        $validator
            ->scalar('company_description')
            ->allowEmpty('company_description');

        $validator
            ->scalar('company_logo')
            ->maxLength('company_logo', 100)
            ->allowEmpty('company_logo');

        $validator
            ->scalar('company_keyword')
            ->maxLength('company_keyword', 45)
            ->requirePresence('company_keyword', 'create')
            ->notEmpty('company_keyword');

        $validator
            ->integer('active')
            ->requirePresence('active', 'create')
            ->notEmpty('active');

        return $validator;
    }

    function getAuthIds($user_id = null){
        $companies_data = array();

        if($user_id != null){
            $this->UsersSuppliers = TableRegistry::get('UsersSuppliers');
            $this->UsersCompanies = TableRegistry::get('UsersCompanies');

            

            $users_suppliers_data = $this->UsersSuppliers->find('all')->select(['UsersSuppliers.company_id'])->where(['UsersSuppliers.user_id' => $user_id])->toArray();

            $users_companies_data = $this->UsersCompanies->find('all')->select(['UsersCompanies.company_id'])->where(['UsersCompanies.user_id' => $user_id])->toArray();

            if(count($users_suppliers_data) > 0){
                foreach($users_suppliers_data as $user_supplier_data){
                    $companies_data[] = $user_supplier_data->company_id;
                }
            }
            
            if(count($users_companies_data) > 0){
                foreach($users_companies_data as $user_company_data){
                    $companies_data[] = $user_company_data->company_id;
                }
            }
        }

        return array_unique($companies_data);
    }
}

<?php
namespace Management\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Companies Model
 *
 * @property \Management\Model\Table\AislesTable|\Cake\ORM\Association\HasMany $Aisles
 * @property \Management\Model\Table\CategoriesTable|\Cake\ORM\Association\HasMany $Categories
 * @property \Management\Model\Table\ProductsStoresTable|\Cake\ORM\Association\HasMany $ProductsStores
 * @property \Management\Model\Table\SectionsTable|\Cake\ORM\Association\HasMany $Sections
 * @property \Management\Model\Table\StoresTable|\Cake\ORM\Association\HasMany $Stores
 * @property \Management\Model\Table\SubCategoriesTable|\Cake\ORM\Association\HasMany $SubCategories
 * @property \Management\Model\Table\UsersSuppliersTable|\Cake\ORM\Association\HasMany $UsersSuppliers
 * @property \Management\Model\Table\SuppliersTable|\Cake\ORM\Association\BelongsToMany $Suppliers
 * @property \Management\Model\Table\UsersTable|\Cake\ORM\Association\BelongsToMany $Users
 *
 * @method \Management\Model\Entity\Company get($primaryKey, $options = [])
 * @method \Management\Model\Entity\Company newEntity($data = null, array $options = [])
 * @method \Management\Model\Entity\Company[] newEntities(array $data, array $options = [])
 * @method \Management\Model\Entity\Company|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Management\Model\Entity\Company patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Management\Model\Entity\Company[] patchEntities($entities, array $data, array $options = [])
 * @method \Management\Model\Entity\Company findOrCreate($search, callable $callback = null, $options = [])
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
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Aisles', [
            'foreignKey' => 'company_id',
            'className' => 'Management.Aisles'
        ]);
        $this->hasMany('Categories', [
            'foreignKey' => 'company_id',
            'className' => 'Management.Categories'
        ]);
        $this->hasMany('ProductsStores', [
            'foreignKey' => 'company_id',
            'className' => 'Management.ProductsStores'
        ]);
        $this->hasMany('Sections', [
            'foreignKey' => 'company_id',
            'className' => 'Management.Sections'
        ]);
        $this->hasMany('Stores', [
            'foreignKey' => 'company_id',
            'className' => 'Management.Stores'
        ]);
        $this->hasMany('SubCategories', [
            'foreignKey' => 'company_id',
            'className' => 'Management.SubCategories'
        ]);
        $this->hasMany('UsersSuppliers', [
            'foreignKey' => 'company_id',
            'className' => 'Management.UsersSuppliers'
        ]);
        $this->belongsToMany('Suppliers', [
            'foreignKey' => 'company_id',
            'targetForeignKey' => 'supplier_id',
            'joinTable' => 'suppliers_companies',
            'className' => 'Management.Suppliers'
        ]);
        $this->belongsToMany('Users', [
            'foreignKey' => 'company_id',
            'targetForeignKey' => 'user_id',
            'joinTable' => 'users_companies',
            'className' => 'Management.Users'
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
}

<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * Sections Model
 *
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\CategoriesTable|\Cake\ORM\Association\HasMany $Categories
 * @property \App\Model\Table\ProductsStoresTable|\Cake\ORM\Association\HasMany $ProductsStores
 * @property \App\Model\Table\UsersCompaniesTable|\Cake\ORM\Association\HasMany $UsersCompanies
 * @property \App\Model\Table\UsersSuppliersTable|\Cake\ORM\Association\HasMany $UsersSuppliers
 *
 * @method \App\Model\Entity\Section get($primaryKey, $options = [])
 * @method \App\Model\Entity\Section newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Section[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Section|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Section patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Section[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Section findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SectionsTable extends Table
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

        $this->setTable('sections');
        $this->setDisplayField('section_name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Categories', [
            'foreignKey' => 'section_id'
        ]);
        $this->hasMany('ProductsStores', [
            'foreignKey' => 'section_id'
        ]);
        $this->hasMany('UsersCompanies', [
            'foreignKey' => 'section_id'
        ]);
        $this->hasMany('UsersSuppliers', [
            'foreignKey' => 'section_id'
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
            ->scalar('section_name')
            ->maxLength('section_name', 45)
            ->requirePresence('section_name', 'create')
            ->notEmpty('section_name');

        $validator
            ->scalar('section_code')
            ->maxLength('section_code', 45)
            ->requirePresence('section_code', 'create')
            ->notEmpty('section_code');

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

        return $rules;
    }

    function getAuthIds($user_id = null){
        $sections_data = array();
        if($user_id != null){
            $this->UsersSuppliers = TableRegistry::get('UsersSuppliers');
            $this->UsersCompanies = TableRegistry::get('UsersCompanies');

            

            $users_suppliers_data = $this->UsersSuppliers->find('all')->select(['UsersSuppliers.section_id'])->where(['UsersSuppliers.user_id' => $user_id])->toArray();

            $users_companies_data = $this->UsersCompanies->find('all')->select(['UsersCompanies.section_id'])->where(['UsersCompanies.user_id' => $user_id])->toArray();

            if(count($users_suppliers_data) > 0){
                foreach($users_suppliers_data as $user_supplier_data){
                    $sections_data[] = $user_supplier_data->section_id;
                }
            }
            
            if(count($users_companies_data) > 0){
                foreach($users_companies_data as $user_company_data){
                    $sections_data[] = $user_company_data->section_id;
                }
            }      
        }

        return array_unique($sections_data);
    }
}

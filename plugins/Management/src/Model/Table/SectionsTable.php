<?php
namespace Management\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Sections Model
 *
 * @property \Management\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \Management\Model\Table\CategoriesTable|\Cake\ORM\Association\HasMany $Categories
 * @property \Management\Model\Table\ProductsStoresTable|\Cake\ORM\Association\HasMany $ProductsStores
 * @property \Management\Model\Table\UsersCompaniesTable|\Cake\ORM\Association\HasMany $UsersCompanies
 * @property \Management\Model\Table\UsersSuppliersTable|\Cake\ORM\Association\HasMany $UsersSuppliers
 *
 * @method \Management\Model\Entity\Section get($primaryKey, $options = [])
 * @method \Management\Model\Entity\Section newEntity($data = null, array $options = [])
 * @method \Management\Model\Entity\Section[] newEntities(array $data, array $options = [])
 * @method \Management\Model\Entity\Section|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Management\Model\Entity\Section patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Management\Model\Entity\Section[] patchEntities($entities, array $data, array $options = [])
 * @method \Management\Model\Entity\Section findOrCreate($search, callable $callback = null, $options = [])
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
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER',
            'className' => 'Management.Companies'
        ]);
        $this->hasMany('Categories', [
            'foreignKey' => 'section_id',
            'className' => 'Management.Categories'
        ]);
        $this->hasMany('ProductsStores', [
            'foreignKey' => 'section_id',
            'className' => 'Management.ProductsStores'
        ]);
        $this->hasMany('UsersCompanies', [
            'foreignKey' => 'section_id',
            'className' => 'Management.UsersCompanies'
        ]);
        $this->hasMany('UsersSuppliers', [
            'foreignKey' => 'section_id',
            'className' => 'Management.UsersSuppliers'
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
}

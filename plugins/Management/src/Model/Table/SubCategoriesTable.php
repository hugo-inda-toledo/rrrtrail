<?php
namespace Management\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SubCategories Model
 *
 * @property \Management\Model\Table\CategoriesTable|\Cake\ORM\Association\BelongsTo $Categories
 * @property \Management\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \Management\Model\Table\ProductsStoresTable|\Cake\ORM\Association\HasMany $ProductsStores
 *
 * @method \Management\Model\Entity\SubCategory get($primaryKey, $options = [])
 * @method \Management\Model\Entity\SubCategory newEntity($data = null, array $options = [])
 * @method \Management\Model\Entity\SubCategory[] newEntities(array $data, array $options = [])
 * @method \Management\Model\Entity\SubCategory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Management\Model\Entity\SubCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Management\Model\Entity\SubCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \Management\Model\Entity\SubCategory findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SubCategoriesTable extends Table
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

        $this->setTable('sub_categories');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Categories', [
            'foreignKey' => 'category_id',
            'joinType' => 'INNER',
            'className' => 'Management.Categories'
        ]);
        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER',
            'className' => 'Management.Companies'
        ]);
        $this->hasMany('ProductsStores', [
            'foreignKey' => 'sub_category_id',
            'className' => 'Management.ProductsStores'
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
            ->scalar('sub_category_name')
            ->maxLength('sub_category_name', 45)
            ->requirePresence('sub_category_name', 'create')
            ->notEmpty('sub_category_name');

        $validator
            ->scalar('sub_category_code')
            ->maxLength('sub_category_code', 45)
            ->allowEmpty('sub_category_code');

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
        $rules->add($rules->existsIn(['category_id'], 'Categories'));
        $rules->add($rules->existsIn(['company_id'], 'Companies'));

        return $rules;
    }
}
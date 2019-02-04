<?php
namespace Management\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Categories Model
 *
 * @property \Management\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \Management\Model\Table\SectionsTable|\Cake\ORM\Association\BelongsTo $Sections
 * @property \Management\Model\Table\ProductsStoresTable|\Cake\ORM\Association\HasMany $ProductsStores
 * @property \Management\Model\Table\SubCategoriesTable|\Cake\ORM\Association\HasMany $SubCategories
 *
 * @method \Management\Model\Entity\Category get($primaryKey, $options = [])
 * @method \Management\Model\Entity\Category newEntity($data = null, array $options = [])
 * @method \Management\Model\Entity\Category[] newEntities(array $data, array $options = [])
 * @method \Management\Model\Entity\Category|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Management\Model\Entity\Category patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Management\Model\Entity\Category[] patchEntities($entities, array $data, array $options = [])
 * @method \Management\Model\Entity\Category findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CategoriesTable extends Table
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

        $this->setTable('categories');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER',
            'className' => 'Management.Companies'
        ]);
        $this->belongsTo('Sections', [
            'foreignKey' => 'section_id',
            'joinType' => 'INNER',
            'className' => 'Management.Sections'
        ]);
        $this->hasMany('ProductsStores', [
            'foreignKey' => 'category_id',
            'className' => 'Management.ProductsStores'
        ]);
        $this->hasMany('SubCategories', [
            'foreignKey' => 'category_id',
            'className' => 'Management.SubCategories'
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
            ->scalar('category_name')
            ->maxLength('category_name', 45)
            ->requirePresence('category_name', 'create')
            ->notEmpty('category_name');

        $validator
            ->scalar('category_code')
            ->maxLength('category_code', 45)
            ->requirePresence('category_code', 'create')
            ->notEmpty('category_code');

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
        $rules->add($rules->existsIn(['section_id'], 'Sections'));

        return $rules;
    }
}

<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ThirdCategories Model
 *
 * @property \App\Model\Table\SubCategoriesTable|\Cake\ORM\Association\BelongsTo $SubCategories
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property |\Cake\ORM\Association\HasMany $ProductsStores
 *
 * @method \App\Model\Entity\ThirdCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\ThirdCategory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ThirdCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ThirdCategory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ThirdCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ThirdCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ThirdCategory findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ThirdCategoriesTable extends Table
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

        $this->setTable('third_categories');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('SubCategories', [
            'foreignKey' => 'sub_category_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ProductsStores', [
            'foreignKey' => 'third_category_id'
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
            ->scalar('third_category_name')
            ->maxLength('third_category_name', 60)
            ->requirePresence('third_category_name', 'create')
            ->notEmpty('third_category_name');

        $validator
            ->scalar('third_category_code')
            ->maxLength('third_category_code', 10)
            ->allowEmpty('third_category_code');

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
        $rules->add($rules->existsIn(['sub_category_id'], 'SubCategories'));
        $rules->add($rules->existsIn(['company_id'], 'Companies'));

        return $rules;
    }
}

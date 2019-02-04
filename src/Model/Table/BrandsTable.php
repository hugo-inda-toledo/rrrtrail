<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Brands Model
 *
 * @property \App\Model\Table\SuppliersTable|\Cake\ORM\Association\BelongsToMany $Suppliers
 *
 * @method \App\Model\Entity\Brand get($primaryKey, $options = [])
 * @method \App\Model\Entity\Brand newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Brand[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Brand|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Brand patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Brand[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Brand findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BrandsTable extends Table
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

        $this->setTable('brands');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Suppliers', [
            'foreignKey' => 'brand_id',
            'targetForeignKey' => 'supplier_id',
            'joinTable' => 'brands_suppliers'
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
            ->scalar('brand_name')
            ->maxLength('brand_name', 70)
            ->requirePresence('brand_name', 'create')
            ->notEmpty('brand_name');

        $validator
            ->scalar('brand_code')
            ->maxLength('brand_code', 11)
            ->requirePresence('brand_code', 'create')
            ->notEmpty('brand_code');

        $validator
            ->scalar('brand_keyword')
            ->maxLength('brand_keyword', 70)
            ->allowEmpty('brand_keyword');

        $validator
            ->integer('enabled')
            ->allowEmpty('enabled');

        return $validator;
    }
}

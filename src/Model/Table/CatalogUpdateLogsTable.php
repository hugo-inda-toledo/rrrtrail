<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CatalogUpdateLogs Model
 *
 * @property \App\Model\Table\CatalogUpdatesTable|\Cake\ORM\Association\BelongsTo $CatalogUpdates
 * @property \App\Model\Table\ProductStatesTable|\Cake\ORM\Association\BelongsTo $ProductStates
 * @property \App\Model\Table\MarkedBiesTable|\Cake\ORM\Association\BelongsTo $MarkedBies
 *
 * @method \App\Model\Entity\CatalogUpdateLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\CatalogUpdateLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CatalogUpdateLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CatalogUpdateLog|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CatalogUpdateLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CatalogUpdateLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CatalogUpdateLog findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CatalogUpdateLogsTable extends Table
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

        $this->setTable('catalog_update_logs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('CatalogUpdates', [
            'foreignKey' => 'catalog_update_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ProductStates', [
            'foreignKey' => 'product_state_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('MarkedBies', [
            'foreignKey' => 'marked_by_id',
            'joinType' => 'INNER'
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
        $rules->add($rules->existsIn(['catalog_update_id'], 'CatalogUpdates'));
        $rules->add($rules->existsIn(['product_state_id'], 'ProductStates'));
        $rules->add($rules->existsIn(['marked_by_id'], 'MarkedBies'));

        return $rules;
    }
}

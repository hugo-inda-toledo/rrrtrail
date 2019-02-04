<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Detections Model
 *
 * @property \App\Model\Table\RobotSessionsTable|\Cake\ORM\Association\BelongsTo $RobotSessions
 * @property \App\Model\Table\ProductsStoresTable|\Cake\ORM\Association\BelongsTo $ProductsStores
 * @property \App\Model\Table\AislesTable|\Cake\ORM\Association\BelongsTo $Aisles
 * @property \App\Model\Table\DetectionsTable|\Cake\ORM\Association\BelongsTo $Detections
 * @property \App\Model\Table\DetectionsTable|\Cake\ORM\Association\HasMany $Detections
 *
 * @method \App\Model\Entity\Detection get($primaryKey, $options = [])
 * @method \App\Model\Entity\Detection newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Detection[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Detection|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Detection patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Detection[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Detection findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DetectionsTable extends Table
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

        $this->setTable('detections');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('RobotSessions', [
            'foreignKey' => 'robot_session_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ProductsStores', [
            'foreignKey' => 'product_store_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Aisles', [
            'foreignKey' => 'aisle_id',
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
        $rules->add($rules->existsIn(['robot_session_id'], 'RobotSessions'));
        $rules->add($rules->existsIn(['product_store_id'], 'ProductsStores'));
        $rules->add($rules->existsIn(['aisle_id'], 'Aisles'));

        return $rules;
    }
}

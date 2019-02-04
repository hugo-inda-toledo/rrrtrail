<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RobotSessions Model
 *
 * @property \App\Model\Table\StoresTable|\Cake\ORM\Association\BelongsTo $Stores
 * @property \App\Model\Table\DetectionsTable|\Cake\ORM\Association\HasMany $Detections
 *
 * @method \App\Model\Entity\RobotSession get($primaryKey, $options = [])
 * @method \App\Model\Entity\RobotSession newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\RobotSession[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RobotSession|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RobotSession patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\RobotSession[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\RobotSession findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RobotSessionsTable extends Table
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

        $this->setTable('robot_sessions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Stores', [
            'foreignKey' => 'store_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Detections', [
            'foreignKey' => 'robot_session_id'
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
            ->integer('session_code')
            ->allowEmpty('session_code');

        $validator
            ->integer('session_date')
            ->allowEmpty('session_date');

        $validator
            ->integer('includes_qa')
            ->allowEmpty('includes_qa');

        $validator
            ->integer('is_test')
            ->allowEmpty('is_test');

        $validator
            ->integer('processing')
            ->allowEmpty('processing');

        $validator
            ->integer('finished')
            ->allowEmpty('finished');

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
        $rules->add($rules->existsIn(['store_id'], 'Stores'));

        return $rules;
    }
}

<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UsersSuppliersRobotReports Model
 *
 * @property \App\Model\Table\UsersSuppliersTable|\Cake\ORM\Association\BelongsTo $UsersSuppliers
 * @property \App\Model\Table\RobotReportsTable|\Cake\ORM\Association\BelongsTo $RobotReports
 *
 * @method \App\Model\Entity\UsersSuppliersRobotReport get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsersSuppliersRobotReport newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsersSuppliersRobotReport[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsersSuppliersRobotReport|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsersSuppliersRobotReport patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsersSuppliersRobotReport[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsersSuppliersRobotReport findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersSuppliersRobotReportsTable extends Table
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

        $this->setTable('users_suppliers_robot_reports');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('UsersSuppliers', [
            'foreignKey' => 'user_supplier_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('RobotReports', [
            'foreignKey' => 'robot_report_id',
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

        $validator
            ->integer('enabled')
            ->requirePresence('enabled', 'create')
            ->notEmpty('enabled');

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
        $rules->add($rules->existsIn(['user_supplier_id'], 'UsersSuppliers'));
        $rules->add($rules->existsIn(['robot_report_id'], 'RobotReports'));

        return $rules;
    }
}

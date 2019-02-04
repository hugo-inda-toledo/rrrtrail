<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RobotReports Model
 *
 * @property |\Cake\ORM\Association\BelongsToMany $Users
 *
 * @method \App\Model\Entity\RobotReport get($primaryKey, $options = [])
 * @method \App\Model\Entity\RobotReport newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\RobotReport[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RobotReport|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RobotReport patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\RobotReport[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\RobotReport findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RobotReportsTable extends Table
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

        $this->setTable('robot_reports');
        $this->setDisplayField('report_name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Users', [
            'foreignKey' => 'robot_report_id',
            'targetForeignKey' => 'user_id',
            'joinTable' => 'users_robot_reports'
        ]);
        $this->hasMany('UsersCompaniesRobotReports', [
            'foreignKey' => 'robot_report_id'
        ]);
        $this->hasMany('UsersSuppliersRobotReports', [
            'foreignKey' => 'robot_report_id'
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
            ->scalar('report_name')
            ->maxLength('report_name', 70)
            ->requirePresence('report_name', 'create')
            ->notEmpty('report_name');

        $validator
            ->scalar('report_description')
            ->allowEmpty('report_description');

        $validator
            ->scalar('report_keyword')
            ->maxLength('report_keyword', 70)
            ->allowEmpty('report_keyword');

        $validator
            ->scalar('report_icon')
            ->maxLength('report_icon', 255)
            ->allowEmpty('report_icon');

        $validator
            ->integer('active')
            ->requirePresence('active', 'create')
            ->notEmpty('active');

        return $validator;
    }
}

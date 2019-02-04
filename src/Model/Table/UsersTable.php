<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property |\Cake\ORM\Association\HasMany $Logs
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsToMany $Companies
 * @property \App\Model\Table\GroupsTable|\Cake\ORM\Association\BelongsToMany $Groups
 * @property \App\Model\Table\PermissionsTable|\Cake\ORM\Association\BelongsToMany $Permissions
 * @property |\Cake\ORM\Association\BelongsToMany $RobotReports
 * @property \App\Model\Table\SuppliersTable|\Cake\ORM\Association\BelongsToMany $Suppliers
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('full_name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Logs', [
            'foreignKey' => 'user_id'
        ]);

        $this->hasMany('UsersCompanies', [
            'foreignKey' => 'user_id'
        ]);

        $this->hasMany('UsersSuppliers', [
            'foreignKey' => 'user_id'
        ]);

        $this->hasOne('Invitations', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsToMany('Companies', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'company_id',
            'joinTable' => 'users_companies'
        ]);
        $this->belongsToMany('Groups', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'group_id',
            'joinTable' => 'users_groups'
        ]);
        $this->belongsToMany('Permissions', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'permission_id',
            'joinTable' => 'users_permissions'
        ]);
        $this->belongsToMany('RobotReports', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'robot_report_id',
            'joinTable' => 'users_robot_reports'
        ]);
        $this->belongsToMany('Suppliers', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'supplier_id',
            'joinTable' => 'users_suppliers'
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
            ->scalar('name')
            ->maxLength('name', 45)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 45)
            ->requirePresence('last_name', 'create')
            ->notEmpty('last_name');

        $validator
            ->scalar('username')
            ->maxLength('username', 70)
            ->requirePresence('username', 'create')
            ->notEmpty('username');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmpty('email');

        $validator
            ->scalar('password')
            ->maxLength('password', 100)
            ->requirePresence('password', 'create')
            ->notEmpty('password');

        $validator
            ->integer('active')
            ->requirePresence('active', 'create')
            ->notEmpty('active');

        $validator
            ->integer('is_admin')
            ->requirePresence('is_admin', 'create')
            ->notEmpty('is_admin');

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
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }
}

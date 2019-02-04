<?php
namespace Management\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \Management\Model\Table\LogsTable|\Cake\ORM\Association\HasMany $Logs
 * @property \Management\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsToMany $Companies
 * @property \Management\Model\Table\GroupsTable|\Cake\ORM\Association\BelongsToMany $Groups
 * @property \Management\Model\Table\PermissionsTable|\Cake\ORM\Association\BelongsToMany $Permissions
 * @property \Management\Model\Table\SuppliersTable|\Cake\ORM\Association\BelongsToMany $Suppliers
 *
 * @method \Management\Model\Entity\User get($primaryKey, $options = [])
 * @method \Management\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \Management\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \Management\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Management\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Management\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \Management\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
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
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Logs', [
            'foreignKey' => 'user_id',
            'className' => 'Management.Logs'
        ]);
        $this->belongsToMany('Companies', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'company_id',
            'joinTable' => 'users_companies',
            'className' => 'Management.Companies'
        ]);
        $this->belongsToMany('Groups', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'group_id',
            'joinTable' => 'users_groups',
            'className' => 'Management.Groups'
        ]);
        $this->belongsToMany('Permissions', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'permission_id',
            'joinTable' => 'users_permissions',
            'className' => 'Management.Permissions'
        ]);
        $this->belongsToMany('Suppliers', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'supplier_id',
            'joinTable' => 'users_suppliers',
            'className' => 'Management.Suppliers'
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

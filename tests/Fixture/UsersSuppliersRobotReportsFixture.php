<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersSuppliersRobotReportsFixture
 *
 */
class UsersSuppliersRobotReportsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'user_supplier_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'robot_report_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'enabled' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'users_suppliers_robot_reports_robot_reports_fk_1_idx' => ['type' => 'index', 'columns' => ['robot_report_id'], 'length' => []],
            'users_suppliers_robot_reports_users_suppliers_fk_1_idx' => ['type' => 'index', 'columns' => ['user_supplier_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'users_suppliers_robot_reports_robot_reports_fk_1' => ['type' => 'foreign', 'columns' => ['robot_report_id'], 'references' => ['robot_reports', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'users_suppliers_robot_reports_users_suppliers_fk_1' => ['type' => 'foreign', 'columns' => ['user_supplier_id'], 'references' => ['users_suppliers', 'id'], 'update' => 'noAction', 'delete' => 'cascade', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_spanish_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'user_supplier_id' => 1,
                'robot_report_id' => 1,
                'enabled' => 1,
                'created' => '2018-11-20 10:39:12',
                'modified' => '2018-11-20 10:39:12'
            ],
        ];
        parent::init();
    }
}

<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersSuppliersRobotReportsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersSuppliersRobotReportsTable Test Case
 */
class UsersSuppliersRobotReportsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersSuppliersRobotReportsTable
     */
    public $UsersSuppliersRobotReports;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users_suppliers_robot_reports',
        'app.users_suppliers',
        'app.robot_reports'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('UsersSuppliersRobotReports') ? [] : ['className' => UsersSuppliersRobotReportsTable::class];
        $this->UsersSuppliersRobotReports = TableRegistry::get('UsersSuppliersRobotReports', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UsersSuppliersRobotReports);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

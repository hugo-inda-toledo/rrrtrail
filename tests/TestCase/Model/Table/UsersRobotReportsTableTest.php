<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersRobotReportsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersRobotReportsTable Test Case
 */
class UsersRobotReportsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersRobotReportsTable
     */
    public $UsersRobotReports;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users_robot_reports',
        'app.users',
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
        $config = TableRegistry::exists('UsersRobotReports') ? [] : ['className' => UsersRobotReportsTable::class];
        $this->UsersRobotReports = TableRegistry::get('UsersRobotReports', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UsersRobotReports);

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

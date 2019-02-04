<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersCompaniesRobotReportsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersCompaniesRobotReportsTable Test Case
 */
class UsersCompaniesRobotReportsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersCompaniesRobotReportsTable
     */
    public $UsersCompaniesRobotReports;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users_companies_robot_reports',
        'app.users_companies',
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
        $config = TableRegistry::exists('UsersCompaniesRobotReports') ? [] : ['className' => UsersCompaniesRobotReportsTable::class];
        $this->UsersCompaniesRobotReports = TableRegistry::get('UsersCompaniesRobotReports', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UsersCompaniesRobotReports);

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

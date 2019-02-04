<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RobotReportsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RobotReportsTable Test Case
 */
class RobotReportsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RobotReportsTable
     */
    public $RobotReports;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
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
        $config = TableRegistry::exists('RobotReports') ? [] : ['className' => RobotReportsTable::class];
        $this->RobotReports = TableRegistry::get('RobotReports', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RobotReports);

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
}

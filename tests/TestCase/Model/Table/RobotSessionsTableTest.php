<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RobotSessionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RobotSessionsTable Test Case
 */
class RobotSessionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RobotSessionsTable
     */
    public $RobotSessions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.robot_sessions',
        'app.stores',
        'app.detections'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('RobotSessions') ? [] : ['className' => RobotSessionsTable::class];
        $this->RobotSessions = TableRegistry::get('RobotSessions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RobotSessions);

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

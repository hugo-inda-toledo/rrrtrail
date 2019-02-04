<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DetectionsLogsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DetectionsLogsTable Test Case
 */
class DetectionsLogsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DetectionsLogsTable
     */
    public $DetectionsLogs;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.detections_logs',
        'app.detections',
        'app.product_states',
        'app.marked_bies'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('DetectionsLogs') ? [] : ['className' => DetectionsLogsTable::class];
        $this->DetectionsLogs = TableRegistry::get('DetectionsLogs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DetectionsLogs);

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

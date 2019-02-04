<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DetectionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DetectionsTable Test Case
 */
class DetectionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DetectionsTable
     */
    public $Detections;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.detections',
        'app.robot_sessions',
        'app.products_stores',
        'app.aisles'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Detections') ? [] : ['className' => DetectionsTable::class];
        $this->Detections = TableRegistry::get('Detections', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Detections);

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

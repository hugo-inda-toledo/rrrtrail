<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CatalogUpdateLogsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CatalogUpdateLogsTable Test Case
 */
class CatalogUpdateLogsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CatalogUpdateLogsTable
     */
    public $CatalogUpdateLogs;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.catalog_update_logs',
        'app.catalog_updates',
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
        $config = TableRegistry::exists('CatalogUpdateLogs') ? [] : ['className' => CatalogUpdateLogsTable::class];
        $this->CatalogUpdateLogs = TableRegistry::get('CatalogUpdateLogs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CatalogUpdateLogs);

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

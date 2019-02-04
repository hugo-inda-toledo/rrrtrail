<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\StockUpdatesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\StockUpdatesTable Test Case
 */
class StockUpdatesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\StockUpdatesTable
     */
    public $StockUpdates;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.stock_updates',
        'app.products_stores'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('StockUpdates') ? [] : ['className' => StockUpdatesTable::class];
        $this->StockUpdates = TableRegistry::get('StockUpdates', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->StockUpdates);

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

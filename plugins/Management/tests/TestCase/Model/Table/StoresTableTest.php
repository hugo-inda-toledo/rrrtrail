<?php
namespace Management\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Management\Model\Table\StoresTable;

/**
 * Management\Model\Table\StoresTable Test Case
 */
class StoresTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Management\Model\Table\StoresTable
     */
    public $Stores;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.management.stores',
        'plugin.management.companies',
        'plugin.management.locations',
        'plugin.management.aisles',
        'plugin.management.users_companies',
        'plugin.management.users_suppliers',
        'plugin.management.products'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Stores') ? [] : ['className' => StoresTable::class];
        $this->Stores = TableRegistry::get('Stores', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Stores);

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

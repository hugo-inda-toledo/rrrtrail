<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductsStoresTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductsStoresTable Test Case
 */
class ProductsStoresTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductsStoresTable
     */
    public $ProductsStores;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.products_stores',
        'app.companies',
        'app.stores',
        'app.sections',
        'app.categories',
        'app.sub_categories',
        'app.third_categories',
        'app.aisles',
        'app.product_states',
        'app.product_state_marked_bies'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ProductsStores') ? [] : ['className' => ProductsStoresTable::class];
        $this->ProductsStores = TableRegistry::get('ProductsStores', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductsStores);

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

<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductsStoresAislesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductsStoresAislesTable Test Case
 */
class ProductsStoresAislesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductsStoresAislesTable
     */
    public $ProductsStoresAisles;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.products_stores_aisles',
        'app.product_stores',
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
        $config = TableRegistry::exists('ProductsStoresAisles') ? [] : ['className' => ProductsStoresAislesTable::class];
        $this->ProductsStoresAisles = TableRegistry::get('ProductsStoresAisles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductsStoresAisles);

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

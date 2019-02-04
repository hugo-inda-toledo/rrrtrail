<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AnalyzedProductsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AnalyzedProductsTable Test Case
 */
class AnalyzedProductsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AnalyzedProductsTable
     */
    public $AnalyzedProducts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.analyzed_products',
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
        $config = TableRegistry::exists('AnalyzedProducts') ? [] : ['className' => AnalyzedProductsTable::class];
        $this->AnalyzedProducts = TableRegistry::get('AnalyzedProducts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AnalyzedProducts);

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

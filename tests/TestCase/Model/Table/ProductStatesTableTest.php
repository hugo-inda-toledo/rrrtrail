<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductStatesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductStatesTable Test Case
 */
class ProductStatesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductStatesTable
     */
    public $ProductStates;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.product_states',
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
        $config = TableRegistry::exists('ProductStates') ? [] : ['className' => ProductStatesTable::class];
        $this->ProductStates = TableRegistry::get('ProductStates', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductStates);

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

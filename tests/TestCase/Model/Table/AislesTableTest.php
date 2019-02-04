<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AislesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AislesTable Test Case
 */
class AislesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AislesTable
     */
    public $Aisles;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.aisles',
        'app.companies',
        'app.stores',
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
        $config = TableRegistry::exists('Aisles') ? [] : ['className' => AislesTable::class];
        $this->Aisles = TableRegistry::get('Aisles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Aisles);

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

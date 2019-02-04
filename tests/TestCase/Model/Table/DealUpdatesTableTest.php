<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DealUpdatesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DealUpdatesTable Test Case
 */
class DealUpdatesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DealUpdatesTable
     */
    public $DealUpdates;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.deal_updates',
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
        $config = TableRegistry::exists('DealUpdates') ? [] : ['className' => DealUpdatesTable::class];
        $this->DealUpdates = TableRegistry::get('DealUpdates', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DealUpdates);

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

<?php
namespace Retailers\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestCase;
use Retailers\Controller\StoresController;

/**
 * Retailers\Controller\StoresController Test Case
 */
class StoresControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.retailers.stores',
        'plugin.retailers.companies',
        'plugin.retailers.locations',
        'plugin.retailers.aisles',
        'plugin.retailers.catalog_updates',
        'plugin.retailers.deal_updates',
        'plugin.retailers.price_updates',
        'plugin.retailers.robot_sessions',
        'plugin.retailers.stock_updates',
        'plugin.retailers.users_companies',
        'plugin.retailers.users_suppliers'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

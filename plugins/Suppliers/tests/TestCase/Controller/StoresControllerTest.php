<?php
namespace Suppliers\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestCase;
use Suppliers\Controller\StoresController;

/**
 * Suppliers\Controller\StoresController Test Case
 */
class StoresControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.suppliers.stores',
        'plugin.suppliers.companies',
        'plugin.suppliers.locations',
        'plugin.suppliers.aisles',
        'plugin.suppliers.catalog_updates',
        'plugin.suppliers.deal_updates',
        'plugin.suppliers.price_updates',
        'plugin.suppliers.robot_sessions',
        'plugin.suppliers.stock_updates',
        'plugin.suppliers.users_companies',
        'plugin.suppliers.users_suppliers'
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

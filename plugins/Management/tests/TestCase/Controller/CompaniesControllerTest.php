<?php
namespace Management\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestCase;
use Management\Controller\CompaniesController;

/**
 * Management\Controller\CompaniesController Test Case
 */
class CompaniesControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.management.companies',
        'plugin.management.aisles',
        'plugin.management.categories',
        'plugin.management.products_stores',
        'plugin.management.sections',
        'plugin.management.stores',
        'plugin.management.sub_categories',
        'plugin.management.users_suppliers',
        'plugin.management.suppliers',
        'plugin.management.users'
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

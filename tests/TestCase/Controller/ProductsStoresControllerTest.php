<?php
namespace App\Test\TestCase\Controller;

use App\Controller\ProductsStoresController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\ProductsStoresController Test Case
 */
class ProductsStoresControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.products_stores',
        'app.products',
        'app.suppliers_products',
        'app.suppliers',
        'app.companies',
        'app.aisles',
        'app.categories',
        'app.sections',
        'app.users_companies',
        'app.users',
        'app.groups',
        'app.users_groups',
        'app.permissions',
        'app.users_permissions',
        'app.users_suppliers',
        'app.stores',
        'app.locations',
        'app.countries',
        'app.communes',
        'app.regions',
        'app.sub_categories',
        'app.suppliers_companies'
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

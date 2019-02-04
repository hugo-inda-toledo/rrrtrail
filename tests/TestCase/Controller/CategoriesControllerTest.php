<?php
namespace App\Test\TestCase\Controller;

use App\Controller\CategoriesController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\CategoriesController Test Case
 */
class CategoriesControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.categories',
        'app.companies',
        'app.aisles',
        'app.products_stores',
        'app.products',
        'app.suppliers_products',
        'app.suppliers',
        'app.suppliers_companies',
        'app.users',
        'app.users_companies',
        'app.stores',
        'app.locations',
        'app.countries',
        'app.communes',
        'app.regions',
        'app.users_suppliers',
        'app.sections',
        'app.groups',
        'app.users_groups',
        'app.permissions',
        'app.users_permissions',
        'app.sub_categories'
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

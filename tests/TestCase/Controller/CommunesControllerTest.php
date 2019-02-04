<?php
namespace App\Test\TestCase\Controller;

use App\Controller\CommunesController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\CommunesController Test Case
 */
class CommunesControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.communes',
        'app.countries',
        'app.locations',
        'app.regions',
        'app.stores',
        'app.companies',
        'app.aisles',
        'app.categories',
        'app.products',
        'app.suppliers_products',
        'app.suppliers',
        'app.suppliers_companies',
        'app.users',
        'app.users_companies',
        'app.sections',
        'app.users_suppliers',
        'app.groups',
        'app.users_groups',
        'app.permissions',
        'app.users_permissions',
        'app.products_stores',
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
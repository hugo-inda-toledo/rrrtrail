<?php
namespace App\Test\TestCase\Controller;

use App\Controller\UsersCompaniesController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\UsersCompaniesController Test Case
 */
class UsersCompaniesControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users_companies',
        'app.users',
        'app.companies',
        'app.aisles',
        'app.categories',
        'app.products',
        'app.products_stores',
        'app.stores',
        'app.users_suppliers',
        'app.suppliers',
        'app.sections',
        'app.sub_categories',
        'app.groups',
        'app.users_groups',
        'app.permissions',
        'app.users_permissions'
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

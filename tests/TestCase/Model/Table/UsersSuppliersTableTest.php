<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersSuppliersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersSuppliersTable Test Case
 */
class UsersSuppliersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersSuppliersTable
     */
    public $UsersSuppliers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users_suppliers',
        'app.users',
        'app.companies',
        'app.aisles',
        'app.categories',
        'app.products',
        'app.sections',
        'app.users_companies',
        'app.stores',
        'app.products_stores',
        'app.sub_categories',
        'app.suppliers',
        'app.suppliers_companies',
        'app.suppliers_products',
        'app.groups',
        'app.users_groups',
        'app.permissions',
        'app.users_permissions'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('UsersSuppliers') ? [] : ['className' => UsersSuppliersTable::class];
        $this->UsersSuppliers = TableRegistry::get('UsersSuppliers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UsersSuppliers);

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

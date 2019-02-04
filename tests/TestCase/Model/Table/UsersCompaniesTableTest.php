<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersCompaniesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersCompaniesTable Test Case
 */
class UsersCompaniesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersCompaniesTable
     */
    public $UsersCompanies;

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
        'app.sections',
        'app.stores',
        'app.users_suppliers',
        'app.products_stores',
        'app.sub_categories',
        'app.groups',
        'app.users_groups',
        'app.permissions',
        'app.users_permissions',
        'app.suppliers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('UsersCompanies') ? [] : ['className' => UsersCompaniesTable::class];
        $this->UsersCompanies = TableRegistry::get('UsersCompanies', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UsersCompanies);

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

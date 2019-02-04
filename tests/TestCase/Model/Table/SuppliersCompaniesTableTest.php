<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SuppliersCompaniesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SuppliersCompaniesTable Test Case
 */
class SuppliersCompaniesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SuppliersCompaniesTable
     */
    public $SuppliersCompanies;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.suppliers_companies',
        'app.suppliers',
        'app.companies',
        'app.aisles',
        'app.categories',
        'app.products',
        'app.sections',
        'app.users_companies',
        'app.users',
        'app.groups',
        'app.users_groups',
        'app.permissions',
        'app.users_permissions',
        'app.users_suppliers',
        'app.stores',
        'app.products_stores',
        'app.sub_categories'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('SuppliersCompanies') ? [] : ['className' => SuppliersCompaniesTable::class];
        $this->SuppliersCompanies = TableRegistry::get('SuppliersCompanies', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SuppliersCompanies);

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

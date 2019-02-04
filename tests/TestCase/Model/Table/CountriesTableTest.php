<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CountriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CountriesTable Test Case
 */
class CountriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CountriesTable
     */
    public $Countries;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.countries',
        'app.communes',
        'app.locations',
        'app.regions',
        'app.stores',
        'app.companies',
        'app.aisles',
        'app.categories',
        'app.products',
        'app.sub_categories',
        'app.products_stores',
        'app.sections',
        'app.users_companies',
        'app.users',
        'app.groups',
        'app.users_groups',
        'app.permissions',
        'app.users_permissions',
        'app.suppliers',
        'app.suppliers_companies',
        'app.suppliers_products',
        'app.users_suppliers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Countries') ? [] : ['className' => CountriesTable::class];
        $this->Countries = TableRegistry::get('Countries', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Countries);

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
}

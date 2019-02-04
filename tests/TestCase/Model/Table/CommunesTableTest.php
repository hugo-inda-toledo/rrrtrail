<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CommunesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CommunesTable Test Case
 */
class CommunesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CommunesTable
     */
    public $Communes;

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
        $config = TableRegistry::exists('Communes') ? [] : ['className' => CommunesTable::class];
        $this->Communes = TableRegistry::get('Communes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Communes);

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

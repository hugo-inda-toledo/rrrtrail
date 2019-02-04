<?php
namespace Management\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Management\Model\Table\CompaniesTable;

/**
 * Management\Model\Table\CompaniesTable Test Case
 */
class CompaniesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Management\Model\Table\CompaniesTable
     */
    public $Companies;

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
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Companies') ? [] : ['className' => CompaniesTable::class];
        $this->Companies = TableRegistry::get('Companies', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Companies);

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

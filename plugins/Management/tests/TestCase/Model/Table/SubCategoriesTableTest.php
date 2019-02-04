<?php
namespace Management\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Management\Model\Table\SubCategoriesTable;

/**
 * Management\Model\Table\SubCategoriesTable Test Case
 */
class SubCategoriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Management\Model\Table\SubCategoriesTable
     */
    public $SubCategories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.management.sub_categories',
        'plugin.management.categories',
        'plugin.management.companies',
        'plugin.management.products_stores'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('SubCategories') ? [] : ['className' => SubCategoriesTable::class];
        $this->SubCategories = TableRegistry::get('SubCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SubCategories);

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

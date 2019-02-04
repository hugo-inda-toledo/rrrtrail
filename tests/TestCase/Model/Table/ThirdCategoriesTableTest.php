<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ThirdCategoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ThirdCategoriesTable Test Case
 */
class ThirdCategoriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ThirdCategoriesTable
     */
    public $ThirdCategories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.third_categories',
        'app.sub_categories',
        'app.companies'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ThirdCategories') ? [] : ['className' => ThirdCategoriesTable::class];
        $this->ThirdCategories = TableRegistry::get('ThirdCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ThirdCategories);

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

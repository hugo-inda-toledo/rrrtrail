<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BrandsSuppliersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BrandsSuppliersTable Test Case
 */
class BrandsSuppliersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\BrandsSuppliersTable
     */
    public $BrandsSuppliers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.brands_suppliers',
        'app.brands',
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
        $config = TableRegistry::exists('BrandsSuppliers') ? [] : ['className' => BrandsSuppliersTable::class];
        $this->BrandsSuppliers = TableRegistry::get('BrandsSuppliers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->BrandsSuppliers);

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

<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MeasurementUnitsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MeasurementUnitsTable Test Case
 */
class MeasurementUnitsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\MeasurementUnitsTable
     */
    public $MeasurementUnits;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.measurement_units',
        'app.products'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('MeasurementUnits') ? [] : ['className' => MeasurementUnitsTable::class];
        $this->MeasurementUnits = TableRegistry::get('MeasurementUnits', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MeasurementUnits);

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

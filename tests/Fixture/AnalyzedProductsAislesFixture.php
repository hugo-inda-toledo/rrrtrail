<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AnalyzedProductsAislesFixture
 *
 */
class AnalyzedProductsAislesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'analyzed_product_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'aisle_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'location_x' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'location_y' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'location_z' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'detection_id' => ['type' => 'string', 'length' => 40, 'null' => true, 'default' => null, 'collate' => 'utf8_spanish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'fk_products_stores_aisles_products_stores1_idx' => ['type' => 'index', 'columns' => ['analyzed_product_id'], 'length' => []],
            'fk_products_stores_aisles_aisles1_idx' => ['type' => 'index', 'columns' => ['aisle_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fk_products_stores_aisles_aisles1' => ['type' => 'foreign', 'columns' => ['aisle_id'], 'references' => ['aisles', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_products_stores_aisles_products_stores1' => ['type' => 'foreign', 'columns' => ['analyzed_product_id'], 'references' => ['products_stores', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_spanish_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'analyzed_product_id' => 1,
                'aisle_id' => 1,
                'location_x' => 1,
                'location_y' => 1,
                'location_z' => 1,
                'detection_id' => 'Lorem ipsum dolor sit amet',
                'created' => '2018-06-06 12:31:09',
                'modified' => '2018-06-06 12:31:09'
            ],
        ];
        parent::init();
    }
}

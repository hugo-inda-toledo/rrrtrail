<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DetectionsFixture
 *
 */
class DetectionsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'robot_session_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'product_store_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'aisle_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'detection_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'label_price' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'location_x' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'location_y' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'location_z' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'stock_alert' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'fk_analyzed_products_products_stores1_idx1' => ['type' => 'index', 'columns' => ['product_store_id'], 'length' => []],
            'fk_product_detections_aisles1_idx' => ['type' => 'index', 'columns' => ['aisle_id'], 'length' => []],
            'fk_detections_robot_sessions1_idx' => ['type' => 'index', 'columns' => ['robot_session_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fk_analyzed_products_products_stores1' => ['type' => 'foreign', 'columns' => ['product_store_id'], 'references' => ['products_stores', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_detections_robot_sessions1' => ['type' => 'foreign', 'columns' => ['robot_session_id'], 'references' => ['robot_sessions', 'store_id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_product_detections_aisles1' => ['type' => 'foreign', 'columns' => ['aisle_id'], 'references' => ['aisles', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
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
                'robot_session_id' => 1,
                'product_store_id' => 1,
                'aisle_id' => 1,
                'detection_id' => 1,
                'label_price' => 1,
                'location_x' => 1,
                'location_y' => 1,
                'location_z' => 1,
                'stock_alert' => 1,
                'created' => '2018-07-13 22:35:14',
                'modified' => '2018-07-13 22:35:14'
            ],
        ];
        parent::init();
    }
}

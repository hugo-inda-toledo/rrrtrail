<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DetectionsLogsFixture
 *
 */
class DetectionsLogsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'detection_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'product_state_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'marked_by_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'fk_detections_logs_detections_idx1_idx' => ['type' => 'index', 'columns' => ['detection_id'], 'length' => []],
            'fk_detections_logs_product_states_idx1_idx' => ['type' => 'index', 'columns' => ['product_state_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fk_detections_logs_detections_idx1' => ['type' => 'foreign', 'columns' => ['detection_id'], 'references' => ['detections', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_detections_logs_product_states_idx1' => ['type' => 'foreign', 'columns' => ['product_state_id'], 'references' => ['product_states', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'detection_id' => 1,
                'product_state_id' => 1,
                'marked_by_id' => 1,
                'created' => '2018-07-23 17:17:14',
                'modified' => '2018-07-23 17:17:14'
            ],
        ];
        parent::init();
    }
}

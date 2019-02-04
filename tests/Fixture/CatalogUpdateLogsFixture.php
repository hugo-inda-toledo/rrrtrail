<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CatalogUpdateLogsFixture
 *
 */
class CatalogUpdateLogsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'catalog_update_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'product_state_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'marked_by_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'fk_catalog_update_logs_catalog_updates_idx1_idx' => ['type' => 'index', 'columns' => ['catalog_update_id'], 'length' => []],
            'fk_catalog_update_logs_product_states_idx1_idx' => ['type' => 'index', 'columns' => ['product_state_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fk_catalog_update_logs_catalog_updates_idx1' => ['type' => 'foreign', 'columns' => ['catalog_update_id'], 'references' => ['catalog_updates', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_catalog_update_logs_product_states_idx1' => ['type' => 'foreign', 'columns' => ['product_state_id'], 'references' => ['product_states', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'catalog_update_id' => 1,
                'product_state_id' => 1,
                'marked_by_id' => 1,
                'created' => '2018-07-23 17:16:58',
                'modified' => '2018-07-23 17:16:58'
            ],
        ];
        parent::init();
    }
}

<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InvitationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InvitationsTable Test Case
 */
class InvitationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\InvitationsTable
     */
    public $Invitations;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.invitations',
        'app.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Invitations') ? [] : ['className' => InvitationsTable::class];
        $this->Invitations = TableRegistry::get('Invitations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Invitations);

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

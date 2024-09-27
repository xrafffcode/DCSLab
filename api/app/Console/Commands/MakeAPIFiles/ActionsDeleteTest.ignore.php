<?php

namespace Tests\Unit\Actions\RepToPascalThisActions;

use App\Actions\RepToPascalThis\RepToPascalThisActions;
use Tests\ActionsTestCase;

class RepToPascalThisActionsDeleteTest extends ActionsTestCase
{
    private RepToPascalThisActions $RepToCamelThisActions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->RepToCamelThisActions = new RepToPascalThisActions();
    }

    public function test_RepToSnakeThis_actions_call_delete_expect_bool()
    {
        $this->markTestIncomplete('Need to implement test');
    }
}

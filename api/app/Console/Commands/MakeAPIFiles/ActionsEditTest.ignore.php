<?php

namespace Tests\Unit\Actions\RepToPascalThisActions;

use App\Actions\RepToPascalThis\RepToPascalThisActions;
use Tests\ActionsTestCase;

class RepToPascalThisActionsEditTest extends ActionsTestCase
{
    private RepToPascalThisActions $RepToCamelThisActions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->RepToCamelThisActions = new RepToPascalThisActions();
    }

    public function test_RepToSnakeThis_actions_call_update_expect_db_updated()
    {
        $this->markTestIncomplete('Need to implement test');
    }

    public function test_RepToSnakeThis_actions_call_update_with_empty_array_parameters_expect_exception()
    {
        $this->markTestIncomplete('Need to implement test');
    }
}

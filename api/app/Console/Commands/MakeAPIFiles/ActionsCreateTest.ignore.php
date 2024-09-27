<?php

namespace Tests\Unit\Actions\RepToPascalThisActions;

use App\Actions\RepToPascalThis\RepToPascalThisActions;
use Exception;
use Tests\ActionsTestCase;

class RepToPascalThisActionsCreateTest extends ActionsTestCase
{
    private RepToPascalThisActions $RepToCamelThisActions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->RepToCamelThisActions = new RepToPascalThisActions();
    }

    public function test_RepToSnakeThis_actions_call_create_expect_db_has_record()
    {
        $this->markTestIncomplete('Need to implement test');
    }

    public function test_RepToSnakeThis_actions_call_create_with_empty_array_parameters_expect_exception()
    {
        $this->expectException(Exception::class);
        $this->RepToCamelThisActions->create([]);
    }
}

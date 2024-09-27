<?php

namespace Tests\Unit\Actions\RepToPascalThisActions;

use App\Actions\RepToPascalThis\RepToPascalThisActions;
use Tests\ActionsTestCase;

class RepToPascalThisActionsReadTest extends ActionsTestCase
{
    private RepToPascalThisActions $RepToCamelThisActions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->RepToCamelThisActions = new RepToPascalThisActions();
    }

    public function test_RepToSnakeThis_actions_call_read_any_with_paginate_true_expect_paginator_object()
    {
        $this->markTestIncomplete('Need to implement test');
    }

    public function test_RepToSnakeThis_actions_call_read_any_with_paginate_false_expect_collection_object()
    {
        $this->markTestIncomplete('Need to implement test');
    }

    public function test_RepToSnakeThis_actions_call_read_any_with_nonexistance_companyId_expect_empty_collection()
    {
        $this->markTestIncomplete('Need to implement test');
    }

    public function test_RepToSnakeThis_actions_call_read_any_with_search_parameter_expect_filtered_results()
    {
        $this->markTestIncomplete('Need to implement test');
    }

    public function test_RepToSnakeThis_actions_call_read_any_with_page_parameter_negative_expect_results()
    {
        $this->markTestIncomplete('Need to implement test');
    }

    public function test_RepToSnakeThis_actions_call_read_any_with_perpage_parameter_negative_expect_results()
    {
        $this->markTestIncomplete('Need to implement test');
    }

    public function test_RepToSnakeThis_actions_call_read_expect_object()
    {
        $this->markTestIncomplete('Need to implement test');
    }
}

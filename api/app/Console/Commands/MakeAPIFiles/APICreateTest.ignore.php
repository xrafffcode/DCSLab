<?php

namespace Tests\Feature\API\RepToPascalThisAPI;

use Tests\APITestCase;

class RepToPascalThisAPICreateTest extends APITestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_RepToSnakeThis_api_call_store_without_authorization_expect_unauthorized_message()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function test_RepToSnakeThis_api_call_store_without_access_right_expect_unauthorized_message()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function test_RepToSnakeThis_api_call_store_with_script_tags_in_payload_expect_stripped()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function test_RepToSnakeThis_api_call_store_with_script_tags_in_payload_expect_encoded()
    {
        $this->markTestSkipped('Test under construction');
    }

    public function test_RepToSnakeThis_api_call_store_expect_successful()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function test_RepToSnakeThis_api_call_store_with_nonexistance_branch_id_expect_failed()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function test_RepToSnakeThis_api_call_store_with_existing_code_in_same_company_expect_failed()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function test_RepToSnakeThis_api_call_store_with_existing_code_in_different_company_expect_successful()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function test_RepToSnakeThis_api_call_store_with_empty_string_parameters_expect_validation_error()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

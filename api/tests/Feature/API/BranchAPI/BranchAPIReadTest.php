<?php

namespace Tests\Feature\API\BranchAPI;

use App\Enums\UserRoles;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Str;
use Tests\APITestCase;
use Vinkla\Hashids\Facades\Hashids;

class BranchAPIReadTest extends APITestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_branch_api_call_read_any_without_authorization_expect_unauthorized_message()
    {
        $branchCount = 2;
        $idxMainBranch = random_int(0, $branchCount - 1);

        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoles::DEVELOPER->value)->first())
            ->has(Company::factory()->setStatusActive()
                ->has(Branch::factory()->setStatusActive()->count($branchCount)
                    ->state(new Sequence(
                        fn (Sequence $sequence) => [
                            'is_main' => $sequence->index == $idxMainBranch ? true : false,
                        ]
                    ))
                ))
            ->create();

        $company = $user->companies()->inRandomOrder()->first();

        $api = $this->getJson(route('api.get.db.company.branch.read_any', [
            'company_id' => Hashids::encode($company->id),
            'search' => '',
            'paginate' => true,
            'page' => 1,
            'per_page' => 10,
            'refresh' => true,
        ]));

        $api->assertUnauthorized();
    }

    public function test_branch_api_call_read_any_without_access_right_expect_forbidden_message()
    {
        $branchCount = 2;
        $idxMainBranch = random_int(0, $branchCount - 1);

        $user = User::factory()
            ->has(Company::factory()->setStatusActive()
                ->has(Branch::factory()->setStatusActive()->count($branchCount)
                    ->state(new Sequence(
                        fn (Sequence $sequence) => [
                            'is_main' => $sequence->index == $idxMainBranch ? true : false,
                        ]
                    ))
                ))
            ->create();

        $this->actingAs($user);

        $company = $user->companies()->inRandomOrder()->first();

        $api = $this->getJson(route('api.get.db.company.branch.read_any', [
            'company_id' => Hashids::encode($company->id),
            'search' => '',
            'paginate' => true,
            'page' => 1,
            'per_page' => 10,
            'refresh' => true,
        ]));

        $api->assertForbidden();
    }

    public function test_branch_api_call_read_without_authorization_expect_unauthorized_message()
    {
        $branchCount = 2;
        $idxMainBranch = random_int(0, $branchCount - 1);

        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoles::DEVELOPER->value)->first())
            ->has(Company::factory()->setStatusActive()
                ->has(Branch::factory()->setStatusActive()->count($branchCount)
                    ->state(new Sequence(
                        fn (Sequence $sequence) => [
                            'is_main' => $sequence->index == $idxMainBranch ? true : false,
                        ]
                    ))
                ))
            ->create();

        $company = $user->companies->first();

        $ulid = $company->branches()->inRandomOrder()->first()->ulid;

        $api = $this->getJson(route('api.get.db.company.branch.read', $ulid));

        $api->assertUnauthorized();
    }

    public function test_branch_api_call_read_with_sql_injection_expect_injection_ignored()
    {
        $branchCount = 2;
        $idxMainBranch = random_int(0, $branchCount - 1);

        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoles::DEVELOPER->value)->first())
            ->has(Company::factory()->setStatusActive()
                ->has(Branch::factory()->setStatusActive()->count($branchCount)
                    ->state(new Sequence(
                        fn (Sequence $sequence) => [
                            'is_main' => $sequence->index == $idxMainBranch ? true : false,
                        ]
                    ))
                ))
            ->create();

        $this->actingAs($user);

        $company = $user->companies()->inRandomOrder()->first();

        $injections = [
            "' OR '1'='1",
            '1 UNION SELECT username, password FROM users',
            '1; DROP TABLE users',
            "' OR '1'='1' --",
            "' OR \'1\'=\'1",
            '1 OR SLEEP(5)',
            '1 AND (SELECT COUNT(*) FROM sysobjects) > 1',
            "1 AND (SELECT * FROM users WHERE username = 'admin' AND SLEEP(5))",
            "1; INSERT INTO logs (message) VALUES ('Injected SQL query')",
            "SELECT * FROM users; INSERT INTO logs (message) VALUES ('Injected SQL query')",
            "1 OR EXISTS(SELECT * FROM users WHERE username = 'admin' AND password LIKE '%a%')",
            "1; UPDATE users SET password = 'hacked' WHERE id = 1; --",
            '1 OR 1=1; DROP TABLE users; --',
            '1 AND 1=0 UNION ALL SELECT table_name, column_name FROM information_schema.columns',
            '1 AND 1=0 UNION ALL SELECT table_name, column_name FROM information_schema.columns WHERE table_schema = database()',
            "1; EXEC xp_cmdshell('echo vulnerable'); --",
            "' OR EXISTS(SELECT * FROM information_schema.tables WHERE table_schema='public' AND table_name='users' LIMIT 1) --",
            "1'; EXEC sp_addrolemember 'db_owner', 'admin'; --",
            "1' OR '1'='1'; -- EXEC master..xp_cmdshell 'echo vulnerable' --",
            "1' UNION ALL SELECT NULL, NULL, NULL, NULL, NULL, NULL, CONCAT(username, ':', password) FROM users --",
            '1; SELECT pg_sleep(5); --',
            "1 AND SLEEP(5) AND 'abc'='abc",
            "1 AND SLEEP(5) AND 'xyz'='xyz",
            '1 OR 1=1; SELECT COUNT(*) FROM information_schema.tables;',
            "1' UNION ALL SELECT table_name, column_name FROM information_schema.columns WHERE table_schema = 'public' --",
            '1 AND (SELECT * FROM (SELECT(SLEEP(5)))hOKz)',
            "1' AND 1=(SELECT COUNT(*) FROM tabname); --",
            "1'; WAITFOR DELAY '0:0:5' --",
            "1 OR 1=1; WAITFOR DELAY '0:0:5' --",
            "1; DECLARE @v VARCHAR(8000);SET @v = '';SELECT @v = @v + name + ', ' FROM sysobjects WHERE xtype = 'U';SELECT @v --",
            "1; SELECT COUNT(*), CONCAT(table_name, ':', column_name) FROM information_schema.columns GROUP BY table_name, column_name HAVING COUNT(*) > 1; --",
            '1; SELECT COUNT(*), table_name FROM information_schema.columns GROUP BY table_name HAVING COUNT(*) > 1; --',
            "1' OR '1'='1'; SELECT COUNT(*) FROM information_schema.tables; --",
            '1 AND (SELECT COUNT(*) FROM users) > 10',
            '1 AND (SELECT COUNT(*) FROM users) > 100',
            "1 OR EXISTS(SELECT * FROM users WHERE username = 'admin')",
            "1' OR EXISTS(SELECT * FROM users WHERE username = 'admin') OR '1'='1",
            "1' OR EXISTS(SELECT * FROM users WHERE username = 'admin') OR 'x'='x",
            '1 AND (SELECT COUNT(*) FROM users) > 1; SELECT * FROM users;',
            '1 OR 1=1; SELECT * FROM users;',
            "1' OR 1=1; SELECT * FROM users;",
            "1 OR 1=1; SELECT * FROM users WHERE username = 'admin'; --",
            "1' OR 1=1; SELECT * FROM users WHERE username = 'admin'; --",
            "1 OR 1=1; SELECT * FROM users WHERE username = 'admin' --",
            "1' OR 1=1; SELECT * FROM users WHERE username = 'admin' --",
            "' OR 1=1 --",
            "admin'--",
            "admin' #",
            "' OR 'x'='x",
            "' OR 'a'='a'",
            "' OR 'a'='a'--",
            "' OR 1=1",
            "' OR 1=1--",
            "' OR 1=1#",
            "' OR 1=1 /*",
            "' OR '1'='1'--",
            "' OR '1'='1'/*",
            "' OR '1'='1' #",
            "' OR '1'='1' /*",
            "' OR '1'='1' or ''='",
            "' OR '1'='1' or 'a'='a",
            "' OR '1'='1' or 'a'='a'--",
            "' OR '1'='1' or 'a'='a'/*",
            "' OR '1'='1' or 'a'='a' #",
            "' OR '1'='1' or 'a'='a' /*",
            '1; SELECT * FROM users WHERE 1=1',
            '1; SELECT * FROM users WHERE 1=1--',
            '1; SELECT * FROM users WHERE 1=1/*',
            "1' OR 1=1; SELECT * FROM users WHERE 1=1",
            "1' OR 1=1; SELECT * FROM users WHERE 1=1--",
            "1' OR 1=1; SELECT * FROM users WHERE 1=1/*",
            "1 OR '1'='1'; SELECT * FROM users WHERE 1=1",
            "1 OR '1'='1'; SELECT * FROM users WHERE 1=1--",
            "1 OR '1'='1'; SELECT * FROM users WHERE 1=1/*",
            "1' OR '1'='1'; SELECT * FROM users WHERE 1=1",
            "1' OR '1'='1'; SELECT * FROM users WHERE 1=1--",
            "1' OR '1'='1'; SELECT * FROM users WHERE 1=1/*",
            "1' OR '1'='1' UNION SELECT username, password FROM users",
            "1' OR '1'='1' UNION SELECT username, password FROM users--",
            "1' OR '1'='1' UNION SELECT username, password FROM users/*",
            "1' OR '1'='1' UNION SELECT username, password FROM users #",
            "1' OR '1'='1' UNION SELECT username, password FROM users /*",
            "1' OR '1'='1' UNION SELECT NULL, table_name FROM information_schema.tables",
            "1' OR '1'='1' UNION SELECT NULL, table_name FROM information_schema",
            "' OR '",
            "1' OR '1'='1' UNION SELECT NULL",
            "1' OR '1'='1' UNION SELECT NULL, table_name FROM information_schema.columns",
            "1' OR '1'='1' UNION SELECT NULL, table_name FROM",
            "' OR '1'='1' or",
        ];

        $testIdx = random_int(0, count($injections));

        $api = $this->getJson(route('api.get.db.company.branch.read_any', [
            'company_id' => Hashids::encode($company->id),
            'search' => $injections[$testIdx],
            'paginate' => true,
            'page' => 1,
            'per_page' => 10,
            'refresh' => true,
        ]));

        $api->assertSuccessful();

        $api->assertJsonFragment([
            'total' => 0,
        ]);

        $api->assertJsonStructure([
            'data',
            'links' => [
                'first', 'last', 'prev', 'next',
            ],
            'meta' => [
                'current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total',
            ],
        ]);

        $testIdx = random_int(0, count($injections));

        $api = $this->getJson(route('api.get.db.company.branch.read_any', [
            'company_id' => Hashids::encode($company->id),
            'search' => $injections[$testIdx],
            'paginate' => false,
            'page' => 1,
            'per_page' => 10,
            'refresh' => true,
        ]));

        $api->assertSuccessful();

        $api->assertJsonFragment([
            'data' => [],
        ]);
    }

    public function test_branch_api_call_read_without_access_right_expect_forbidden_message()
    {
        $branchCount = 2;
        $idxMainBranch = random_int(0, $branchCount - 1);

        $user = User::factory()
            ->has(Company::factory()->setStatusActive()
                ->has(Branch::factory()->setStatusActive()->count($branchCount)
                    ->state(new Sequence(
                        fn (Sequence $sequence) => [
                            'is_main' => $sequence->index == $idxMainBranch ? true : false,
                        ]
                    ))
                ))
            ->create();

        $this->actingAs($user);

        $company = $user->companies->first();

        $ulid = $company->branches()->inRandomOrder()->first()->ulid;

        $api = $this->getJson(route('api.get.db.company.branch.read', $ulid));

        $api->assertForbidden();
    }

    public function test_branch_api_call_read_any_with_or_without_pagination_expect_paginator_or_collection()
    {
        $branchCount = 2;
        $idxMainBranch = random_int(0, $branchCount - 1);

        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoles::DEVELOPER->value)->first())
            ->has(Company::factory()->setStatusActive()
                ->has(Branch::factory()->setStatusActive()->count($branchCount)
                    ->state(new Sequence(
                        fn (Sequence $sequence) => [
                            'is_main' => $sequence->index == $idxMainBranch ? true : false,
                        ]
                    ))
                ))
            ->create();

        $this->actingAs($user);

        $company = $user->companies()->inRandomOrder()->first();

        $api = $this->getJson(route('api.get.db.company.branch.read_any', [
            'company_id' => Hashids::encode($company->id),
            'search' => '',
            'paginate' => true,
            'page' => 1,
            'per_page' => 10,
            'refresh' => true,
        ]));

        $api->assertSuccessful();
        $api->assertJsonStructure([
            'data',
            'links' => [
                'first', 'last', 'prev', 'next',
            ],
            'meta' => [
                'current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total',
            ],
        ]);

        $api = $this->getJson(route('api.get.db.company.branch.read_any', [
            'company_id' => Hashids::encode($company->id),
            'search' => '',
            'paginate' => false,
            'page' => 1,
            'per_page' => 10,
            'refresh' => true,
        ]));

        $api->assertSuccessful();
    }

    public function test_branch_api_call_read_any_with_pagination_expect_several_per_page()
    {
        $branchCount = 2;
        $idxMainBranch = random_int(0, $branchCount - 1);

        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoles::DEVELOPER->value)->first())
            ->has(Company::factory()->setStatusActive()
                ->has(Branch::factory()->setStatusActive()->count($branchCount)
                    ->state(new Sequence(
                        fn (Sequence $sequence) => [
                            'is_main' => $sequence->index == $idxMainBranch ? true : false,
                        ]
                    ))
                ))
            ->create();

        $this->actingAs($user);

        $company = $user->companies()->inRandomOrder()->first();

        $api = $this->getJson(route('api.get.db.company.branch.read_any', [
            'company_id' => Hashids::encode($company->id),
            'search' => '',
            'paginate' => true,
            'page' => 1,
            'per_page' => 25,
            'refresh' => true,
        ]));

        $api->assertSuccessful();

        $api->assertJsonFragment([
            'per_page' => 25,
        ]);

        $api->assertJsonStructure([
            'data',
            'links' => [
                'first', 'last', 'prev', 'next',
            ],
            'meta' => [
                'current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total',
            ],
        ]);
    }

    public function test_branch_api_call_read_any_with_search_expect_filtered_results()
    {
        $branchCount = 4;
        $idxMainBranch = random_int(0, $branchCount - 1);
        $idxTest = random_int(0, $branchCount - 1);
        $defaultName = Branch::factory()->make()->name;
        $testName = Branch::factory()->insertStringInName('testing')->make()->name;

        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoles::DEVELOPER->value)->first())
            ->has(Company::factory()->setStatusActive()->setIsDefault()
                ->has(Branch::factory()->setStatusActive()->count($branchCount)
                    ->state(new Sequence(
                        fn (Sequence $sequence) => [
                            'is_main' => $sequence->index == $idxMainBranch ? true : false,
                            'name' => $sequence->index == $idxTest ? $testName : $defaultName,
                        ]
                    ))
                )
            )
            ->create();

        $this->actingAs($user);

        $company = $user->companies()->inRandomOrder()->first();

        $api = $this->getJson(route('api.get.db.company.branch.read_any', [
            'company_id' => Hashids::encode($company->id),
            'search' => 'testing',
            'paginate' => true,
            'page' => 1,
            'per_page' => 10,
            'refresh' => true,
        ]));

        $api->assertSuccessful();
        $api->assertJsonStructure([
            'data',
            'links' => [
                'first', 'last', 'prev', 'next',
            ],
            'meta' => [
                'current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total',
            ],
        ]);

        $api->assertJsonFragment([
            'total' => 1,
        ]);
    }

    public function test_branch_api_call_read_any_without_search_querystring_expect_failed()
    {
        $branchCount = 2;
        $idxMainBranch = random_int(0, $branchCount - 1);

        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoles::DEVELOPER->value)->first())
            ->has(Company::factory()->setStatusActive()
                ->has(Branch::factory()->setStatusActive()->count($branchCount)
                    ->state(new Sequence(
                        fn (Sequence $sequence) => [
                            'is_main' => $sequence->index == $idxMainBranch ? true : false,
                        ]
                    ))
                ))
            ->create();

        $this->actingAs($user);

        $company = $user->companies()->inRandomOrder()->first();

        $api = $this->getJson(route('api.get.db.company.branch.read_any', [
            'company_id' => Hashids::encode($company->id),
        ]));

        $api->assertUnprocessable();
    }

    public function test_branch_api_call_read_any_with_special_char_in_search_expect_results()
    {
        $branchCount = 5;
        $idxMainBranch = random_int(0, $branchCount - 1);

        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoles::DEVELOPER->value)->first())
            ->has(Company::factory()->setStatusActive()
                ->has(Branch::factory()->setStatusActive()->count($branchCount)
                    ->state(new Sequence(
                        fn (Sequence $sequence) => [
                            'is_main' => $sequence->index == $idxMainBranch ? true : false,
                        ]
                    ))
                ))
            ->create();

        $this->actingAs($user);

        $company = $user->companies()->inRandomOrder()->first();

        $api = $this->getJson(route('api.get.db.company.branch.read_any', [
            'company_id' => Hashids::encode($company->id),
            'search' => " !#$%&'()*+,-./:;<=>?@[\]^_`{|}~",
            'paginate' => true,
            'page' => 1,
            'per_page' => 10,
            'refresh' => false,
        ]));

        $api->assertSuccessful();
        $api->assertJsonStructure([
            'data',
            'links' => [
                'first', 'last', 'prev', 'next',
            ],
            'meta' => [
                'current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total',
            ],
        ]);
    }

    public function test_branch_api_call_read_any_with_negative_value_in_parameters_expect_results()
    {
        $branchCount = 2;
        $idxMainBranch = random_int(0, $branchCount - 1);

        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoles::DEVELOPER->value)->first())
            ->has(Company::factory()->setStatusActive()
                ->has(Branch::factory()->setStatusActive()->count($branchCount)
                    ->state(new Sequence(
                        fn (Sequence $sequence) => [
                            'is_main' => $sequence->index == $idxMainBranch ? true : false,
                        ]
                    ))
                ))
            ->create();

        $this->actingAs($user);

        $company = $user->companies()->inRandomOrder()->first();

        $api = $this->getJson(route('api.get.db.company.branch.read_any', [
            'company_id' => Hashids::encode($company->id),
            'search' => '',
            'paginate' => true,
            'page' => -1,
            'per_page' => -10,
            'refresh' => false,
        ]));

        $api->assertStatus(422);
    }

    public function test_branch_api_call_read_expect_successful()
    {
        $branchCount = 3;
        $idxMainBranch = random_int(0, $branchCount - 1);

        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoles::DEVELOPER->value)->first())
            ->has(Company::factory()->setStatusActive()
                ->has(Branch::factory()->setStatusActive()->count($branchCount)
                    ->state(new Sequence(
                        fn (Sequence $sequence) => [
                            'is_main' => $sequence->index == $idxMainBranch ? true : false,
                        ]
                    ))
                ))
            ->create();

        $this->actingAs($user);

        $company = $user->companies->first();

        $ulid = $company->branches()->inRandomOrder()->first()->ulid;

        $api = $this->getJson(route('api.get.db.company.branch.read', $ulid));

        $api->assertSuccessful();
    }

    public function test_branch_api_call_read_without_ulid_expect_exception()
    {
        $this->expectException(Exception::class);

        $branchCount = 3;
        $idxMainBranch = random_int(0, $branchCount - 1);

        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoles::DEVELOPER->value)->first())
            ->has(Company::factory()->setStatusActive()
                ->has(Branch::factory()->setStatusActive()->count($branchCount)
                    ->state(new Sequence(
                        fn (Sequence $sequence) => [
                            'is_main' => $sequence->index == $idxMainBranch ? true : false,
                        ]
                    ))
                ))
            ->create();

        $this->actingAs($user);

        $this->getJson(route('api.get.db.company.branch.read', null));
    }

    public function test_branch_api_call_read_with_nonexistance_ulid_expect_not_found()
    {
        $branchCount = 3;
        $idxMainBranch = random_int(0, $branchCount - 1);

        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoles::DEVELOPER->value)->first())
            ->has(Company::factory()->setStatusActive()
                ->has(Branch::factory()->setStatusActive()->count($branchCount)
                    ->state(new Sequence(
                        fn (Sequence $sequence) => [
                            'is_main' => $sequence->index == $idxMainBranch ? true : false,
                        ]
                    ))
                ))
            ->create();

        $this->actingAs($user);

        $ulid = Str::ulid()->generate();

        $api = $this->getJson(route('api.get.db.company.branch.read', $ulid));

        $api->assertStatus(404);
    }
}

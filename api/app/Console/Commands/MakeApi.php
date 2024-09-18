<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeApi extends Command
{
    protected $signature = 'make:api {args}';

    protected $description = 'Command description';

    public function handle()
    {
        $this->info('Starting API Creation...');

        $name = $this->argument('args');

        $this->createMigration($name);
        $this->createModel($name);
        $this->createFactory($name);
        $this->createSeeder($name);
        $this->createActions($name);
        $this->createResource($name);
        $this->createPolicy($name);
        $this->createRequest($name);
        $this->createController($name);
        $this->createActionsTest($name);
        $this->createAPITest($name);
    }

    public function createMigration(string $name)
    {
        $this->info('Creating Migration...');

        $name = Str::snake($name);
        $name = Str::plural($name);

        Artisan::call('make:migration', ['name' => 'create_'.$name.'_table']);
        $output = Artisan::output();
        preg_match('/\[(.*?)\]/', $output, $matches);
        $path = $matches[1] ?? null;

        $content = File::get(__DIR__.'/MakeAPIFiles/migration.php');
        $content = $this->replaceNameInContent($content, $name);

        file_put_contents($path, $content);

        $this->openInVSCode($path);
    }

    public function createModel(string $name)
    {
        $this->info('Creating Model...');

        Artisan::call('make:model', ['name' => $name]);
        $output = Artisan::output();
        preg_match('/\[(.*?)\]/', $output, $matches);
        $path = $matches[1] ?? null;

        $content = File::get(__DIR__.'/MakeAPIFiles/model.php');
        $content = $this->replaceNameInContent($content, $name);

        file_put_contents($path, $content);

        $this->openInVSCode($path);
    }

    public function createFactory(string $name)
    {
        $this->info('Creating Factory...');

        Artisan::call('make:factory', ['name' => $name]);
        $output = Artisan::output();
        preg_match('/\[(.*?)\]/', $output, $matches);
        $path = $matches[1] ?? null;

        $content = File::get(__DIR__.'/MakeAPIFiles/factory.php');
        $content = $this->replaceNameInContent($content, $name);

        file_put_contents($path, $content);

        $this->openInVSCode($path);
    }

    public function createSeeder(string $name)
    {
        $this->info('Creating Seeder...');

        Artisan::call('make:seeder', ['name' => $name.'TableSeeder']);
        $output = Artisan::output();
        preg_match('/\[(.*?)\]/', $output, $matches);
        $path = $matches[1] ?? null;

        $content = File::get(__DIR__.'/MakeAPIFiles/seeder.php');
        $content = $this->replaceNameInContent($content, $name);

        file_put_contents($path, $content);

        $this->openInVSCode($path);
    }

    public function createActions(string $name)
    {
        $this->info('Creating Actions...');

        if (! is_dir(base_path('app/Actions/'.$name))) {
            mkdir(base_path('app/Actions/'.$name));
        }

        $path = base_path('app/Actions/'.$name.'/'.$name.'Actions.php');

        $content = File::get(__DIR__.'/MakeAPIFiles/actions.php');
        $content = $this->replaceNameInContent($content, $name);

        file_put_contents($path, $content);

        $this->openInVSCode($path);
    }

    public function createResource(string $name)
    {
        $this->info('Creating Resource...');

        Artisan::call('make:resource', ['name' => $name.'Resource']);
        $output = Artisan::output();
        preg_match('/\[(.*?)\]/', $output, $matches);
        $path = $matches[1] ?? null;

        $content = File::get(__DIR__.'/MakeAPIFiles/resource.php');
        $content = $this->replaceNameInContent($content, $name);

        file_put_contents($path, $content);

        $this->openInVSCode($path);
    }

    public function createPolicy(string $name)
    {
        $this->info('Creating Policy...');

        Artisan::call('make:policy', ['name' => $name.'Policy']);
        $output = Artisan::output();
        preg_match('/\[(.*?)\]/', $output, $matches);
        $path = $matches[1] ?? null;

        $content = File::get(__DIR__.'/MakeAPIFiles/policy.php');
        $content = $this->replaceNameInContent($content, $name);

        file_put_contents($path, $content);

        $this->openInVSCode($path);
    }

    public function createRequest(string $name)
    {
        $this->info('Creating Request...');

        Artisan::call('make:request', ['name' => $name.'Request']);
        $output = Artisan::output();
        preg_match('/\[(.*?)\]/', $output, $matches);
        $path = $matches[1] ?? null;

        $content = File::get(__DIR__.'/MakeAPIFiles/request.php');
        $content = $this->replaceNameInContent($content, $name);

        file_put_contents($path, $content);

        $this->openInVSCode($path);

        $this->info('Creating Store Code Rules...');

        Artisan::call('make:rule', ['name' => $name.'StoreValidCode']);
        $output = Artisan::output();
        preg_match('/\[(.*?)\]/', $output, $matches);
        $path = $matches[1] ?? null;

        $content = File::get(__DIR__.'/MakeAPIFiles/ruleStoreValidCode.php');
        $content = $this->replaceNameInContent($content, $name);

        file_put_contents($path, $content);

        $this->openInVSCode($path);

        $this->info('Creating Update Code Rules...');

        Artisan::call('make:rule', ['name' => $name.'UpdateValidCode']);
        $output = Artisan::output();
        preg_match('/\[(.*?)\]/', $output, $matches);
        $path = $matches[1] ?? null;

        $content = File::get(__DIR__.'/MakeAPIFiles/ruleUpdateValidCode.php');
        $content = $this->replaceNameInContent($content, $name);

        file_put_contents($path, $content);

        $this->openInVSCode($path);
    }

    public function createController(string $name)
    {
        $this->info('Creating Controller...');

        Artisan::call('make:controller', ['name' => $name.'Controller']);
        $output = Artisan::output();
        preg_match('/\[(.*?)\]/', $output, $matches);
        $path = $matches[1] ?? null;

        $content = File::get(__DIR__.'/MakeAPIFiles/controller.php');
        $content = $this->replaceNameInContent($content, $name);

        file_put_contents($path, $content);

        $this->openInVSCode($path);
    }

    public function createActionsTest(string $name)
    {
        $this->info('Creating Actions Create Test...');

        $path = base_path('tests/Unit/Actions/'.$name.'Actions/'.$name.'CreateActionsTest.php');
        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        $content = File::get(__DIR__.'/MakeAPIFiles/ActionsCreateTest.php');
        $content = $this->replaceNameInContent($content, $name);

        file_put_contents($path, $content);

        $this->openInVSCode($path);

        $this->info('Creating Actions Read Test...');

        $path = base_path('tests/Unit/Actions/'.$name.'Actions/'.$name.'ReadActionsTest.php');
        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        $content = File::get(__DIR__.'/MakeAPIFiles/ActionsReadTest.php');
        $content = $this->replaceNameInContent($content, $name);

        file_put_contents($path, $content);

        $this->openInVSCode($path);

        $this->info('Creating Actions Update Test...');

        $path = base_path('tests/Unit/Actions/'.$name.'Actions/'.$name.'UpdateActionsTest.php');
        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        $content = File::get(__DIR__.'/MakeAPIFiles/ActionsUpdateTest.php');
        $content = $this->replaceNameInContent($content, $name);

        file_put_contents($path, $content);

        $this->openInVSCode($path);

        $this->info('Creating Actions Delete Test...');

        $path = base_path('tests/Unit/Actions/'.$name.'Actions/'.$name.'DeleteActionsTest.php');
        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        $content = File::get(__DIR__.'/MakeAPIFiles/ActionsDeleteTest.php');
        $content = $this->replaceNameInContent($content, $name);

        file_put_contents($path, $content);

        $this->openInVSCode($path);
    }

    public function createAPITest(string $name)
    {
        $this->info('Creating API Create Test...');

        $path = base_path('tests/Unit/API/'.$name.'API/'.$name.'CreateAPITest.php');
        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        $content = File::get(__DIR__.'/MakeAPIFiles/APICreateTest.php');
        $content = $this->replaceNameInContent($content, $name);

        file_put_contents($path, $content);

        $this->openInVSCode($path);

        $this->info('Creating API Read Test...');

        $path = base_path('tests/Unit/API/'.$name.'API/'.$name.'ReadAPITest.php');
        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        $content = File::get(__DIR__.'/MakeAPIFiles/APIReadTest.php');
        $content = $this->replaceNameInContent($content, $name);

        file_put_contents($path, $content);

        $this->openInVSCode($path);

        $this->info('Creating API Update Test...');

        $path = base_path('tests/Unit/API/'.$name.'API/'.$name.'UpdateAPITest.php');
        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        $content = File::get(__DIR__.'/MakeAPIFiles/APIUpdateTest.php');
        $content = $this->replaceNameInContent($content, $name);

        file_put_contents($path, $content);

        $this->openInVSCode($path);

        $this->info('Creating API Delete Test...');

        $path = base_path('tests/Unit/API/'.$name.'API/'.$name.'DeleteAPITest.php');
        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        $content = File::get(__DIR__.'/MakeAPIFiles/APIDeleteTest.php');
        $content = $this->replaceNameInContent($content, $name);

        file_put_contents($path, $content);

        $this->openInVSCode($path);
    }

    public function replaceNameInContent(string $content, string $name): string
    {
        $content = str_replace('RepToTitleThis', ucfirst(preg_replace('/(?<=\\w)(?=[A-Z])/', ' ', $name)), $content);
        $content = str_replace('RepToProperThis', ucfirst(strtolower(preg_replace('/(?<=\\w)(?=[A-Z])/', ' ', $name))), $content);
        $content = str_replace('RepToPascalThis', Str::studly($name), $content);
        $content = str_replace('RepToPascalPluralsThis', Str::studly(Str::plural($name)), $content);
        $content = str_replace('RepToCamelThis', Str::camel($name), $content);
        $content = str_replace('RepToCamelPluralsThis', Str::camel(Str::plural($name)), $content);
        $content = str_replace('RepToSnakeThis', Str::snake($name), $content);
        $content = str_replace('RepToSnakePluralsThis', Str::snake(Str::plural($name)), $content);
        $content = str_replace('RepToKebabThis', Str::kebab($name), $content);

        return $content;
    }

    protected function openInVSCode($filePath)
    {
        $command = 'code '.escapeshellarg($filePath);
        exec($command);
    }
}

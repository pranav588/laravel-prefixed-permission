<?php

namespace Thinklar\PrefixedPermission\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallPrefixedPermission extends Command
{
    protected $signature = 'prefixed-permission:install';
    protected $description = 'Install Spatie Permission with custom table prefix';

    public function handle()
    {
        $prefix = $this->ask('Enter table prefix (leave blank for no prefix)', '');

        $this->info("Using prefix: {$prefix}");

        // Publish config dynamically
        $configPath = config_path('permission.php');

        $configContent = "<?php\n\nreturn [\n    'table_names' => [\n".
            "        'roles' => '{$prefix}roles',\n".
            "        'permissions' => '{$prefix}permissions',\n".
            "        'model_has_permissions' => '{$prefix}model_has_permissions',\n".
            "        'model_has_roles' => '{$prefix}model_has_roles',\n".
            "        'role_has_permissions' => '{$prefix}role_has_permissions',\n".
            "    ],\n];";

        File::put($configPath, $configContent);

        $this->info('Permission config published.');

        // Publish migration stub
        $stub = File::get(__DIR__.'/../../stubs/create_permission_tables.stub');
        $stub = str_replace('{{prefix}}', $prefix, $stub);

        $migrationName = date('Y_m_d_His').'_create_permission_tables.php';
        File::put(database_path('migrations/'.$migrationName), $stub);

        $this->info('Migration created successfully.');
        $this->line('Now run: php artisan migrate');
    }
}

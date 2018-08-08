<?php

namespace App\Commands\Migrate;

use Illuminate\Database\Console\Migrations\FreshCommand as OriginalCommand;

class FreshCommand extends OriginalCommand
{

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {

        $database = config('database.connections.default.database');
        $env = "db_name=\"$database\"";

        if (!$this->option('force') && !$this->confirm("This command will drop your database ($database) and recreate it. Do you want to continue?")) {
            exit(1);
        }

        if (!$this->confirmToProceed()) {
            return;
        }

        // drop/create the database
        $this->callSilent('psql', [
            '--nodb' => true,
            '--quiet' => true,
            '--file' => base_path("database/schema/recreate_database.sql"),
            '--set' => $env
        ]);
        $this->info("Dropped/Created the database $database.");

        // execute the init script
        $this->comment('Executing the init script...');
        $this->executeFile(base_path('database/schema/init.sql'), $env);
        $this->info('Init script executed.');

        // creates the initial tables
        $this->comment("Creating the tables...");
        $files = $this->readDirectory('database/schema/tables');
        $this->executeFiles($files);
        $this->info('All tables created.');

        // creates the initial relationships
        $this->comment("Creating the relationships...");
        $files = $this->readDirectory('database/schema/relationships');
        $this->executeFiles($files);
        $this->info('All relationships created.');

        // run the migrations
        $db = $this->input->getOption('database');
        $this->call('migrate', [
            '--database' => $db,
            '--path' => $this->input->getOption('path'),
            '--realpath' => $this->input->getOption('realpath'),
            '--force' => true,
        ]);

        // run the seeders
        if ($this->needsSeeding()) {
            $this->comment("Running seeders...");
            $this->runSeeder($db);
            $this->info('Seeders ok.');
        }

        // run everything else
        $this->comment("Creating the non data dependent objects...");
        $files = $this->readDirectories([
            'database/schema/types',
            'database/schema/functions',
            'database/schema/endpoints',
            'database/schema/permissions',
        ]);
        $this->executeFiles($files, $env);
        $this->info('Ready!');
    }

    protected function readDirectories(array $paths): array
    {
        $files = [];
        foreach ($paths as $path) {
            $files = array_merge($files, $this->readDirectory($path));
        }
        return $files;
    }

    protected function readDirectory(string $path): array
    {
        $dir = dir(base_path($path));
        $files = [];
        while (false !== ($entry = $dir->read())) {
            $fullPath = "$path/$entry";
            if (preg_match('/\.sql$/', $entry)) {
                $files[] = $fullPath;
            }

        }
        $dir->rewind();
        while (false !== ($entry = $dir->read())) {
            $fullPath = "$path/$entry";
            if (is_dir($fullPath) && $entry != '.' && $entry != '..') {
                $files = array_merge($files, $this->readDirectory($fullPath));
            }
        }
        $dir->close();
        return $files;
    }

    protected function executeFiles(array $files, string $env = null): void
    {
        $bar = $this->output->createProgressBar(count($files));
        foreach ($files as $file) {
            $this->executeFile($file, $env);
            $bar->advance();
        }
        $bar->finish();
        echo PHP_EOL;
    }

    protected function executeFile(string $file, string $env = null): void
    {
        $options = [
            '--quiet' => true,
            '--file' => $file
        ];
        if ($env !== null) {
            $options['--set'] = $env;
        }
        $this->callSilent('psql', $options);
    }
}

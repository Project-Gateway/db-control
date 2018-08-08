<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;

class PsqlCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'psql 
        {--command= : Single SQL command to run} 
        {--file= : SQL file to run} 
        {--set= : Use to set an env variable for the script (var="value") }
        {--nodb : Run without defining the current database.}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Open PostgreSQL command line';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {

        $command = "env PGOPTIONS='-c client_min_messages=WARNING' ";
        $command .= 'psql ' . $this->connectionString();

        if ($sql = $this->option('command')) {
            $command .= " -c \"$sql\"";
        }

        if ($file = $this->option('file')) {
            $command .= " -f \"$file\"";
        }

        if (!($sql xor $file)) {
            $this->error('Please provide --command OR --file.');
            die;
        }

        if ($env = $this->option('set')) {
            $command .= " --set=$env";
        }

        $result = shell_exec($command);
        if (!$this->option('quiet')) {
            echo $result;
        }
    }

    protected function connectionString()
    {
        $c = config('database.connections.default');
        $string = "postgres://${c['username']}:${c['password']}@${c['host']}:${c['port']}/";
        return $string . ($this->option('nodb') ? '' : $c['database']);
    }


}

<?php
namespace Webcosmonauts\Alder\Commands;

use Webcosmonauts\Alder\Facades\AlderScheme;
use Illuminate\Console\Command;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Models\Modifiers\PageModifier;

class UpgradeDatabaseStateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alder:db:upgrade
                            {--m|migrate : Run migrations before executing command}
                            {--f|fresh : Run fresh migrations before executing command}
                            {--s|seed : Run seeders after structuring database}';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upgrade database structure with modifier fields';
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Run migrations if needed
        $this->migrations();
        
        $modifiers = Alder::getLcmModels();
        if (is_null($modifiers))
            throw new \RuntimeException('App is not running in console');
        
        $this->line('Synchronizing modifier properties with database...');
        
        // Set up schema tables
        AlderScheme::setupSystem();
    
        // Synchronize properties of each modifier
        foreach ($modifiers as $modifier)
            AlderScheme::upgrade($modifier);
        
        $this->info('Database structure is up to date');
        
        // Run seeders if needed
        $this->seeders();
        
        return;
    }
    
    private function migrations() {
        $exitCode = 0;
        if ($this->option('migrate') || $this->option('fresh')) {
            $command = 'migrate';
            if ($this->option('fresh'))
                $command .= ':fresh';
            $exitCode = $this->call($command);
            echo PHP_EOL;
        }
        if ($exitCode !== 0)
            throw new \RuntimeException("Error while running migrations, exit code $exitCode");
    }
    
    private function seeders() {
        $exitCode = 0;
        if ($this->option('seed')) {
            $this->line('Running seeders...');
        
            $exitCode = $this->call('db:seed');
        
            if ($exitCode == 0)
                $this->info('Database seeded successfully');
            else
                throw new \RuntimeException("Error while running seeders, exit code $exitCode");
        }
    }
}

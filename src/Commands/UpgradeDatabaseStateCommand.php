<?php
namespace Webcosmonauts\Alder\Commands;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Webcosmonauts\Alder\Facades\AlderScheme;
use Webcosmonauts\Alder\Models\Modifiers\BaseModifier;
use Illuminate\Console\Command;
use Webcosmonauts\Alder\Facades\Alder;

class UpgradeDatabaseStateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alder:db:upgrade
                            {--M|migrate : Run migrations before executing command}
                            {--F|fresh : Run fresh migrations before executing command}
                            {--S|seed : Run migrations with seeders before executing command}';
    
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
        $exitCode = 1;
        if ($this->option('migrate') || $this->option('fresh') || $this->option('seed')) {
            $command = 'migrate';
            if ($this->option('fresh'))
                $command .= ':fresh';
            if ($this->option('seed'))
                $command .= ' --seed';
            $exitCode = Artisan::call($command);
        }
        if ($exitCode !== 1)
            throw new \RuntimeException("Error while running migrations, exit code $exitCode");
        
        $modifiers = Alder::getLcmModels();
        if (is_null($modifiers))
            throw new \RuntimeException('App is not running in console');
        
        $this->line('Synchronizing modifier properties with database...');
        
        foreach ($modifiers as $modifier) {
            /** @var BaseModifier $model*/
            AlderScheme::upgrade($modifier);
        }
        
        return;
    }
}

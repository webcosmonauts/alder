<?php
namespace Webcosmonauts\Alder\Commands;

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
    protected $signature = 'alder:db:upgrade';
    
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
        echo implode("\n", config('alder.modifiers'));
        return;
        
        $this->line('Synchronizing modifier properties with database...');
        
        $lcms = Alder::getLcmModels();
        if (is_null($lcms)) {
            $this->error('App is not running in console');
            return;
        }
        
        $models = [];
        foreach ($lcms as $model) {
            /** @var BaseModifier $model*/
            $model = new $model;
            if (empty($model->leaf_type)) {
                $this->error("$model has empty leaf type");
                continue;
            }
            
            if (is_array($model->leaf_type)) {
                foreach ($model->leaf_type as $leaf_type)
                    $models[$leaf_type] = $model;
            }
            else
                $models[$model->leaf_type] = $model;
        }
        
        foreach ($lcms as $model) {
            /** @var BaseModifier $model*/
            $model = new $model;
            if (empty($model->leaf_type)) {
                $this->error("$model has empty leaf type");
                continue;
            }
            
            
        }
        
        return;
    }
}

<?php
namespace Webcosmonauts\Alder\Commands;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Webcosmonauts\Alder\Models\Modifiers\BaseModifier;
use Illuminate\Console\Command;
use Webcosmonauts\Alder\Facades\Alder;

class SynchronizeModifiersAndDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alder:modifiers:synchronize
                            {--M|migrate : Run migrations before executing command}
                            {--F|fresh : Run fresh migrations before executing command}
                            {--S|seed : Run migrations with seeders before executing command}';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize modifier fields with database structure';
    
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
        
        $lcms = Alder::getLcmModels();
        if (is_null($lcms))
            throw new \RuntimeException('App is not running in console');
        
        $this->line('Synchronizing modifier properties with database...');
        
        foreach ($lcms as $model) {
            $model = new $model;
            /** @var BaseModifier $model*/
            
            // if array, apply to each leaf type
            if (is_array($model->leaf_type)) {
                foreach ($model->leaf_type as $leaf_type)
                    $this->synchronize($model, $leaf_type);
            }
            else
                $this->synchronize($model, $model->leaf_type);
        }
        
        return;
    }
    
    private function synchronize(BaseModifier $model, $leaf_type) {
        $table_name = "{$leaf_type}_modifiers";
        if (!Schema::hasTable($table_name)) {
            Schema::create($table_name, function (Blueprint $table) use ($model, $leaf_type) {
                $table->integer('leaf_id')->primary();
                foreach ($model->fields as $field => $params)
                    $this->addColumn($table, $model, "{$model->prefix}_$field", $field, $params);
            });
        }
        else {
            Schema::table($table_name, function (Blueprint $table) use ($table_name, $model, $leaf_type) {
                foreach ($model->fields as $field => $params) {
                    if (!Schema::hasColumn($table_name, $field))
                        $this->addColumn($table, $model, "{$model->prefix}_$field", $field, $params);
                }
            });
        }
    }
    
    private function addColumn(Blueprint &$table, BaseModifier $model, $column_name, $field, $params) {
        if (isset($params['default'])) {
            $method = 'default';
            $param = $params['default'];
        }
        elseif (isset($params['nullable']) && $params['nullable'] == true) {
            $method = 'nullable';
            $param = true;
        }
        
        switch ($params['type']) {
            case 'number':
                if (isset($method, $param))
                    $table->integer($column_name)->$method($param);
                else
                    $table->integer($column_name);
                break;
            case 'text':
                if (isset($method, $param))
                    $table->string($column_name)->$method($param);
                else
                    $table->string($column_name);
                break;
            // TODO: add textarea (we currently only have 'text')
            case 'textarea':
                if (isset($method, $param))
                    $table->text($column_name)->$method($param);
                else
                    $table->text($column_name);
                break;
            default:
                $this->warn("Field \"$field\" has unsupported type \"{$params['type']}\" in " . get_class($model));
        }
    }
}
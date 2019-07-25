<?php


namespace Webcosmonauts\Alder\Commands;


use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Facades\AlderScheme;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\Modifiers\PostModifier;
use Webcosmonauts\Alder\Models\Modifiers\SeoKeywordModifier;
use Webcosmonauts\Alder\Structure\StructureState;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alder:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test';

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

        $t1 = now();
        DB::transaction(function() {

        // for($i = 0; $i < 60000; $i++) {
        //     $post = new Leaf();
        //     $post->leaf_type_id = 2;
        //     $post->save();
        //
        //     $k = new SeoKeywordModifier;
        //     $k->id = $post->id;
        //     $k->save();
        // }

        });
        $t2 = now();

        $leaf = Leaf::where('id', 1)->with('Alder/postModifier')->first();
        dump($leaf->{'Alder/postModifier'}->thumbnail);
        dump($leaf->{'Alder/postModifier'}->lang('pl')->thumbnail);


        return;
    }

}

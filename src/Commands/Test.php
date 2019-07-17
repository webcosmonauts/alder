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

//            for($i = 0; $i < 100000; $i++) {
//                $post = new Leaf();
//                $post->leaf_type_id = 1;
//                $post->save();
//
//                for($j = 1; $j <= 5; $j++) {
//                    /** @var SeoKeywordModifier $keyword */
//                    $keyword = SeoKeywordModifier::find($j);
//                    $keyword->posts()->save($post);
//                    // $keyword->save();
//                }
//            }

//            $tbl = SeoKeywordModifier::getTableName();
//            $class = SeoKeywordModifier::class;
//            $struct = new StructureState(SeoKeywordModifier::structure);
//            $columns = [];
//            $columns[] = 'leaves.id as Leaf.id';
//            $columns[] = 'leaves.created_at as Leaf.created_at';
//            $columns[] = 'leaves.updated_at as Leaf.updated_at';
//            $columns[] = $tbl .'.id as '. $class .'.id';
//            foreach($struct->fields->keys() as $field) {
//                $columns[] = $tbl .'.'. $field .' as '. $class .'.'. $field;
//            }
//            $raw = DB::table('leaves')
//                ->where('leaves.leaf_type_id', 2)
//                ->join($tbl, $tbl.'.id', '=', 'leaves.id')
//                ->where('leaves.id', '<', 3150)
//                ->select($columns)->get();
//
//            $leaves = new Collection();
//            $modifiers = new Collection();
//
//            foreach($raw as $entry) {
//                $attributes = [];
//                foreach($entry as $column => $value) {
//                    [$prefix, $field] = explode('.', $column);
//                    if($prefix == $class) $attributes[$field] = $value;
//                }
//                $modifiers = $modifiers->concat($class::hydrate([$attributes]));
//            }

        });
        $t2 = now();

//        $this->comment($posts);
//        $this->comment($t2->diff($t1)->s);

        dump(Leaf::withModifiers(['alder/seoKeyword'])->first()->alder->seoKeyword->keyword);
        dump(Leaf::withModifiers(['alder/seoKeyword']));
//        $leaf = Leaf::first();
//
//        dump($leaf->alder->seoKeyword->keyword);
//        dump($leaf);

        return;
    }

}

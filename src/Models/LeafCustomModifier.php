<?php
    
namespace Webcosmonauts\Alder\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Support\Carbon;

/**
 * Class LeafCustomModifier
 *
 * @property int id
 * @property string title
 * @property string slug
 * @property object modifiers
 * @property int leaf_type_id
 *
 * @property LeafType leaf_type
 */
class LeafCustomModifier extends BaseModel
{
    use Translatable;
    
    protected $table = 'lcms';
    public $translatedAttributes = ['title', 'group_title'];
    
    protected $casts = [
        'modifiers' => 'object'
    ];
    
    public function leaf_type() {
        return $this->belongsTo(LeafType::class);
    }
}

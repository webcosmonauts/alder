<?php

namespace Webcosmonauts\Alder\Http\Controllers\LeavesController;

use Illuminate\Routing\Controller;
use PhpParser\Node\Expr\Cast\Object_;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\LeafCustomModifier;
use Webcosmonauts\Alder\Models\LeafCustomModifierValue;
use Webcosmonauts\Alder\Models\LeafStatus;
use Webcosmonauts\Alder\Models\LeafType;

class LeafEntityController extends Controller
{
    /**
     * Get Leaf
     *
     * @param Integer $id, int|string $leaf_type
     * @return Object
     * @throws EmptyId
     */
    public static function getLeaf($id, $leaf_type = 'pages')
    {
        $leaf = Leaf::whereStatusId(LeafStatus::whereSlug('published')->value('id'))
            ->where('leaf_type_id', (is_int($leaf_type) ? $leaf_type : LeafType::whereSlug($leaf_type)->value('id')))
            ->where('is_accessible', true)
            ->find($id);
        if(empty($leaf)){
            return false;
        }
        else{
            return $leaf;
        }
    }

    /**
     * Get Leaf by Slag
     *
     * @param String $slug, int|string $leaf_types
     * @return Object
     * @throws EmptyId
     */
    public static function getLeafBySlag($slug, $leaf_type = 'pages')
    {
        $leaf = Leaf::whereTranslation('slug', $slug)
            ->whereStatusId(LeafStatus::whereSlug('published')->value('id'))
            ->whereLeafTypeId((is_int($leaf_type) ? $leaf_type : LeafType::whereSlug($leaf_type)->value('id')))
            ->where('is_accessible',true)
            ->first();
        if(empty($leaf)){
            return false;
        }
        else{
            return $leaf;
        }
    }

    /**
     * Get Leaf Title
     *
     * @param Integer $id
     * @return String
     * @throws EmptyId
     */
    public static function getLeafTitle($id)
    {
        $leaf = Leaf::find($id);
        if(empty($leaf)){
            return false;
        }
        else{
            return $leaf->title;
        }
    }

    /**
     * Get Leaf Slug
     *
     * @param Integer $id
     * @return String
     * @throws EmptyId
     */
    public static function getLeafSlug($id)
    {
        $leaf = Leaf::find($id);
        if(empty($leaf)){
            return false;
        }
        else{
            return $leaf->slug;
        }
    }

    /**
     * Get Leaf Content
     *
     * @param Integer $id
     * @return String
     * @throws EmptyId
     */
    public static function getLeafContent($id)
    {
        $leaf = Leaf::find($id);
        if(empty($leaf)){
            return false;
        }
        else{
            return $leaf->content;
        }
    }

    /**
     * Get Leaf Type
     *
     * @param Integer $id
     * @return String
     * @throws EmptyId
     */
    public static function getLeafType($id)
    {
        $leaf_type = Alder::getLeafType($id);
        if(empty($leaf_type)){
            return false;
        }
        else{
            return $leaf_type;
        }
    }

    /**
     * Get Leaves by type
     *
     * @param String $leaf_type
     * @return String
     * @throws EmptyId
     */
    public static function getLeavesByType($leaf_type)
    {
        $leaves_per_page = Alder::getRootValue('leaves_per_page');
        return Leaf::where('leaf_type_id', LeafType::where('slug',$leaf_type)->value('id'))
            ->where('status_id', LeafStatus::where('slug', 'published')->value('id'))
            ->paginate($leaves_per_page);
    }

    /**
     * Get All Leaves by type
     *
     * @param String $leaf_type
     * @return String
     * @throws EmptyId
     */
    public static function getAllLeavesByType($leaf_type)
    {
        $leaves_query = LeafType::where('slug',$leaf_type)->value('id');
        $leaves = Leaf::where('leaf_type_id', $leaves_query)->get();

        return $leaves;
    }

    /**
     * Get Leaf Custom modifiers values
     *
     * @param Integer $id
     * @return Object
     * @throws EmptyId
     */

    public static function getLeafCustomModifiersValues($id)
    {
        $leaf = Leaf::find($id);
        $lcmv = LeafCustomModifierValue::where('id', '=', $leaf->LCMV_id)->firstOrFail();
        if(empty($lcmv)){
            return false;
        }
        else{
            return $lcmv;
        }
    }

    /**
     * Get Leaf tags
     *
     * @param Integer $id
     * @return Object
     * @throws EmptyId
     */

    public static function getLeafTags($id)
    {
        $leaf = Leaf::find($id);
        $lcmv = LeafCustomModifierValue::where('id', '=', $leaf->LCMV_id)->firstOrFail();
        $tags_raw = $lcmv->values->tags;
        $tags = Leaf::whereIn('id',$tags_raw)->get();
        if(empty($lcmv)){
            return false;
        }
        else{
            return $tags;
        }
    }

    /**
     * Get Leaf categories
     *
     * @param Integer $id
     * @return Object
     * @throws EmptyId
     */
    public static function getLeafCategories($id)
    {
        $leaf = Leaf::find($id);
        $lcmv = LeafCustomModifierValue::where('id', '=', $leaf->LCMV_id)->firstOrFail();
        $categories_raw = $lcmv->values->categories;
        $categories = Leaf::whereIn('id',$categories_raw)->get();
        if(empty($lcmv)){
            return false;
        }
        else{
            return $categories;
        }
    }

    /**
     * Get Leaf template
     *
     * @param Integer $id
     * @return Object
     * @throws EmptyId
     */
    public static function getLeafTemplate($id)
    {
        $leaf = Leaf::find($id);

        $leaf_type_object = Alder::getLeafType($leaf->leaf_type_id);
        //Get leaf type
        $leaf_type = $leaf_type_object->slug;
        //Check if leaf is singular
        $is_singular = $leaf_type_object->is_singular;

        $lcmv = self::getLeafCustomModifiersValues($id);
        if($leaf_type == 'pages'){
            if(!empty($lcmv)){
                return $lcmv->values->template;
            }
            else{
                return "";
            }
        }
        else{

        }

    }

}

<?php

namespace Webcosmonauts\Alder\Http\Controllers\LeavesController;

use Illuminate\Routing\Controller;
use PhpParser\Node\Expr\Cast\Object_;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\LeafCustomModifier;
use Webcosmonauts\Alder\Models\LeafCustomModifierValue;

class LeafEntityController extends Controller
{
    /**
     * Get Leaf
     *
     * @param Integer $id
     * @return Object
     * @throws EmptyId
     */
    public static function getLeaf($id)
    {
        $leaf = Leaf::where('id', '=', $id)->where('is_accessible',true)->firstOrFail();
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
     * @param String $slug
     * @return Object
     * @throws EmptyId
     */
    public static function getLeafBySlag($slug)
    {
        $leaf = Leaf::where('slug', '=', $slug)->where('is_accessible',true)->firstOrFail();
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
        $leaf = self::getLeaf($id);
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
        $leaf = self::getLeaf($id);
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
        $leaf = self::getLeaf($id);
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
        $leaf = self::getLeaf($id);

        if(empty($leaf)){
            return false;
        }
        else{
            return $leaf->content;
        }
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
        $leaf = self::getLeaf($id);
        $lcmv = LeafCustomModifierValue::where('id', '=', $leaf->LCMV_id)->firstOrFail();
        if(empty($lcmv)){
            return false;
        }
        else{
            return $lcmv;
        }
    }

    public static function getLeafTemplate($id)
    {

        $lcmv = self::getLeafCustomModifiersValues($id);

        if(empty($lcmv)){
            return false;
        }
        elseif(empty($lcmv->values->template)){
            return ".leaf";
        }
        else{
            return ".templates.".$lcmv->values->template;
        }
    }

}

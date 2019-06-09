<?php
namespace Webcosmonauts\Alder\Http\Controllers\TemplateControllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Webcosmonauts\Alder\Http\Controllers\LeafController;

class TemplateController extends Controller
{
    /**
     * Get single leaf view
     *
     * @param String $request
     * @param String $slug
     * @param LeafController $theme
     * @return View
     * @throws AssigningNullToNotNullableException
     */
    public static function getViewForLeaf($leaf_type){
        
    }

    /**
     * Get templates names
     *
     * @param String $theme
     * @return Object
     * @throws IsNullThemeParameter
     */
    public static function getTemplatesNames($theme){

        $files = File::allFiles(resource_path('views/templates/'.$theme.'/templates'));
        $all_files = array();
        foreach ($files as $file)
        {
            if ( ! preg_match( '|Template Name:(.*)$|mi', file_get_contents( $file ), $header ) )
                continue;
            $all_files[] =  $header[1];
        }

        return (object)$all_files;
    }

    /**
     * Get templates Object
     *
     * @param String $theme
     * @return Object
     * @throws IsNullThemeParameter
     */
    public static function getTemplatesObject($theme){

        $files = File::allFiles(resource_path('views/templates/'.$theme.'/templates'));
        $all_files = array();
        foreach ($files as $file)
        {
            if ( ! preg_match( '|Template Name:(.*)$|mi', file_get_contents( $file ), $header ) )
                continue;
            $all_files[] =  array(
                "theme_name"=>$header[1],
                //"blade_name"=>(string)str_replace('.blade', '', $file->getFilenameWithoutExtension())
                "blade_name"=>(string)$file->getFilenameWithoutExtension()
            );
        }

        return (object)$all_files;
    }

}

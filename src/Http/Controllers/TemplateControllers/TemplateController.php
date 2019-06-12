<?php
namespace Webcosmonauts\Alder\Http\Controllers\TemplateControllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Http\Controllers\LeafController;
use Webcosmonauts\Alder\Models\Root;
use Webcosmonauts\Alder\Models\RootType;

class TemplateController extends Controller
{

    /**
     * Get [B]READ view of roots
     *
     * @return View
     */
    public function index()
    {
        /* Get admin panel menu items */
        $admin_menu_items = Alder::getMenuItems();
        $active_theme = Alder::getRootValue('theme');

        /* Get available themes */
        $themes = self::getThemes();

        /* Return view with prefilled data */
        return view('alder::bread.appearance.browse')->with([
            'active_theme'=>$active_theme,
            'themes' => $themes,
            'admin_menu_items' => $admin_menu_items,
        ]);
    }

    public function update(Request $request)
    {

        foreach ($request->except(['_token']) as $key => $value) {
            Alder::setRootValue($key, $value);
            echo 'Updated' . $key . '<br>';

            return redirect()->back()->with(['success' => 'Settings is save']);
        }

    }

    /**
     * Get all themes
     *
     * @return Object
     * @throws NoThemes
     */
    public static function getThemes(){
        $theme_folders = File::directories(public_path('themes'));

        $themes = [];
        foreach ($theme_folders as $single_folder)
        {
            $single_instance = array(
                "theme_name"=>"",
                "theme_slug"=>"",
                "theme_description"=>"",
                "theme_uri"=>"",
                "author"=>"",
                "author_uri"=>"",
                "version"=>"",
                "tags"=>"",
                "license"=>"",
                "screenshot"=>"",
            );
            if(file_exists($single_folder."/Readme.md")):
                //Get theme name
                if ( ! preg_match( '|#Theme name:(.*)$|mi', file_get_contents( $single_folder."/Readme.md"), $match ) )
                    continue;
                $single_instance["theme_name"]=$match[1];

                //Get theme slug
                if ( ! preg_match( '|#Theme slug:(.*)$|mi', file_get_contents( $single_folder."/Readme.md"), $match ) )
                    continue;
                $single_instance["theme_slug"]=trim($match[1]);

                //Get theme description
                if ( ! preg_match( '|##Theme description:(.*)$|mi', file_get_contents( $single_folder."/Readme.md"), $match ) )
                    continue;
                $single_instance["theme_description"]=$match[1];

                //Get theme URI
                if ( ! preg_match( '|##Theme URI:(.*)$|mi', file_get_contents( $single_folder."/Readme.md"), $match ) )
                    continue;
                $single_instance["theme_uri"]=$match[1];

                //Get author
                if ( ! preg_match( '|###Author:(.*)$|mi', file_get_contents( $single_folder."/Readme.md"), $match ) )
                    continue;
                $single_instance["author"]=$match[1];

                //Get author URI
                if ( ! preg_match( '|###Author URI:(.*)$|mi', file_get_contents( $single_folder."/Readme.md"), $match ) )
                    continue;
                $single_instance["author_uri"]=$match[1];

                //Get author URI
                if ( ! preg_match( '|####Version:(.*)$|mi', file_get_contents( $single_folder."/Readme.md"), $match ) )
                    continue;
                $single_instance["version"]=$match[1];

                //Get theme tags
                if ( ! preg_match( '|#####Tags:(.*)$|mi', file_get_contents( $single_folder."/Readme.md"), $match ) )
                    continue;
                $single_instance["tags"]=explode(',',$match[1]);

                //Get license
                if ( ! preg_match( '|#####License:(.*)$|mi', file_get_contents( $single_folder."/Readme.md"), $match ) )
                    continue;
                $single_instance["license"]=$match[1];


            endif;

            //Get screenshot if available
            if(file_exists($single_folder."/screenshot.png")):
                $theme_folder_name = explode("\\",$single_folder);
                $single_instance["screenshot"] = "themes/".array_pop($theme_folder_name)."/screenshot.png";
            endif;
            $themes[] = $single_instance;
        }
        //dd($themes);
        return (object)$themes;
    }

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
                "label"=>$header[1],
                "template_name"=>(string)str_replace('.blade', '', $file->getFilenameWithoutExtension()),
                "blade_name"=>(string)$file->getFilenameWithoutExtension()
            );
        }

        return (object)$all_files;
    }

}

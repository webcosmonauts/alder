<?php

namespace Webcosmonauts\Alder\Http\Controllers\TemplateControllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Facades\LeafHelper;
use Webcosmonauts\Alder\Http\Controllers\LeafController;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\LeafType;
use Webcosmonauts\Alder\Models\Root;
use Webcosmonauts\Alder\Models\RootType;

class ViewHierarchyController extends Controller
{
    public function index(){
        /* Get admin panel menu items */
        $admin_menu_items = Alder::getMenuItems();

        //Get active theme value
        $active_theme = Alder::getRootValue('active-theme');

        $views_hierarchy = Alder::getRootValue('views_hierarchy');

        /* Get available themes */
        $theme = TemplateController::getThemeInfo($active_theme);

        //Get templates for active theme
        $templates = TemplateController::getTemplatesNames($active_theme);

        $page_id = LeafType::where('slug', 'pages')->value('id');
        $pages = Leaf::where('leaf_type_id',$page_id)->get();

        foreach (['static_index_page','views_hierarchy',
                     'leaves_per_page','timezone','favicon'] as $vals) {
            $roots[] = Root::where('slug',$vals)->get();
        }



        /* Return view with prefilled data */
        return view('alder::bread.theme-settings.browse')->with([
            'active_theme' => $active_theme,
            'views_hierarchy'=>$views_hierarchy,
            'theme' => $theme,
            'templates' => $templates,
            'pages' => $pages,
            'admin_menu_items' => $admin_menu_items,
            'roots' => $roots,
        ]);
    }

    public function setViewsHierarchy(Request $request)
    {

        foreach ($request->except(['_token']) as $key => $value) {
            Alder::setRootValue($key, $value);
            echo 'Updated' . $key . '<br>';

            return redirect()->back()->with(['success' => 'Settings is save']);
        }

    }
    public function saveRoots(Request $request)
    {
        foreach (['static_index_page','leaves_per_page',
                     'timezone','favicon'] as $keys => $vals) {
            if ($vals =! 'favicon') {
                $roots = Root::where('slug',$vals)->get()->first();
                $roots->value = $request->$vals;
//            dd($roots->value);
                $roots->save();
            }else {
                $file_div = '';
                $file_name_db = '';
                if ($request->userfile) {
                    $file_name = ($request->userfile->getClientOriginalName());
                    $file_div = public_path() . '\img\icons\_'. date('mdYHis'). '_' . $file_name;
                    $file_name_db = '_'. date('mdYHis'). '_' . $request->name . '_' . $file_name;


                    if (move_uploaded_file($request->userfile, $file_div)) {
                        echo 'good';

                    } else {
                        echo 'error';
                    }
                }
                $roots = Root::where('slug','favicon')->get()->first();
                if ($file_name_db)
                    $roots->value = $file_name_db;

                $roots->save();
            }

        }

        return redirect()->back()->with(['success' => 'Settings is save']);

    }
}

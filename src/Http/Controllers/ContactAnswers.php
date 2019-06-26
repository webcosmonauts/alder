<?php

namespace Webcosmonauts\Alder\Http\Controllers;


use Alder;
use Illuminate\Http\Request;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\LeafType;

class ContactAnswers extends BaseController
{
    //
    public function index() {


        $answer_type = LeafType::where('slug','contact-form-message')->value('id');
        $answers = Leaf::where('leaf_type_id', $answer_type)->get();
        
        return view('alder::bread.answers.browse')->with([
            'admin_menu_items' => Alder::getMenuItems(),
            'answers' => $answers
        ]);
    }


}

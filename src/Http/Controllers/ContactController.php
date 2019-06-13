<?php

namespace Webcosmonauts\Alder\Http\Controllers;

use Alder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


//require 'path/to/PHPMailer/src/Exception.php';
//require 'path/to/PHPMailer/src/PHPMailer.php';
//require 'path/to/PHPMailer/src/SMTP.php';

use Illuminate\Http\Request;

use App\Http\Requests;
use Webcosmonauts\Alder\Exceptions\AssigningNullToNotNullableException;
use Webcosmonauts\Alder\Http\Controllers\BaseController;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\LeafCustomModifierValue;
use Webcosmonauts\Alder\Models\LeafType;
use Webcosmonauts\Alder\Models\RootType;
use Webcosmonauts\Alder\Models\User;

class ContactController extends BaseController {


    public function index(){
        /* Get leaf type with custom modifiers */
        $root_types = RootType::with('roots')->get();

        $forms = Leaf::where('leaf_type_id', 8)->get();


        return view('alder::bread.contact-forms.browse')->with([
            'admin_menu_items' => Alder::getMenuItems(),
            'forms' => $forms
        ]);
    }

    public function create()
    {
        $root_type = RootType::with('roots')
            ->where('name', 'Mailing')->first();

        $roots = Alder::getRootsValues($root_type->roots);

        return view('alder::bread.contact-forms.edit')->with([
            'admin_menu_items' => Alder::getMenuItems(),
            'roots' => $roots,
            'edit' => false,
        ]);
    }
    public function edit(Request $request, $id)
    {

        $form = Leaf::where('id',$id)->get();
        $form = $form[0];

        return view('alder::bread.contact-forms.edit')->with([
            'admin_menu_items' => Alder::getMenuItems(),
            'request' => $request,
            'form' => $form,
            'edit' => true
        ]);
    }

    public function update(Request $request, $id)
    {
        $edit = true;
        $branchType = $this->getBranchType($request);
        /* Get leaf type with custom modifiers */
        $leaf_type = \Webcosmonauts\Alder\Facades\Alder::getLeafType($branchType);
        /* Get combined parameters of all LCMs */
        $params = Alder::combineLeafTypeLCMs($leaf_type);

        return $this->createForm($edit, $request, $leaf_type,$params, $id);
    }

    public function save_form(Request $request)
    {

        $branchType = $this->getBranchType($request);
        /* Get leaf type with custom modifiers */
        $leaf_type = \Webcosmonauts\Alder\Facades\Alder::getLeafType($branchType);
        /* Get combined parameters of all LCMs */
        $params = Alder::combineLeafTypeLCMs($leaf_type);
        $edit = false;
        $id = '15457';
        return $this->createForm($edit, $request, $leaf_type, $params, $id);
    }


    public function read(Request $request, $id){

        $forms = Leaf::where('leaf_type_id', 8)->where('id', $id)->get()->first();

        $lines = preg_split('/([\[:\]\s])/', $forms['content']);
        $linevs = explode("\n", str_replace(array("\r\n", "\r", "[", "]"), "\n", $forms['content']));

        $lin = array();
        foreach ($lines as $val){
            if (!empty($val))
                $lin[] = $val;
        }
        $lincs = array();
        foreach ($linevs as $val){
            if (!empty($val))
                $lincs[] = $val;
        }



        return view('alder::bread.contact-forms.read')->with([
            'admin_menu_items' => Alder::getMenuItems(),
            'request' => $request,
            'forms' => $forms,
            'lin' => $lin,
            'lincs' => $lincs
        ]);
    }

    public function store(Request $request)
    {
        $mail = new PHPMailer(TRUE);

        try{

            $max_rozmiar = 1024*1024*25;
            if (isset($_FILES['uploaded_file'])) {
                if ($_FILES['uploaded_file']['size'] > $max_rozmiar) {
                    return redirect()->back()->with(['error'=>'Error, your file is very big ( > 25 Mb)']);
                } else {
                    $mail->AddAttachment($_FILES['uploaded_file']['tmp_name'],
                        $_FILES['uploaded_file']['name']);
                }
            }

            $array_of_addresses = array();
            if(strpos($request['template-content'],'name:')){
                $error = "array?";
                var_dump($error);
                $array_of_addresses_raw = preg_split('/([\[:\]\s])/', $request['template-content']);
                $array_of_addresses_raw = array_filter($array_of_addresses_raw, function ($value) {
                    return !empty($value);
                });
                $array_of_emails = array();
                foreach ($array_of_addresses_raw as $key => $val) {
                    if ($val == 'email'){
                        $array_of_emails[] = $array_of_addresses_raw[$key + 2];
                    }
                }
                foreach ($array_of_emails as $key => $val) {
                    if ($this->isValidEmail($val)){
                        $array_of_addresses[] = $val;
                    }
                }
                if (empty($array_of_addresses)){
                    return redirect()->back()->with(['error_email'=>'Sorry, fix emails']);
                }
                echo '<pre>';
                print_r($array_of_addresses);
                echo '</pre>';
            }
            else{
                return redirect()->back()->with(['error_email'=>'Sorry, fix emails']);
            }
//
////          Settings SMTP
            $mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = 's24.linuxpl.com';
            $mail->SMTPAuth = TRUE;
            $mail->Username = 'rawa@nfinity.pl';
            $mail->Password = 'G111d*#`Le1H2@@';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->setFrom($request['recipient'], $request['sender']);

            dd($request->all());
            if(count($array_of_addresses) == 0){
                return redirect()->back()->with(['error_email'=>'Sorry, fix emails(1)']);
            }
            elseif(count($array_of_addresses) == 1){
                $mail->addAddress($array_of_addresses[0], "Your name");

                $mail->isHTML(true);
                $mail->Subject = $request['title'];
                $mail->Body    = $request['content'];
                $mail->AltBody = $request['message-content'];
                //$mail->addAttachment('/');
                $mail->send();
                echo 'Message has been sent';
                return redirect()->back()->with(['success'=>'Message is send']);
            }
            elseif(count($array_of_addresses) > 1){
                foreach ($array_of_addresses as $key=>$sigle_address_to_send):
                    $mail->addAddress($sigle_address_to_send, "Your name");
                    $mail->isHTML(true);
                    $mail->Subject = $request['title'];
                    $mail->Body    = $request['content'];
                    $mail->AltBody = $request['message-content'];
                    //$mail->addAttachment('/');
                    $mail->send();
                    echo 'Message has been sent';
                    return redirect()->back()->with(['success'=>'Message is send']);
                endforeach;
            }
            else{
                $error = "Something wnt wrong";
                return redirect()->back()->with(['error_sthng'=>'Sorry, unexpected error']);
            }
        }
        catch (Exception $e) {
//            echo $e->errorMessage();
//            echo "Fack";
            return redirect()->back()->with(['error_sthng'=>'Sorry, unexpected error']);
        }
        catch (\Exception $e) {
//            echo $e->getMessage();
//            echo "Fack!!!!!";
            return redirect()->back()->with(['error_sthng'=>'Sorry, unexpected error']);

        };
    }

    /**
     *
     * Get array for template-content
     * @param Request $request
     * @return array
     *
     */
    function temp_array ($array){
        $array_of_addresses_raw = array_filter($array, !empty($array));
    }

    function isValidEmail($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function createForm ($edit, Request $request, LeafType $leaf_type, $params, $id) {
        return DB::transaction(function () use ($edit, $request, $leaf_type, $params, $id) {
            try {

                $form = $edit ? Leaf::where('id',$id)->get()->first() : new Leaf();

                $form->title = $request->title;
                $form->slug = $request->slug;
                $form->content = $request['template-content'];
                $form->is_accessable = $request->is_accessable == 'on' ? 1 : 0;
                $edit ? : $form->status_id = 5;
                $edit ? : $form->leaf_type_id = 8;
                $edit ? : $form->user_id = Auth::user()->id;
                $edit ? : $form->created_at = date("Y-m-d H:i:s");
                $form->updated_at = date("Y-m-d H:i:s");
                $form->revision = 0;

                $LCMV = $edit ? $form->LCMV : new LeafCustomModifierValue();
                $values = [];
//                dd($params);
                foreach ($params->fields as $field_name => $modifiers) {
//                    dd($modifiers);
                    if (isset($request->$field_name) && !empty($request->$field_name))
                        $values[$field_name] = $request->$field_name;
                    else {

                        if (isset($modifiers->default))
                            $values[$field_name] = $modifiers->default;
                        else if (isset($modifiers->nullable) && $modifiers->nullable)
                            $values[$field_name] = null;
                        else
                            throw new AssigningNullToNotNullableException($field_name);
                    }
                }
                $LCMV->values = $values;
                $LCMV->save();

                $form->LCMV_id = $LCMV->id;

                $form->save();

                return \Webcosmonauts\Alder\Facades\Alder::returnRedirect(
                    $request->ajax(),
                    __('alder::generic.successfully_'
                        . ('created')) . " $form->title",
                    route("alder.contact-forms.index"),
                    true,
                    'success'
                );
            } catch (\Exception $e) {
                DB::rollBack();
                return Alder::returnResponse(
                    $request->ajax(),
                    __('alder::messages.processing_error'),
                    false,
                    'danger',
                    $e->getMessage()
                );
            }
        });
    }

}
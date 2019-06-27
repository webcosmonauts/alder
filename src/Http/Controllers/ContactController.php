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
use Webcosmonauts\Alder\Models\Root;
use Webcosmonauts\Alder\Models\Translations\LeafTranslation;
use Webcosmonauts\Alder\Models\User;

class ContactController extends BaseController {
    public function index() {
        $forms = Leaf::where(
            'leaf_type_id',
            LeafType::where('slug', 'contact-forms')->value('id')
        )->get();

//        session('locale')



        return view('alder::bread.contact-forms.browse')->with([
            'admin_menu_items' => Alder::getMenuItems(),
            'leaves' => $forms,
        ]);
    }

    public function create() {
        $root_type = RootType::with('roots')
            ->where('slug', 'mailing')->first();

        $roots = Alder::getRootsValues($root_type->roots);

        $mailer_type = RootType::where('slug','mailing')->value('id');
        $mailer = Root::where('root_type_id', $mailer_type)->get();

        $read = false;

        return view('alder::bread.contact-forms.edit')->with([
            'admin_menu_items' => Alder::getMenuItems(),
            'roots' => $roots,
            'edit' => false,
            'mailer' => $mailer,
            'read' => $read,
            'id' => false,
            'arr_total' => ''
        ]);
    }

    public function edit(Request $request, $id)
    {

        $form = Leaf::find($id);
        $content = json_decode($form->content);

        $template = (array) $content;

        $lines = preg_split('/([\[:\]\s])/', $template['template-content']);

        $lin = array();
        foreach ($lines as $val){
            if (!empty($val))
                $lin[] = $val;
        }
        $array_key = array();
        foreach ($lin as $key=>$value) {
            if ($value == 'name') {
                $array_key[] = $lin[$key + 1];
            }
        }
        foreach ($array_key as $key => $value) {
            $array_key[$key] = '[' . $value . ']';
        }

        $arrau_mailer = ['use-smtp', 'smtp-host', 'smtp-port', 'encryption', 'login', 'password', 'from', 'recipient',
            'sender', 'theme', 'additional_headers', 'message_content'];

        $array_mailer = array();
        foreach ($template as $key => $value) {
            foreach ($arrau_mailer as $val) {
                if ($key == $val) {
                    $array_mailer[$key] = $template[$key];
                }
            }
        }
        $read = true;

        return view('alder::bread.contact-forms.edit')->with([
            'admin_menu_items' => Alder::getMenuItems(),
            'request' => $request,
            'form' => $form,
            'edit' => true,
            'id' => $id,
            'arr_total' => '',
            'content' => $content,
            'template' => $template,
            'read' => $read,
            'array_mailer' => $array_mailer,
            'array_key' => $array_key
        ]);
    }
    public function edit_mailer(Request $request, $id)
    {

        $form = LeafTranslation::where('leaf_id',$id)->get()->first();
        $content = json_decode($form['content']);


        $array_names = $request->all();

        $template = (array) $content;
        $arrau_mailer = ['use-smtp', 'smtp-host', 'smtp-port', 'encryption', 'login', 'password', 'from', 'recipient',
            'sender', 'theme', 'additional_headers', 'message_content'];

        $array_mailer = array();
        foreach ($template as $key => $value) {
            foreach ($arrau_mailer as $val) {
                if ($key == $val) {
                    $array_mailer[$key] = $template[$key];
                }
            }
        }



        $total_key = array();
        foreach ($array_names as $key => $value){
            $total_key['[' . $key . ']'] = $value;
        }



//dd($total_key);
//        dd($content, $array_names, $array_mailer, $total_key);


        $total = array();
        foreach ($array_mailer as $key => $value) {
            $temp = $value;
            foreach ($total_key as $keys => $val) {
//                dump($value);
                $temp = str_replace($keys, $val, $temp);
            }
//            dump($temp);

            $total[$key] = $temp;
        }

        $this->store($total);
        return Alder::returnResponse(
            $request->ajax(),
            __('alder::messages.mail_sent_success'),
            true,
            'success');


//
//        return view('alder::bread.contact-forms.edit')->with([
//            'admin_menu_items' => Alder::getMenuItems(),
//            'request' => $request,
//            'form' => $form,
//            'edit' => true,
//            'mailer' => $mailer,
//            'array_names' => $array_names,
//            'id' => $id,
//            'arr_total' => $arr_total,
//            'read' => $read
//        ]);
    }

    public function destroy(Request $request, int $id) {

        return
            Leaf::where('id',$id)->delete()

                ? Alder::returnResponse(
                $request->ajax(),
                __('alder::messages.delete_successfully'),
                true,
                'success'
            )
                : Alder::returnResponse(
                $request->ajax(),
                __('alder::messages.processing_error'),
                false,
                'danger'
            );
    }

    public function update(Request $request, $id)
    {
//        dd($request);
        $edit = true;
        $branchType = $this->getBranchType($request);
        /* Get leaf type with custom modifiers */
        $leaf_type = \Webcosmonauts\Alder\Facades\Alder::getLeafType($branchType);
        /* Get combined parameters of all LCMs */
        $params = Alder::combineLCMs($leaf_type);
        $content = json_encode($request->all());

        return $this->createForm($edit, $request, $leaf_type,$params,$content, $id);
    }

    public function save_form(Request $request)
    {

        $branchType = $this->getBranchType($request);

        /* Get leaf type with custom modifiers */
        $leaf_type = Alder::getLeafType($branchType);

        /* Get combined parameters of all LCMs */

        $params = Alder::combineLCMs($leaf_type);
        $edit = false;

        $content = json_encode($request->all());
        return $this->createForm($edit, $request, $leaf_type, $params, $content);
    }



    public function show(Request $request, $id){

//        $cont_id = Leaf::where()

        $forms = Leaf::find($id);
        $contact = json_decode($forms->content);
        $contact = (array) $contact;
//        dd($contact['template-content']);
        $lines = preg_split('/([\[:\]\s])/', $contact['template-content']);
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

//        $strings_free = array();
//        $strings = explode(']', $contact['template-content']);
//        foreach ($strings as $value) {
//            $strings_free[] = explode('[', $value);
//        }
//        $strings = array();
//        foreach ($strings_free as $value) {
//            if ($value[0])
//                $strings[] = $value[1];
//        }
//        dd($strings);

        return view('alder::bread.contact-forms.read')->with([
            'admin_menu_items' => Alder::getMenuItems(),
            'request' => $request,
            'forms' => $forms,
            'lin' => $lin,
            'lincs' => $lincs,
            'id' => $id,
            'contact' => $contact
        ]);
    }

    public function pars_mailer (Request $request) {
//
//        echo 'Parser Mailer';
//
//        $array_mass = explode('|', $request->array_mail);
//        $array_keys = explode('*', $array_mass[0]);
//        $array_vals = explode('*', $array_mass[1]);
//
//        $total_key = array();
//        foreach ($array_keys as $keyk => $valk){
//            $total_key['[' . $valk . ']'] =  $array_vals[$keyk];
//        }
//
//        $total = array();
//        foreach ($request->all() as $request_key => $request_value){
//            $temp = $request_value;
//            foreach ($total_key as $keys => $vals) {
//                $temp = str_replace($keys, $vals, $temp);
//            }
//            $total[$request_key] = $temp;
//        }
//
//
//        return $this->store($total);
    }

    public function store($total)
    {
        $mail = new PHPMailer(TRUE);

        try{
//            $max_rozmiar = 1024*1024*25;
//            if (isset($_FILES['uploaded_file'])) {
//                if ($_FILES['uploaded_file']['size'] > $max_rozmiar) {
//                    return redirect()->back()->with(['error'=>'Error, your file is very big ( > 25 Mb)']);
//                } else {
//                    $mail->AddAttachment($_FILES['uploaded_file']['tmp_name'],
//                        $_FILES['uploaded_file']['name']);
//                }
//            }
//
//            $array_of_addresses = array();
//            if(strpos($request['template-content'],'name:')){
//                $error = "array?";
//                var_dump($error);
//                $array_of_addresses_raw = preg_split('/([\[:\]\s])/', $request['template-content']);
//                $array_of_addresses_raw = array_filter($array_of_addresses_raw, function ($value) {
//                    return !empty($value);
//                });
//                $array_of_emails = array();
//                foreach ($array_of_addresses_raw as $key => $val) {
//                    if ($val == 'email'){
//                        $array_of_emails[] = $array_of_addresses_raw[$key + 2];
//                    }
//                }
//                foreach ($array_of_emails as $key => $val) {
//                    if ($this->isValidEmail($val)){
//                        $array_of_addresses[] = $val;
//                    }
//                }
//                if (empty($array_of_addresses)){
//                    return redirect()->back()->with(['error_email'=>'Sorry, fix emails']);
//                }
//                echo '<pre>';
//                print_r($array_of_addresses);
//                echo '</pre>';
//            }
//            else{
//                return redirect()->back()->with(['error_email'=>'Sorry, fix emails']);
//            }
//
////          Settings SMTP

            $mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = $total['smtp-host'];
            $mail->SMTPAuth = TRUE;



            $mail->Username = $total['login'];

            $mail->Password = $total['password'];

            $mail->SMTPSecure = $total['encryption'];
            $mail->Port = $total['smtp-port'];

            $mail->setFrom($total['from'], $total['sender']);




//            if(count($array_of_addresses) == 0){
//                return redirect()->back()->with(['error_email'=>'Sorry, fix emails(1)']);
//            }
//            elseif(count($array_of_addresses) == 1){
            $mail->addAddress($total['recipient'], "");
            $mail->isHTML(true);


            $mail->Subject = $total['theme'];
            $mail->Body    = $total['message_content'];
            $mail->AltBody = $total['message_content'];
            //$mail->addAttachment('/');

            $mail->send();

            return redirect()->back();
//            }
//            elseif(count($array_of_addresses) > 1){
//                foreach ($array_of_addresses as $key=>$sigle_address_to_send):
//                    $mail->addAddress($sigle_address_to_send, "");
//                    $mail->isHTML(true);
//                    $mail->Subject = $request['title'];
//                    $mail->Body    = $request['content'];
//                    $mail->AltBody = $request['message-content'];
//                    //$mail->addAttachment('/');
//                    $mail->send();
//                    echo 'Message has been sent';
//                    return redirect()->back()->with(['success'=>'Message is send']);
//                endforeach;
//            }
//            else{
//                $error = "Something wnt wrong";
//                return redirect()->back()->with(['error_sthng'=>'Sorry, unexpected error']);
//            }
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

    private function createForm ($edit, Request $request, LeafType $leaf_type, $params, $content, $id = null) {
        return DB::transaction(function () use ($edit, $request, $leaf_type, $params, $content, $id) {
            try {


                $cont_id = LeafType::where('slug', 'contact-forms')->value('id');

                $form = $edit ? Leaf::where('id',$id)->get()->first() : new Leaf();
                $LCMV = $edit ? $form->LCMV : new LeafCustomModifierValue();

                $form->title = $request->title;
                $form->slug = $request->slug;
                $form->content = $content;
                $form->is_accessible = $request->is_accessible == 'on' ? 1 : 0;
                $edit ? : $form->status_id = 5;
                $edit ? : $form->leaf_type_id = $cont_id;
                $edit ? : $form->user_id = Auth::user()->id;
                $edit ? : $form->created_at = date("Y-m-d H:i:s");
                $form->updated_at = date("Y-m-d H:i:s");
                $form->revision = 0;




                $LCMV->values = $this->addValue($request, $params->lcm);

                $LCMV->save();

                $form->LCMV_id = $LCMV->id;

                $form->save();

                $id = $form->id;

                return \Webcosmonauts\Alder\Facades\Alder::returnRedirect(
                    $request->ajax(),
                    __('alder::generic.successfully_'
                        . ($edit ? 'updated' : 'created')) . " $form->title",
                    route("alder.contact-forms.store"),
                    true,
                    'success'
                );
            } catch (\Exception $e) {
                DB::rollBack();
                return \Webcosmonauts\Alder\Facades\Alder::returnResponse(
                    $request->ajax(),
                    __('alder::messages.processing_error'),
                    false,
                    'danger',
                    $e->getMessage()
                );
            }
        });
    }
    private function addValue($request, $params, $values = []) {
        foreach ($params as $field_name => $modifiers) {
            if (!isset($modifiers->type)) {
                $values[$field_name] = $this->addValue($request, $modifiers->fields);
            }
            else {
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
        }
        return $values;
    }

}
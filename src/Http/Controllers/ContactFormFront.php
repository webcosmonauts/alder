<?php

namespace Webcosmonauts\Alder\Http\Controllers;

use Alder;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Webcosmonauts\Alder\Exceptions\AssigningNullToNotNullableException;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\LeafCustomModifierValue;
use Webcosmonauts\Alder\Models\LeafType;
use Webcosmonauts\Alder\Models\Translations\LeafTranslation;


class ContactFormFront extends BaseController
{
    public function saveForm (Request $request, $id) {




        /* Get leaf type with custom modifiers */
        $leaf_type = Alder::getLeafType('contact-forms');

        /* Get combined parameters of all LCMs */

        $params = Alder::combineLCMs($leaf_type);

        $content = json_encode($request->all());

        $this->createForm($request, $leaf_type, $params, $content);

        $form = Leaf::find($id);
        $content = json_decode($form->content);

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

        $total = array();
        foreach ($array_mailer as $key => $value) {
            $temp = $value;
            foreach ($total_key as $keys => $val) {
                $temp = str_replace($keys, $val, $temp);
            }

            $total[$key] = $temp;
        }



        $this->store($total);

        return redirect()->back()->with(['success'=>'Message sent']);

//        return Alder::returnResponse(
//            $request->ajax(),
//            __('alder::messages.mail_sent_success'),
//            true,
//            'success');
//
//        dd($request);


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

            $mail->send($mail);

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


    private function createForm (Request $request, LeafType $leaf_type, $params, $content, $id = null) {
        return DB::transaction(function () use ($request, $leaf_type, $params, $content, $id) {
            try {


                $cont_id = LeafType::where('slug', 'contact-form-message')->value('id');

                $form = new Leaf();


                $LCMV = new LeafCustomModifierValue();

                $form->title = null;
                $form->slug = null;
                $form->content = $content;
                $form->is_accessible = 1;
                $form->status_id = 5;
                $form->leaf_type_id = $cont_id;
                $form->created_at = date("Y-m-d H:i:s");
                $form->updated_at = date("Y-m-d H:i:s");
                $form->revision = 0;




                $LCMV->values = $this->addValue($request, $params->lcm);

                $LCMV->save();

                $form->LCMV_id = $LCMV->id;

                $form->save();

                $id = $form->id;

                return redirect()->back();
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

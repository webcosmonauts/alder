<?php

namespace Webcosmonauts\Alder\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Models\Root;
use Webcosmonauts\Alder\Models\RootType;
use Webcosmonauts\Alder\Models\User;

class RegisterController extends BaseController
{
    public function index()
    {
        return view('alder::registration.register');
    }
    public function verificated()
    {
        return view('alder::registration.verificated');
    }
    public function activation(Request $request)
    {
        return view('alder::registration.verification')->with([
            'request' => $request
        ]);
    }

    public function save(Request $request)
    {
        return $this->saveUser($request ,false);
    }



    private function saveUser(Request $request, $edit) {
        return DB::transaction(function () use ($request, $edit) {
            try {

                $User = new User();

                $User->name = $request->name;
                $User->surname = $request->surname;
                $User->email = $request->exampleInputEmail;
                if ($request->password)
                    $User->password = bcrypt($request->password);
                $User->is_active = 0;
                $User->LCM_id = $request->LCM_id;
                $User->LCMV_id = $request->LCMV_id;

                $User->created_at = date("Y-m-d H:i:s");
                $User->updated_at = date("Y-m-d H:i:s");

                $User->save();

                if ($this->ver_mail($request, $User)) {
                    return redirect()->back()->with(['success'=>'Message is send']);
                };

//                return redirect()->back()->with(['success'=>'Message is send']);
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()->with(['error_sthng'=>'Sorry, email is not valid or exist']);
            }
        });
    }


    private function ver_mail (Request $request, $user){
        $mail = new PHPMailer(TRUE);

        try{

            $mailer_type = RootType::where('slug','mailing')->value('id');
            $mailer = Root::where('root_type_id', $mailer_type)->get();

            foreach ($mailer as $val) {
                $total[$val->slug] = $val->value;
            }

            $total['recipient'] = $request->exampleInputEmail;
            $total['theme'] = $request->name;
            $total['additional_headers'] = $request->name;
            $total['message_content'] = $request->name;

            $activationLink = url('/activation?' . 'id=' . $user->id . '&_token=' . md5($request->exampleInputEmail));

//          Settings SMTP
            $mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = $total['smtp-host'];
            $mail->SMTPAuth = TRUE;


            $mail->Username = $total['login'];

            $mail->Password = $total['password'];

            $mail->SMTPSecure = $total['encryption'];
            $mail->Port = $total['smtp-port'];

            $mail->setFrom($total['from'], $total['sender']);

            $mail->addAddress($total['recipient'] , "");

            $mail->isHTML(true);
            $mail->Subject = $total['theme'];
            $mail->Body    = $total['message_content'] . '<br>' . $activationLink;
            $mail->AltBody = $total['message_content'];
            //$mail->addAttachment('/');



            $mail->send();
            echo 'Message has been sent';
            return redirect()->back()->with(['success'=>'Message is send']);
        }
        catch (\PHPMailer\PHPMailer\Exception $e) {
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

}

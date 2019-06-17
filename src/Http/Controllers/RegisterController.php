<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Models\Root;
use Webcosmonauts\Alder\Models\RootType;
use Webcosmonauts\Alder\Models\User;

class RegisterController extends Controller
{
    public function index()
    {
        return view('alder::registration.register');
    }
    public function verificated()
    {
        return view('alder::registration.verificated');
    }

    public function save(Request $request)
    {
        return $this->saveUser($request);
    }



    private function saveUser(Request $request) {
        return DB::transaction(function () use ($request) {
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

                $this->ver_mail($request, $User);


                return Alder::returnRedirect(
                    $request->ajax(),
                    __('alder::generic.successfully_'
                        . ('created')) . " $User->name",
                    route("register.verificated"),
                    true,
                    'success'
                );
            } catch (Exception $e) {
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

//            dd($total);
            $activationLink = route('activation', ['id' => $user->id, 'token' => md5($user->email)]);


            dd($activationLink);
            dd($request);
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
            $mail->Body    = $total['message_content'];
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

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Verta;

class AccountMail extends Mailable
{
    use Queueable, SerializesModels;



    public $subject;
    public $username;
    public $password;
    public $multiuser;
    public $connection_start;
    public $traffic;
    public $type_traffic;
    public $expdate;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject,$username,$password,$multiuser,$connection_start,$traffic,$type_traffic,$expdate)
    {
        if(env('APP_LOCALE', 'en')=='fa') {
            if (!empty($expdate)) {
                $expdate=$this->persianToenglishNumbers($expdate);
                $expdate = Verta::parse($expdate)->datetime()->format('Y-m-d');
                $expdate_y= Verta::instance($expdate)->format('Y');
                $expdate_d=Verta::instance($expdate)->format('d');
                $expdate_m = Verta::instance($expdate)->formatWord('F');
                $expdate = $expdate_y.' '.$expdate_m.' '.$expdate_d;
            }
        }
        $this->subject = $subject;
        $this->username = $username;
        $this->password = $password;
        $this->multiuser = $multiuser;
        $this->connection_start = $connection_start;
        $this->traffic = $traffic;
        $this->type_traffic = $type_traffic;
        $this->expdate = $expdate;
    }
    public function persianToenglishNumbers($input)
    {
        $persianNumbers = [
            '۰' => '0',
            '۱' => '1',
            '۲' => '2',
            '۳' => '3',
            '۴' => '4',
            '۵' => '5',
            '۶' => '6',
            '۷' => '7',
            '۸' => '8',
            '۹' => '9',
        ];

        return strtr($input, $persianNumbers);
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('accountmail');
    }
}

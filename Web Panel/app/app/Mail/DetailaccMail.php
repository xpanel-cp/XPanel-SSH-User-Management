<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Verta;
use DateTime;
class DetailaccMail extends Mailable
{
    use Queueable, SerializesModels;



    public $subject;
    public $username;
    public $password;
    public $multiuser;
    public $traffic;
    public $total;
    public $expdate;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject,$username,$password,$multiuser,$traffic,$total,$expdate)
    {
        $start_inp = date("Y-m-d");
        $today = new DateTime($start_inp); // تاریخ امروز
        $futureDate = new DateTime($expdate);
        $interval = $today->diff($futureDate);
        $expdate = $interval->days;

        $this->subject = $subject;
        $this->username = $username;
        $this->password = $password;
        $this->multiuser = $multiuser;
        $this->traffic = $traffic;
        $this->total = $total;
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
        return $this->view('detailacc');
    }
}

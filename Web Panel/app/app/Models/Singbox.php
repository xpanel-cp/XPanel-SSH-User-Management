<?php

namespace App\Models;

use App\Models\Settings;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Singbox extends Model
{
    use HasFactory;
    protected $fillable = [
        'port_sb',
        'name',
        'email',
        'mobile',
        'multiuser',
        'start_date',
        'end_date',
        'date_one_connect',
        'customer_user',
        'protocol_sb',
        'detail_sb',
        'status',
        'traffic',
        'desc',
        'sni'
    ];
    public function xtraffic() {
        return $this->belongsTo(Trafficsb::class, 'port_sb', 'port_sb');

    }
    public static function generateUniqueUUIDAndPort()
    {
        $setting = Settings::first();
        if(empty($setting->tls_port) || $setting->tls_port==null)
        {
            $tls_port='444';
        }
        else
        {
            $tls_port=$setting->tls_port;
        }

        $defaultPorts = [22, 443, 80, 8880, 9990, env('DB_PORT'), env('PORT_SSH'), env('MAIL_PORT'), $tls_port, env('PORT_DROPBEAR'), env('PORT_PANEL'), env('REDIS_PORT')];

        do {
            $uuid = sprintf(
                '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                rand(0, 0xffff),
                rand(0, 0xffff),
                rand(0, 0xffff),
                rand(0, 0x0fff) | 0x4000,
                rand(0, 0x3fff) | 0x8000,
                rand(0, 0xffff),
                rand(0, 0xffff),
                rand(0, 0xffff)
            );

            $port = rand(1000, 65535);

            $isDefaultPort = in_array($port, $defaultPorts);

            $existsInDatabase = self::where('detail_sb->uuid', $uuid)
                ->where('detail_sb->port', $port)
                ->exists();

        } while ($isDefaultPort || $existsInDatabase);

        return [
            'uuid' => $uuid,
            'port' => $port,
        ];
    }

    public static function generateUniqueBase64()
    {
        $setting = Settings::first();
        if(empty($setting->tls_port) || $setting->tls_port==null)
        {
            $tls_port='444';
        }
        else
        {
            $tls_port=$setting->tls_port;
        }

        $defaultPorts = [22, 443, 80, 8880, 9990, env('DB_PORT'), env('PORT_SSH'), env('MAIL_PORT'), $tls_port, env('PORT_DROPBEAR'), env('PORT_PANEL'), env('REDIS_PORT')];
        function generatePassword() {
            $key1 = random_bytes(64);
            $key2 = random_bytes(32);

            $password1 = base64_encode(hash_hmac('sha256', 'plaintext1', $key1, true));
            $password2 = base64_encode(hash_hmac('sha256', 'plaintext2', $key2, true));

            return "$password1";
        }
        do {
            $uuid = generatePassword();
            $port = rand(1000, 65535);

            $isDefaultPort = in_array($port, $defaultPorts);

            $existsInDatabase = self::where('detail_sb->uuid', $uuid)
                ->where('detail_sb->port', $port)
                ->exists();

        } while ($isDefaultPort || $existsInDatabase);

        return [
            'uuid' => $uuid,
            'port' => $port,
        ];
    }

}

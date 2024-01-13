<?php

function checkHTTP($ip, $port)
{
    $url = "http://$ip:$port/api";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 404) {
        return true;
    } else {
        return false;
    }
}

function checkHTTPS($ip, $port)
{
    $url = "https://$ip:$port/api";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 404) {
        return true;
    } else {
        return false;
    }
}

$filename = '../app/.env';
$port_panel = '';
$direct_panel = '';
$bot_token = '';
$id_admin = '';
$bot_access_token = '';
$bot_log = '';
$file = fopen($filename, 'r');

if ($file) {
    while (($line = fgets($file)) !== false) {
        if (strpos($line, 'PORT_PANEL=') !== false) {
            $parts = explode('=', $line);
            if (count($parts) == 2) {
                $port_panel = trim($parts[1]);
            }
        }

        if (strpos($line, 'PANEL_DIRECT=') !== false) {
            $parts = explode('=', $line);
            if (count($parts) == 2) {
                $direct_panel = trim($parts[1]);
            }
        }
        if (strpos($line, 'BOT_TOKEN=') !== false) {
            $parts = explode('=', $line);
            if (count($parts) == 2) {
                $bot_token = trim($parts[1]);
            }
        }
        if (strpos($line, 'BOT_ID_ADMIN=') !== false) {
            $parts = explode('=', $line);
            if (count($parts) == 2) {
                $id_admin = trim($parts[1]);
            }
        }
        if (strpos($line, 'BOT_API_ACCESS=') !== false) {
            $parts = explode('=', $line);
            if (count($parts) == 2) {
                $bot_access_token = trim($parts[1]);
            }
        }
        if (strpos($line, 'BOT_LOG=') !== false) {
            $parts = explode('=', $line);
            if (count($parts) == 2) {
                $bot_log = trim($parts[1]);
            }
        }
    }

    fclose($file);
}
/*
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $address = htmlspecialchars($_POST["address"]);
    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);
    if (!empty($address) and !empty($username) and !empty($password)) {

        $access = 'false';
        $url_api = '';
        if (checkHTTP($address, $port_panel)) {
            $access = 'true';
            $url_api = "http://$address:$port_panel/api/sync/usercheck";
        } elseif (checkHTTPS($address, $port_panel)) {
            $access = 'true';
            $url_api = "https://$address:$port_panel/api/sync/usercheck";
        } else {
            $access = 'false';
        }

        if ($access == 'true') {
            $post = [
                'username' => $username,
                'password' => $password
            ];
            $ch = curl_init($url_api);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            $response = curl_exec($ch);
            $response = json_decode($response, true);
            $response = json_encode($response, true);
            curl_close($ch);
            header('Content-Type: application/json');
            echo $response;
        }
    }

}
*/
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    /*
        if (isset($_GET["detail"])) {
            $address = htmlspecialchars($_GET["address"]);
            $username = htmlspecialchars($_GET["username"]);
            $password = htmlspecialchars($_GET["password"]);
            if (!empty($address) and !empty($username) and !empty($password)) {
                $access = 'false';
                $url_api = '';
                if (checkHTTP($address, $port_panel)) {
                    $access = 'true';
                    $url_api = "http://$address:$port_panel/api/sync/getuser/$username/$password";
                } elseif (checkHTTPS($address, $port_panel)) {
                    $access = 'true';
                    $url_api = "https://$address:$port_panel/api/sync/getuser/$username/$password";
                } else {
                    $access = 'false';
                }

                if ($access == 'true') {
                    $ch = curl_init($url_api);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
                    curl_setopt($ch, CURLOPT_HTTPGET, true);
                    $response = curl_exec($ch);
                    $response = json_decode($response, true);
                    $response = json_encode($response, true);
                    curl_close($ch);
                    header('Content-Type: application/json');
                    echo $response;
                }
            }
        }
        */
    function bot($bot_token, $method, $data = [])
    {
        $apiUrl = "https://api.telegram.org/bot{$bot_token}/{$method}";

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error cURL: ' . curl_error($ch);
        }

        curl_close($ch);

        return $response;
    }

    function sendTelegramMessage($bot_token, $chatId, $text, $keyboard = null, $mrk = 'html')
    {
        $data = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => $mrk,
            'disable_web_page_preview' => true,
            'reply_markup' => $keyboard
        ];

        return bot($bot_token, 'sendMessage', $data);
    }

    function sendTelegramFile($bot_token, $chatId, $text, $file, $mrk = 'html')
    {
        $data = [
            'chat_id' => $chatId,
            'document' => $file,
            'caption' => $text
        ];

        return bot($bot_token, 'sendDocument', $data);
    }
    if (isset($_GET["bot"])) {
        if (!empty($bot_token) and !empty($id_admin) and !empty($bot_access_token)) {
            $current_time = time();
            $time_difference = 12 * 3600;
            if (!empty($bot_log) and $current_time > ($bot_log + $time_difference)) {
                shell_exec("sed -i 's/BOT_LOG=.*/BOT_LOG={$current_time}/g' /var/www/html/app/.env");
                $url = "https://api.telegram.org/bot$bot_token/getWebhookInfo";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);
                if ($response === false) {
                    echo "error";
                } else {
                    $jsonData = json_decode($response, true);

                    if ($jsonData === null) {
                        echo "error JSON.";
                    } else {
                        $data = json_decode($response, true);

                        if ($data && isset($data['result']['url'])) {
                            $url = $data['result']['url'];
                            $urlParts = explode('/', $url);
                            $host = $urlParts[2];
                            $ch = curl_init('https://' . $host . ":$port_panel/api/$bot_access_token/backup");
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_HTTPGET, true);
                            $response = curl_exec($ch);
                            $response = json_decode($response, true);
                            curl_close($ch);
                            if ($response['message'] == 'Backup Maked') {
                                $file_url = $response['link'];
                                $file_name = basename($file_url);
                                shell_exec("sudo cp /var/www/html/app/storage/backup/$file_name /var/www/html/example/$file_name");
                                $zip_name = explode('.', $file_name);
                                $zip_name = $zip_name[0];
                                $zipFileName = $zip_name . '.zip';
                                exec("zip -r $zipFileName $file_name", $output, $returnCode);
                                sleep(1);
                                sendTelegramFile($bot_token, $id_admin, $file_name, "https://$host/$zip_name.zip");
                                shell_exec("sudo rm -rf /var/www/html/app/storage/backup/$file_name");
                                shell_exec("sudo rm -rf /var/www/html/example/$file_name");
                                shell_exec("sudo rm -rf /var/www/html/example/$zip_name.zip");
                                $jsonUrl = 'https://' . $host . ":$port_panel/api/$bot_access_token/listuser/active";
                                $ch = curl_init($jsonUrl);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                $jsonData = curl_exec($ch);

                                if ($jsonData !== false) {
                                    $users = json_decode($jsonData, true);
                                    if ($users !== null) {
                                        $today = date('Y-m-d');
                                        $threeDaysAgo = date('Y-m-d', strtotime("2 days"));
                                        $usersToNotify = [];
                                        foreach ($users as $user) {
                                            $endDate = $user['end_date'];
                                            if ($endDate !== null) {
                                                if (strtotime($endDate) >= strtotime($today) && strtotime($endDate) <= strtotime($threeDaysAgo)) {
                                                    $usersToNotify[] = $user;
                                                }
                                            }
                                        }
                                        if (!empty($usersToNotify)) {
                                            $message = "<b>کاربران با تاریخ انقضا 2 روز یا کمتر</b>\n\n";
                                            foreach ($usersToNotify as $user) {
                                                $message .= "<b>{$user['username']}</b> ({$user['end_date']})\n";
                                            }
                                            sendTelegramMessage($bot_token, $id_admin, $message);
                                        }
                                    }
                                }

                            }
                        }
                    }
                }
            }
            elseif (empty($bot_log))
            {
                shell_exec("sed -i 's/BOT_LOG=.*/BOT_LOG={$current_time}/g' /var/www/html/app/.env");
                $url = "https://api.telegram.org/bot$bot_token/getWebhookInfo";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);
                if ($response === false) {
                    echo "error";
                } else {
                    $jsonData = json_decode($response, true);

                    if ($jsonData === null) {
                        echo "error JSON.";
                    } else {
                        $data = json_decode($response, true);

                        if ($data && isset($data['result']['url'])) {
                            $url = $data['result']['url'];
                            $urlParts = explode('/', $url);
                            $host = $urlParts[2];
                            $ch = curl_init('https://' . $host . ":$port_panel/api/$bot_access_token/backup");
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_HTTPGET, true);
                            $response = curl_exec($ch);
                            $response = json_decode($response, true);
                            curl_close($ch);
                            if ($response['message'] == 'Backup Maked') {
                                $file_url = $response['link'];
                                $file_name = basename($file_url);
                                shell_exec("sudo cp /var/www/html/app/storage/backup/$file_name /var/www/html/example/$file_name");
                                $zip_name = explode('.', $file_name);
                                $zip_name = $zip_name[0];
                                $zipFileName = $zip_name . '.zip';
                                exec("zip -r $zipFileName $file_name", $output, $returnCode);
                                sleep(1);
                                sendTelegramFile($bot_token, $id_admin, $file_name, "https://$host/$zip_name.zip");
                                shell_exec("sudo rm -rf /var/www/html/app/storage/backup/$file_name");
                                shell_exec("sudo rm -rf /var/www/html/example/$file_name");
                                shell_exec("sudo rm -rf /var/www/html/example/$zip_name.zip");
                                $jsonUrl = 'https://' . $host . ":$port_panel/api/$bot_access_token/listuser/active";
                                $ch = curl_init($jsonUrl);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                $jsonData = curl_exec($ch);

                                if ($jsonData !== false) {
                                    $users = json_decode($jsonData, true);
                                    if ($users !== null) {
                                        $today = date('Y-m-d');
                                        $threeDaysAgo = date('Y-m-d', strtotime("2 days"));
                                        $usersToNotify = [];
                                        foreach ($users as $user) {
                                            $endDate = $user['end_date'];
                                            if ($endDate !== null) {
                                                if (strtotime($endDate) >= strtotime($today) && strtotime($endDate) <= strtotime($threeDaysAgo)) {
                                                    $usersToNotify[] = $user;
                                                }
                                            }
                                        }
                                        if (!empty($usersToNotify)) {
                                            $message = "<b>کاربران با تاریخ انقضا 2 روز یا کمتر</b>\n\n";
                                            foreach ($usersToNotify as $user) {
                                                $message .= "<b>{$user['username']}</b> ({$user['end_date']})\n";
                                            }
                                            sendTelegramMessage($bot_token, $id_admin, $message);
                                        }
                                    }
                                }

                            }
                        }
                    }
                }
            }
        }
    }
}
?>

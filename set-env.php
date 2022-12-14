<?php

$path        = $argv[1];
$app_url     = $argv[2];
$backend_uri = $argv[3];
$db_host     = $argv[4];
$db_port     = $argv[5];
$db_database = $argv[6];
$db_username = $argv[7];
$db_password = $argv[8];

setEnvVar('APP_URL',$app_url,'http://localhost');
setEnvVar('BACKEND_URI',$backend_uri,'/admin');
setEnvVar('ACTIVE_THEME','your-theme','demo');
setEnvVar('DB_HOST',$db_host,'127.0.0.1');
setEnvVar('DB_PORT',$db_port,'3306');
setEnvVar('DB_DATABASE',$db_database,'database');
setEnvVar('DB_USERNAME',$db_username,'root');
setEnvVar('DB_PASSWORD',$db_password,'');
setEnvVar('MAIL_MAILER','SMTP','log');
setEnvVar('MAIL_HOST','[your-mail-host]','null');
setEnvVar('MAIL_PORT','[your-mail-port]','null');
setEnvVar('MAIL_USERNAME','[your-mail-address]','null');
setEnvVar('MAIL_PASSWORD','[your-mail-pass]','null');
setEnvVar('MAIL_ENCRYPTION','tls','null');
setEnvVar('MAIL_FROM_ADDRESS','[your-mail-address]','null');

function setEnvVar($key, $value, $old)
{
    global $path;
    $value = encodeEnvVar($value);

    if (file_exists($path)) {
        file_put_contents($path, str_replace(
            [$key.'='.$old, $key.'='.'"'.$old.'"'],
            [$key.'='.$value, $key.'='.$value],
            file_get_contents($path)
        ));
    }

}

function encodeEnvVar($value)
{
    if (!is_string($value)) {
        return $value;
    }

    // Escape quotes
    if (strpos($value, '"') !== false) {
        $value = str_replace('"', '\"', $value);
    }

    // Quote values with comment, space, quotes
    $triggerChars = ['#', ' ', '"', "'"];
    foreach ($triggerChars as $char) {
        if (strpos($value, $char) !== false) {
            $value = '"'.$value.'"';
            break;
        }
    }

    return $value;
}

?>
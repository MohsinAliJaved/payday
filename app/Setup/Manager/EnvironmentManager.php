<?php


namespace App\Setup\Manager;


use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EnvironmentManager
{

    public function copyEnv()
    {
        if (!file_exists($this->getEnvPath())) {
            return copy(base_path('.env.example'), $this->getEnvPath());
        }
        return true;
    }

    public function saveFileWizard(Request $request)
    {
        $envFileData =
            'APP_NAME=\''.$request->get('app_name')."'\n".
            'APP_ENV='.$request->get('environment', 'production')."\n".
            'APP_KEY='.'base64:'.base64_encode(Str::random(32))."\n".
            'APP_DEBUG='.$request->get('app_debug', false)."\n".
            'APP_URL='.$request->get('app_url')."\n\n".
            'APP_LOCALE='.$request->get('app_locale', 'en')."\n".
            'APP_FALLBACK_LOCALE='.$request->get('app_locale', 'en')."\n".
            'APP_LOCALE_PHP='.$request->get('app_locale_php', 'en_US')."\n".
            'APP_TIMEZONE='.$request->get('app_timezone', 'UTC')."\n".
            'LOG_CHANNEL='.$request->get('log_channel', 'daily')."\n\n".
            'TELESCOPE_ENABLED='.'false'."\n\n".
            'APP_INSTALLED=false'."\n\n".
            'DB_CONNECTION='.$request->database_connection."\n".
            'DB_HOST='.$request->database_hostname."\n".
            'DB_PORT='.$request->database_port."\n".
            'DB_DATABASE='.$request->database_name."\n".
            'DB_USERNAME='.$request->database_username."\n".
            'DB_PASSWORD='.$request->database_password."\n\n".
            'BROADCAST_DRIVER='.$request->get('broadcast_driver', 'log')."\n".
            'CACHE_DRIVER='.$request->get('cache_driver', 'file')."\n".
            'QUEUE_CONNECTION='.$request->get('queue_connection', 'database')."\n".
            'SESSION_DRIVER='.$request->get('session_driver', 'cookie')."\n".
            'SESSION_LIFETIME='.$request->get('session_lifetime', '120')."\n".
            'SESSION_ENCRYPT='.$request->get('session_encrypt', 'false')."\n\n".
            'REDIS_HOST='.$request->get('redis_hostname', '127.0.0.1')."\n".
            'REDIS_PASSWORD='.$request->get('redis_password', 'null')."\n".
            'REDIS_PORT='.$request->get('redis_port', '6379')."\n\n";

        if ($this->copyEnv()) {
            return file_put_contents($this->getEnvPath(), $envFileData);
        }
    }

    public function setEnvironmentValue($envKey, $envValue)
    {
        $value = strtok(file_get_contents($this->getEnvPath(), "$envKey="));

        if (gettype($value) == 'boolean') {
            $value = $value ? 'true' : 'false';
        }

        file_put_contents($this->getEnvPath(), str_replace(
            $envKey.'='.$value, $envKey.'='.$envValue, file_get_contents($this->getEnvPath())
        ));

        return true;
    }



    public function getEnvPath()
    {
        return base_path('.env');
    }
}

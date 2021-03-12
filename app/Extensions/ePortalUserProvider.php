<?php

namespace App\Extensions;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use App\User;

class ePortalUserProvider implements UserProvider
{
    private $token;

    public function retrieveById($identifier)
    {
        dd("retrieveById");
    }
    public function retrieveByToken($identifier, $token)
    {
        return null;
    }
    public function updateRememberToken(Authenticatable $user, $token)
    {
        return null;
    }
    public function retrieveByCredentials(array $credentials)
    {
        if ($this->validation($credentials)) {
            return null;
        }

        return new User($this->getUserData($credentials));
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return true;
    }

    private function validation(array $credentials)
    {
        $years = date("Y");
        $months = date("m");
        $days = date("d");
        $date_buf = $years . $months . $days;
        $this->token = hash('sha256', $date_buf . $credentials['username'] . 'nutc');
        $url = "https://apps.nutc.edu.tw/bin/api2/verify_user.php?username=" . $credentials['username'] . "&password=" . $credentials['password'] . "&token=" . $this->token;
        $response = $this->curl($url);
        $xml = simplexml_load_string($response);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);
        $verify = $array["result"];

        return $verify == "TRUE";
    }

    private function getUserData(array $credentials)
    {
        $url = "https://apps.nutc.edu.tw/bin/api2/user_info.php?username=" . $credentials['username'] . "&token=" . $this->token;
        $response = $this->curl($url);
        $array = json_decode($response, TRUE);
    }

    private function curl($url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
        ));
        return curl_exec($curl);
    }
}

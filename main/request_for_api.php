<?php

include_once __DIR__ . '/vendor/autoload.php';
include_once __DIR__ . '/vendor/amocrm/oauth2-amocrm/src/AmoCRM.php';


class AmoCRMApiClientTestApi
{

    public function setUp(){
        $auth_data = [
            "access_token" => "",
            "client_id" => "",
            "client_secret" => "",
            "code" => "",
            "grant_type" => "authorization_code",
            "redirect_uri" => "https://google.com",
            "base_domain"=>"ppetrlipetsk.amocrm.ru"
        ];

        $provider = new AmoCRM([
            'clientId' => $auth_data["client_id"],
            'clientSecret' => $auth_data["client_secret"],
            'redirectUri' => $auth_data["redirect_uri"],
        ]);
        $provider->setBaseDomain($auth_data["base_domain"]);

        $accessToken = getToken();

        $provider->setBaseDomain($accessToken->getValues()['baseDomain']);

        if ($accessToken->hasExpired()) {
            /**
             * Получаем токен по рефрешу
             */
            try {
                $accessToken = $provider->getAccessToken(new League\OAuth2\Client\Grant\RefreshToken(), [
                    'refresh_token' => $accessToken->getRefreshToken(),
                ]);

            } catch (Exception $e) {
                die((string)$e);
            }
        }

        $token = $accessToken->getToken();

        try {
            $data = $provider->getHttpClient()
                ->request('GET', $provider->urlAccount() . 'api/v2/account', [
                    'headers' => $provider->getHeaders($accessToken)
                ]);

            $parsedBody = json_decode($data->getBody()->getContents(), true);
            printf('ID аккаунта - %s, название - %s', $parsedBody['id'], $parsedBody['name']);
        } catch (GuzzleHttp\Exception\GuzzleException $e) {
            var_dump((string)$e);
        }
    }

    public function testCompanies()
    {
        //$this->assertInstanceOf(Companies::class, $this->apiClient->companies());
        print($this->apiClient->contacts()->get());
        echo 1234567;
    }

}

$c=new AmoCRMApiClientTestApi();
$c->setUp();
//$c->testCompanies();

<?php

require_once 'vendor/autoload.php';
require_once 'amocrm.php';

class AmoCRMApiClientTestx
{
    private $apiClient;
    //private $apiClient;

    public function setUp()
    {
        $auth_data = [
            "access_token" => "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImE5OWNhMmM3Y2JhYTI5ZGU2ZWFiZmFiZmMyYWU3Nzc4MDI0MDg1NTZkYmFkMzBhM2UwMGE5ZjNlYmY4OTNkNTA0NDM0ZDk1ZWFmMTM4MDg2In0.eyJhdWQiOiJiM2JjNDBjZS0yZjJkLTQ0NjAtODZlYy01OGIxMTk5NDg2MzYiLCJqdGkiOiJhOTljYTJjN2NiYWEyOWRlNmVhYmZhYmZjMmFlNzc3ODAyNDA4NTU2ZGJhZDMwYTNlMDBhOWYzZWJmODkzZDUwNDQzNGQ5NWVhZjEzODA4NiIsImlhdCI6MTYyNTcyOTEwNiwibmJmIjoxNjI1NzI5MTA2LCJleHAiOjE2MjU4MTU1MDYsInN1YiI6IjcyMTgzMzciLCJhY2NvdW50X2lkIjoyOTU3OTMyMCwic2NvcGVzIjpbInB1c2hfbm90aWZpY2F0aW9ucyIsImNybSIsIm5vdGlmaWNhdGlvbnMiXX0.iy8b_KkOHQQtPRZ6w2TcKCz9YzG4bXyjGoHJcPs3VYw9fNLrQWzKqxTGVy0tdKL42WFPZVzYLYa3yjOnkEuIiUq9lACNukcII8nJAJBuhgZIAp1adqdd4o9kjeLqKIpsvs6klAwjUI--YOgBU38sQucs8hoMT8z-6bDEv1U8C8FL6RVblqnaEJqTXPqoq5PLArsnJvDU_f_hH7Ap70zGvlykB8k6QLReZkoxeCRFaALNRvX3F9ngm_MfEnyHNmRzU3ivy9-28rFa27ypXQmezT7xzKQch9K0hWopxUiEvCJbTTRiGEe3V7HeztEYw2aoOsDDa145s4TheUuy9hw92g",
            "client_id" => "b3bc40ce-2f2d-4460-86ec-58b119948636",
            "client_secret" => "UJZ3AC78iDxykC2Xxq07Pqc1905RAIiBX2iaCxlustkgDqESAnPVs0YdhHnAm7tN",
            "code" => "def5020049892f0e2392bf202af08054eadc39e43cadc208e721ed06a9f4704ee0dbd2a49745d5df84fc4374c300be92e467c4398eae428eb6d4341e034462f03014bd15a471640ed3b781a02e649ceca748302d6ae811755305c3372b5ab78baf9af349af0877fa31a7d5b1f153452e029514efdfcd602c9ba57fde25897ca6a524ed171cbb71b8f943f4ac483abe8b12102c127f45e88a9ec8b66622bf56d8e4fb7eee31986d772722d1b80025477e265742b9c5f55bc04ea33e05bd9cd46250558d89c2a9a873ecb1ebb7f76af46353a2f7ee67777789e9fa939271a19c69378b18bb27240a018ab611f9ec5bb3e200aad1cf354372065b184fd632fd18c791d6b11c1e6863cbb7f54842892c6ec569297832e61cc476fda46a4f593489d136c9ce4400422ce6862f40b0355deeef20e9d384d8bc7bbff7fa41cdba2c86dc10724d0c1cc72330213f0e38e9ead225ff8e4cad30ead58b3e47614578e14f0377176b554f1f64354c238a4189f13a02c0b972fa2695c816f895890c384ec4c4461e368622b0fe4920cd12d84d6096aa0fcf9850cdf50e38e66602d8c7fa232ea58fef6beafddd69180bb4ec23e1806c3e5d8bffb5357e65ccdf8df6",
            "grant_type" => "authorization_code",
            "redirect_uri" => "https://google.com"
        ];

        /*
        $auth_data=[
            "client_secret" => "",
            "client_id" => "",
            "code" => "",
            "grant_type" => "authorization_code",
            "redirect_uri" => "https://google.com",
            "access_token"=>""
        ];*/

        $this->apiClient = new AmoCRMApiClient($auth_data["client_id"], $auth_data["client_secret"], $auth_data["redirect_uri"]);

        $accessToken = $this->apiClient->getOAuthClient()->getAccessTokenByCode($auth_data["code"]);

//        $this->apiClient->setAccessToken($accessToken)
//            ->setAccountBaseDomain($accessToken->getValues()['baseDomain'])
//            ->onAccessTokenRefresh(
//                function (\League\OAuth2\Client\Token\AccessTokenInterface $accessToken, string $baseDomain) {
//                    saveToken(
//                        [
//                            'accessToken' => $accessToken->getToken(),
//                            'refreshToken' => $accessToken->getRefreshToken(),
//                            'expires' => $accessToken->getExpires(),
//                            'baseDomain' => $baseDomain,
//                        ]
//                    );
//                });

    }

    public function testCompanies()
    {
        //$this->assertInstanceOf(Companies::class, $this->apiClient->companies());
        print($this->apiClient->contacts()->get());
        echo 1234567;
    }

}

$c=new AmoCRMApiClientTestx();
$c->setUp();
$c->testCompanies();

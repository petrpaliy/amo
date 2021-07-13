<?php
require_once 'vendor/autoload.php';
require_once 'amocrm.php';

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\EntitiesServices\Account;
use AmoCRM\EntitiesServices\CatalogElements;
use AmoCRM\EntitiesServices\Catalogs;
use AmoCRM\EntitiesServices\Companies;
use AmoCRM\EntitiesServices\Contacts;
use AmoCRM\EntitiesServices\Customers\Customers;
use AmoCRM\EntitiesServices\Customers\Statuses;
use AmoCRM\EntitiesServices\Customers\Transactions;
use AmoCRM\EntitiesServices\CustomFieldGroups;
use AmoCRM\EntitiesServices\CustomFields;
use AmoCRM\EntitiesServices\EntityNotes;
use AmoCRM\EntitiesServices\EntityTags;
use AmoCRM\EntitiesServices\Events;
use AmoCRM\EntitiesServices\Leads;
use AmoCRM\EntitiesServices\Leads\LossReasons;
use AmoCRM\EntitiesServices\Leads\Pipelines;
use AmoCRM\EntitiesServices\Roles;
use AmoCRM\EntitiesServices\Segments;
use AmoCRM\EntitiesServices\Tasks;
use AmoCRM\EntitiesServices\Unsorted;
use AmoCRM\EntitiesServices\Users;
use AmoCRM\EntitiesServices\Webhooks;
use AmoCRM\EntitiesServices\Widgets;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\OAuth\AmoCRMOAuth;
use League\OAuth2\Client\Token\AccessToken;

class AmoCRMApiClientTest
{
    /**
     * @var AmoCRMApiClient
     */
    private $apiClient;

    public function setUp()
    {
        $auth_data = [
            "access_token" => "",
            "client_id" => "b3bc40ce-2f2d-4460-86ec-58b119948636",
            "client_secret" => "UJZ3AC78iDxykC2Xxq07Pqc1905RAIiBX2iaCxlustkgDqESAnPVs0YdhHnAm7tN",
            "code" => "def502003ff353763cec07652ea7f83858c866dde0191841e8853eb931dbbc984bcd63ee244d60b696a03904936e7ae5ef21f8c6750f22bb3373cf5ad4e8c009777b8e62f47f08d5e8fc1358632263b976a961bf8b941459adbc6268a41c574d35d912992c98c06c2c4d755c852b879845a0171b1e9cb6c435cd1cb26efd4f02efc989924f35ba249eeb861f6862d78567e9444e0a6c3f37db42c4396fdd7d10cdfaf4282a77f8db125f93ecc1077877ca6e3471f76c7b147f711075a354f36f4af84a294273d759844a850ebe4a921044f36bb9f52b39da03b28d5435c43a3bf9aacae91005b2a6f8315c54c224e32342514f75e0be398234d93d987e21c528342f62ff7d93e2c4fc298ca2f0cb53f562cd796b01ff92f9a40130a39c34f05e693152b045e003a86d04ebd2ff2548b8c91aa0c1b3a8947c92f022112e7f69f69f21c0e313dcb7e74218869ad22c9e2c1bb41c8782eac745f5df744991d4ff59c4765a39d35f7c932d005be7a261c307ea1602571f59f965539712f7dca6992dc06b2001ee518ac3a2070d633d872ecf750e3f74578e3f90fa2d82cf373dc8d7e76391e402f65445a8bbd41be618ac43b66759c89a89813c2ffcf6e2",
            "grant_type" => "authorization_code",
            "redirect_uri" => "https://google.com",
            "base_domain"=>"ppetrlipetsk.amocrm.ru"
        ];

        $this->apiClient = new AmoCRMApiClient($auth_data["client_id"], $auth_data["client_secret"], $auth_data["redirect_uri"]);
        $accessToken = $this->apiClient->getOAuthClient()->getAccessTokenByCode($auth_data["code"]);
        echo '<br>access_token='.$accessToken;


        /*
        $this->apiClient->setAccessToken($accessToken)
            ->setAccountBaseDomain($accessToken->getValues()['baseDomain'])
            ->onAccessTokenRefresh(
                function (\League\OAuth2\Client\Token\AccessTokenInterface $accessToken, string $baseDomain) {
                    saveToken(
                        [
                            'accessToken' => $accessToken->getToken(),
                            'refreshToken' => $accessToken->getRefreshToken(),
                            'expires' => $accessToken->getExpires(),
                            'baseDomain' => $baseDomain,
                        ]
                    );
                });

        $this->apiClient->setAccessToken(new AccessToken([
            'access_token' => 'xxx',
            'refresh_token' => 'xxx',
            'expires' => 1893456000,
            'baseDomain' => 'example.com',
        ]));

       $provider = new AmoCRM\OAuth2\Client\Provider\AmoCRM([
            'clientId' => '8a3895df-077a-438d-bb31-6e27c69a409d',
            'clientSecret' => 'yT0ECVffKPGK3jMuhCewRyYD5bgf1bcSNq9JppDrfKwgpoAca6cd8ssNEOUEKMmO',
            'redirectUri' => 'https://example.com',
        ]);

        $accessToken = $provider->getAccessToken(new League\OAuth2\Client\Grant\AuthorizationCode(), [
            'code' => $_GET['code'],
        ]);

        $this->apiClient->setAccessToken($accessToken)
            ->setAccountBaseDomain($accessToken->getValues()['baseDomain'])
            ->onAccessTokenRefresh(
                function (\League\OAuth2\Client\Token\AccessTokenInterface $accessToken, string $baseDomain) {
                    saveToken(
                        [
                            'accessToken' => $accessToken->getToken(),
                            'refreshToken' => $accessToken->getRefreshToken(),
                            'expires' => $accessToken->getExpires(),
                            'baseDomain' => $baseDomain,
                        ]
                    );
                });
        */
    }

    public function testCompanies()
    {
        //$this->assertInstanceOf(Companies::class, $this->apiClient->companies());
        print($this->apiClient->contacts()->get());
        echo 1234567;
    }
}

$c=new AmoCRMApiClientTest();
$c->setUp();
$c->testCompanies();

?>
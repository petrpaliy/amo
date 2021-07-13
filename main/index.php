<?php
require_once '/vendor/autoload.php';

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
        $this->apiClient = new AmoCRMApiClient('xxx', 'xxx', 'xxx');
        $this->apiClient->setAccessToken(new AccessToken([
            'access_token' => 'xxx',
            'refresh_token' => 'xxx',
            'expires' => 1893456000,
            'baseDomain' => 'example.amocrm.ru',
        ]));
    }

    public function testCompanies()
    {
        $this->assertInstanceOf(Companies::class, $this->apiClient->companies());
        print($this->apiClient->companies());
        echo 1234567;
    }
}

$c=new AmoCRMApiClientTest();
$c->setUp();
$c->testCompanies();

?>
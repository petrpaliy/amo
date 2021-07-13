<?php
define('TOKEN_FILE', 'token_info.json');
define('BASE_DOMAIN', 'ppetrlipetsk.amocrm.ru');
define('FIELD_CODE', 'MYMULTISELECTLISTCF_LIST9');

require_once 'vendor/autoload.php';

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\CompaniesCollection;
use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\CustomFields\CustomFieldEnumsCollection;
use AmoCRM\Collections\CustomFields\CustomFieldsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Collections\NotesCollection;
use AmoCRM\Collections\TasksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\CompanyModel;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\Customers\CustomerModel;
use AmoCRM\Models\CustomFields\EnumModel;
use AmoCRM\Models\CustomFields\MultiselectCustomFieldModel;
use AmoCRM\Models\CustomFieldsValues\MultiSelectCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\BaseCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultiselectCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\BaseCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\BaseEnumCustomFieldValueModel;
use AmoCRM\Models\Interfaces\CallInterface;
use AmoCRM\Models\LeadModel;
use AmoCRM\Models\NoteType\CallInNote;
use AmoCRM\Models\NoteType\ServiceMessageNote;
use AmoCRM\Models\TaskModel;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Ramsey\Uuid\Uuid;


class ApiExample
{
private $data = ['client_id' => '8a3895df-077a-438d-bb31-6e27c69a409d',
                'client_secret' => 'oCWjJIs4tDRisdEsehceFaEj1jJ27RXIRePb8ffwInsQZoUEIKzm8q8UPpYrMQSN',
                'grant_type' => 'authorization_code',
                'code' => 'def50200c78279c13311605514e0e16ad4f2aaa4ec295996166c6e5ea356e1f1ed2205f266fb0ef2f62b3597d1c5f18af851853f43ce019827912174aa25319ab89a523b62e66f76ce82c97613306723bcc52447556a68b3e71daf589980f208b0f077a3efc33f91c4078feb50bc6dc2d252a4406a19027e9a607c797a49295029d17a059ce3e5e8ea730821efa3f5e6fa5db17b39a6cb504b40dcfd714b24e11e655938b011749f6ff2543e9fbcaf59944839b7fd23121e17119dd4837a1a6e57e862e0b141fff5fb44a27470f26b3c9a65fe38fd04a8db538c284b7f2311c3358bcb57fa363764e6bbb3b363180cd7b19e1d509f597c62ac62045ddccf9705eea8c90da6eb07caec14fb9bf519449713e9c99e2fb3a4ad654588f8daa4ced7c51f0224dc5ff688a6984cf4b9f39a8b9c375f8b3ed1580f58710870a27f84baa39e024d413497a743a20721161d5a8951f9130c71b504a405daef5dc89b5cf7233805e27d658e8570093acb2b1bab5abdf20aed9f08b3a0b09400babff1902eb611ed3fa89e1a6143841e4d3f3ba365505079876091937daf4ee1a91da35b32f07fcdb72ed1fac6fb4b5de31ea9c5ce140eea0e942c69c3b069729e',
                'redirect_uri' => 'https://google.com',];



    public function askToken(): array
    {
        $link = 'https://' . BASE_DOMAIN .'/oauth2/access_token';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->data));
        $out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $code = (int)$code;
        $errors = [400 => 'Bad request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not found',
            500 => 'Internal server error',
            502 => 'Bad gateway',
            503 => 'Service unavailable',];

        try {
            if ($code < 200 || $code > 204) {
                throw new Exception($errors[$code] ?? 'Undefined error', $code);
            }
        } catch
        (Exception $e) {
            die($e->getMessage());
        }
        $response = json_decode($out, true);
        return $this->saveToken($response);
    }

    public function createApiClient(): AmoCRMApiClient
    {
        if (!file_exists(TOKEN_FILE)) $this->askToken();
        $tokens=$this->getToken();

        if (!isset($tokens)
            || !isset($tokens["access_token"])
            || !isset($tokens["refresh_token"])
            || !isset($tokens["expires"])
        ) die("Tokens is empty");

        $accessToken = new AccessToken([
            'access_token' => $tokens["access_token"],
            'refresh_token' => $tokens["refresh_token"],
            'expires' => $tokens["expires"],
            'baseDomain' => BASE_DOMAIN,
        ]);
        $apiClient = new AmoCRMApiClient($this->data["client_id"], $this->data["client_secret"], $this->data["redirect_uri"]);
        $apiClient->setAccessToken($accessToken)
            ->setAccountBaseDomain($accessToken->getValues()['baseDomain'])
            ->onAccessTokenRefresh(
                function (AccessTokenInterface $accessToken, string $baseDomain) {
                    saveToken(
                        [
                            'accessToken' => $accessToken->getToken(),
                            'refreshToken' => $accessToken->getRefreshToken(),
                            'expires' => $accessToken->getExpires(),
                            'baseDomain' => $baseDomain,
                        ]
                    );
                }
            );
        return $apiClient;
    }

    public function task1($apiClient, $encount){
        if (empty($encount)) die("Missing entities count!");

        for ($i=1;$i<=$encount;$i++){
            try {
                $company = $this->createCompany($i, $apiClient);
                $contact=$this->createContact($i,  $company,$apiClient);
                $this->createCustomer($i, $contact, $apiClient);
                $this->createLead($i, $company, $contact,  $apiClient);
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }
    }

    public function task2($apiClient)
    {
        $customFieldsCollection = new CustomFieldsCollection();
        $fec=(new CustomFieldEnumsCollection());
        for ($i=1; $i<=10;$i++){
            $fec->add(
                (new EnumModel())
                    ->setValue('Значение '.$i)
                    ->setSort(10+$i)
            );
        }

         $cf = new MultiselectCustomFieldModel();
         $cf
             ->setName('Поле Список X')
             ->setSort(35)
             ->setCode(FIELD_CODE)
             ->setEnums($fec);

         $customFieldsCollection->add($cf);

         $customFieldsService = $apiClient->customFields(EntityTypesInterface::CONTACTS);
         try {
             $customFieldsService->add($customFieldsCollection);
         } catch (AmoCRMApiException $e) {
             die($e->getMessage());
         }

    }

    private function getMSValues():array{
        $c=rand(1,10);
        $valuesSet=array();
        for ($i=1;$i<=$c;$i++){
            do{
                $value=rand(0,9);
            }while(in_array($value,$valuesSet));
            $valuesSet[]=$value;
        }
        return $valuesSet;
    }

    private function createCompany($indx, $apiClient): CompanyModel
    {
        $company = new CompanyModel();
        $company->setName('Company '.$indx);
        $companiesCollection = new CompaniesCollection();
        $companiesCollection->add($company);
        try {
            $apiClient->companies()->add($companiesCollection);
        } catch (AmoCRMApiException $e) {
            die($e->getMessage());
        }
        return $company;
    }

    private function createContact($indx,  $company,  $apiClient){
        $contact = new ContactModel();
        $contact->setName('Contact '.$indx);
        try {
            $contactModel = $apiClient->contacts()->addOne($contact);
        } catch (AmoCRMApiException $e) {
            die($e->getMessage());
        }
        $links = new LinksCollection();
        $links->add($company);
        try {
            $apiClient->contacts()->link($contactModel, $links);
        } catch (AmoCRMApiException $e) {
            die($e->getMessage());
        }
        return $contactModel;
    }

    private function createLead($indx, $company, $contact,  $apiClient): void
    {
        $leadsService = $apiClient->leads();
        $lead = new LeadModel();
        $lead->setName('Название сделки '.$indx)
            ->setPrice(50*$indx)
            ->setContacts((
                           new ContactsCollection())
                           ->add($contact))
            ->setCompany(
                $company
            )
        ;
        $leadsCollection = new LeadsCollection();
        $leadsCollection->add($lead);
        try {
            $leadsService->add($leadsCollection);
        } catch (AmoCRMApiException $e) {
            die($e->getMessage());
        }
    }

    private function createCustomer($indx, $contact, $apiClient): void
    {
        $customersService = $apiClient->customers();
        $customer = new CustomerModel();
        $customer->setName('Customer '.$indx);
        $customer->setNextDate(10);
        try {
            $customer = $customersService->addOne($customer);
        } catch (AmoCRMApiException $e) {
            die($e->getMessage());
        }

        $contact->setIsMain(false);
        $links = new LinksCollection();
        $links->add($contact);
        try {
            $customersService->link($customer, $links);
        } catch (AmoCRMApiException $e) {
            die($e->getMessage());
        }
    }

    function saveToken($accessToken): array
    {
        if (
            isset($accessToken)
            && isset($accessToken['access_token'])
            && isset($accessToken['refresh_token'])
            && isset($accessToken['expires_in'])
        ) {
            $data = [
                'access_token' => $accessToken['access_token'],
                'expires' => $accessToken['expires_in'],
                'refresh_token' => $accessToken['refresh_token'],
                'baseDomain' => BASE_DOMAIN,
            ];
            file_put_contents(TOKEN_FILE, json_encode($data));
        } else {
            exit('Invalid access token ' . var_export($accessToken, true));
        }
        return $data;
    }

    function getToken()
    {
        $accessToken = json_decode(file_get_contents(TOKEN_FILE), true);
        if (
            isset($accessToken)
            && isset($accessToken['access_token'])
            && isset($accessToken['refresh_token'])
            && isset($accessToken['expires'])
            && isset($accessToken['baseDomain'])
        ) {
            return $accessToken;
        } else {
            exit('Invalid access token ' . var_export($accessToken, true));
        }
    }

    public function task2_1(AmoCRMApiClient $apiClient): void
    {
        $customFieldsService = $apiClient->customFields(EntityTypesInterface::CONTACTS);
        try {
            if (!empty($customFieldsService)) {
                $result = $customFieldsService->get();
            }
        } catch (AmoCRMApiException $e) {
            die($e->getMessage());
        }

        if (isset($result)) {
            $msTemplate = $result->getBy('code', FIELD_CODE);
        }

        if (!empty($msTemplate)) {
            $enums = $msTemplate->getEnums();
        }

        try {
            $contactsService = $apiClient->contacts();
            $contactsCollection = $contactsService->get();

            foreach ($contactsCollection as $contact) {
                $customFields = $contact->getCustomFieldsValues();

                if (empty($customFields))
                    $customFields = new CustomFieldsValuesCollection();

                $customFieldValuesModel = new MultiselectCustomFieldValuesModel();

                if (!empty($msTemplate)) {
                    $customFieldValuesModel->setFieldId($msTemplate->getId())
                        ->setFieldCode($msTemplate->getCode());
                }

                $valuesIndexes = $this->getMSValues();
                $valuesCollection = (new MultiselectCustomFieldValueCollection());

                foreach ($valuesIndexes as $value) {
                    if (!empty($enums)) {
                        $valuesCollection->add((new BaseEnumCustomFieldValueModel())->setValue($enums[$value]->getValue())->setEnumId($enums[$value]->getId()));
                    }
                }

                $customFieldValuesModel->setValues($valuesCollection);

                $customFields->add($customFieldValuesModel);

                $contact->setCustomFieldsValues($customFields);

                $contactsService->updateOne($contact);
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    private function getEntityMethodById($entityId): ?string{
        switch ($entityId) {
            case 'contact':
                return 'contacts';
            case 'lead':
                return 'leads';
            case 'company':
                return 'companies';
            case 'customer':
                return 'customers';
        }
            return null;
    }

    public function task3(AmoCRMApiClient $apiClient, $entityId, $entityFieldValue, $recordId){
        if (empty($apiClient)) die("Exception! APIClient empty!");
        if (empty($entityId)) die("Exception! entityId empty!");
        if (empty($entityFieldValue)) die("Exception! entityFieldValue empty!");
        if (empty($recordId)) die("Exception! recordId empty!");
        $entityId=strtolower($entityId);

        $callFunction=$this->getEntityMethodById($entityId);
        if (!method_exists($apiClient,$callFunction)) die('Method $callFunction is not exists!');

        $entityInst=$apiClient->$callFunction()->getOne($recordId);
        if (empty($entityInst)) {
            die("Record with id=".$recordId." not found!");
        }

        $fields=$entityInst->getCustomFieldsValues();
        foreach($fields as $field){
            $fieldType=$field->getFieldType();
            if ('text'===$fieldType){
                $field->setValues(
                    (new BaseCustomFieldValueCollection())
                        ->add(
                            (new BaseCustomFieldValueModel())
                                ->setValue($entityFieldValue)
                        )
                );
                $apiClient->$callFunction()->updateOne($entityInst);
                break;
            }
        }
    }

    public function task4(AmoCRMApiClient $apiClient, $entityId){
        if (empty($apiClient)) die("Exception! APIClient empty!");
        if (empty($entityId)) die("Exception! entityId empty!");

        $notesCollection = new NotesCollection();
        $serviceMessageNote = new ServiceMessageNote();
        $serviceMessageNote->setEntityId($entityId)
            ->setText('Обычное примечание')
            ->setService('Api Library')
            ->setCreatedBy(0);

       $callInNote = new CallInNote();

        $callInNote->setEntityId($entityId)
            ->setPhone('+798765432')
            ->setCallStatus(CallInterface::CALL_STATUS_SUCCESS_RECALL)
            ->setCallResult('Разговор состоялся')
            ->setDuration(100)
            ->setUniq(Uuid::uuid4())
            ->setSource('integration name')
            ->setLink('https://dindon.msc/test.mp3');
        $notesCollection->add($serviceMessageNote);
        $notesCollection->add($callInNote);
        try {
            $leadNotesService = $apiClient->notes(EntityTypesInterface::LEADS);
            $leadNotesService->add($notesCollection);
        } catch (AmoCRMApiException $e) {
            die($e);
        }
    }

    public function task5(AmoCRMApiClient $apiClient, $userId, $taskId, $entityId){
        if (empty($apiClient)) die("Exception! APIClient empty!");
        if (empty($entityId)) die("Exception! entityId empty!");

        $tasksCollection = new TasksCollection();
        $task = new TaskModel();
        $task->setTaskTypeId(TaskModel::TASK_TYPE_ID_FOLLOW_UP)
            ->setText('The new task')
            ->setCompleteTill(mktime(11, 10, 0, 12, 1, 2021))
            ->setEntityType(EntityTypesInterface::LEADS)
            ->setEntityId($entityId)
            ->setResponsibleUserId($userId)
            ->setId($taskId);
        $tasksCollection->add($task);

        try {
            $tasksService = $apiClient->tasks();
            $tasksService->add($tasksCollection);
        } catch (AmoCRMApiException $e) {
            die($e->getMessage());
        }
    }

    public function getApiClient(): AmoCRMApiClient
    {
        try {
            $apiClient = $this->createApiClient();
        } catch (Exception $e) {
            exit("Ошибка инициализации клиента:" . $e->getMessage());
        }
        return $apiClient;
    }

}

// Имена и значения массива _POST, содержащего параметры скрипта

// task 1 vars
$_POST["encount"]=2;

// task 3 vars
$_POST["entityId"]='contact';
$_POST["entityFieldValue"]='Value 1';
$_POST["recordId"]=7251849;

// task 4 vars
$_POST["noteEntityId"]=4439661;

// task 5 vars
$_POST["taskEntityId"]=4439661;
$_POST["completeById"]=4439662;
$_POST["userId"]=7218337;


$instanceClt=new ApiExample();
$apiClient=$instanceClt->getApiClient();
if (empty($apiClient)) die("API client error!");

$instanceClt->task1($apiClient,$_POST["encount"]);
$instanceClt->task2($apiClient);
$instanceClt->task2_1($apiClient);
$instanceClt->task3($apiClient, $_POST["entityId"], $_POST["entityFieldValue"], $_POST["recordId"]);
$instanceClt->task4($apiClient, $_POST["noteEntityId"]);
$instanceClt->task5($apiClient, $_POST["userId"],$_POST["completeById"], $_POST["taskEntityId"]);

// Пункт 3. Установить значение доп. поля типа текст в указанный элемент сущности (сущность указывается по айди через интерфейс или консоль).
// Не совсем понятно, что имеется ввиду под "элемент сущности". Буду считать, что это экземпляр сущности, с заданным Id.
// "Поле типа текст у каждой сущности должно быть только одно." Т.к. не задано, что делать, если таких полей несколько, то принимаю:
// - работать с первым текстовым полем в коллекции полей
// - если текстовых полей у заданной сущности нет, то создавать не нужно, исключение не генерировать.


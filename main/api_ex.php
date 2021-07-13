<?php
include_once 'vendor/autoload.php';

\AmoCRM\Client\AmoCRMApiClientFactory
/** Соберем данные для запроса */
$data = [
    'client_id' => '8a3895df-077a-438d-bb31-6e27c69a409d',
    'client_secret' => 'agaxADBIKUJCMSe6F2JHSfvJl4aCoTPH7HssUEEDXKcPXJt2iLoSCAMUPmlYPID0',
    'grant_type' => 'authorization_code',
    'code' => 'def50200d9e93018eaf99dfb57aee8aa01db282fe2529ebfd1882e36f1834c854b41852248d66996b4a0daae375866af27c863b6b80d31698accf13872c860b6c11a698e9074e70e04968b7c08240d7d54738e07d40fa3eb46ca7b8419ee0a2f249735e2d84b103d4ba8a59858f9d5ad5a815a8e8f9fd73bbc503c9f9614f3f019232604c81b27c8afb0f7a820cd141d5e7bef722302014e9723bbd051f148a302e6a6f3e73c19ea3ee6672b199d1d28b42f5658d436e48a8031ae192779a9316f1e06e4396f83460305693de4aff9755b396a42fe507edd11cadaad84b6cfa43fa67346c8601794012d468296f41fa0337e22f935bfa8de3fab69ede0d2b740b0628dd2045c10623297b21159ae715606988c06dd13769a41759495469459e53a6a09522d72594fb374fa87ca9bc85574bddeac8f7c7c5f08bb3bf29ee11a24bb7c0deb3d88129adc21c9c3096850f8a76d1e0129f00468382c9d1622a2866731d162f30502c07faef86a3ad3e982fdab79acc912ddd190d68f3d492172e9209c3393fcb80b1068d4a2f9cf5288e9c63cb6eb5da83c88d617ce7d6001179b0285ab11cba8d6b1febafa53f5690f92ca125d36d8ba968927b6e1602e',
    'redirect_uri' => 'https://google.com',
];

$apiClient = new \AmoCRM\Client\AmoCRMApiClient($data["client_id"], $data["client_secret"], $data["redirect_uri"]);

try {
    $access_token = $apiClient->getOAuthClient()->getAccessTokenByCode($data["code"]);
} catch (\AmoCRM\Exceptions\AmoCRMoAuthApiException $e) {
    echo "Error code=".$e->getMessage();
}
$apiClient->setAccessToken($access_token);
$c=$apiClient->companies()->get();
print("<br>c=".$c);



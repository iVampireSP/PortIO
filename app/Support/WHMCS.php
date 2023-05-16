<?php

namespace App\Support;

class WHMCS
{
    private string $url;
    private string $username;
    private string $password;
    private string $api_token;
    public int|string $platform;
    public array $payments = [];

    private string $config_key = '';
    private array $config = [];

    public function __construct(int|string $whmcs_id)
    {
        $this->config_key = 'whmcs.' . $whmcs_id;
        $whmcs = config($this->config_key);
        $this->config = $whmcs;

        $this->platform = $whmcs_id;

        if (is_null($whmcs)) {
            throw new \Exception('WHMCS config not found');
        }

        $this->url = $whmcs['url'];
        $this->username = $whmcs['username'] ?? '';
        $this->password = md5($whmcs['password'] ?? '');
        $this->api_token = $whmcs['api_token'];
        $this->payments = $whmcs['payments'] ?? [];
    }

    public function hasPayment(string $payment): bool
    {
        return in_array($payment, array_keys($this->payments));
    }

    public function getPayments(): array
    {
        $results = [];

        // 获取 config 的所有的 key
        $payments = array_keys($this->config['payments'] ?? []);

        foreach ($payments as $payment) {
            $results[] = [
                'name' => $payment,
                'title' => $this->config['payments'][$payment]
            ];
        }

        return $results;
    }

    private function request($action, $params = []): ?array
    {
        $params = array_merge([
            'action' => $action,
            'username' => $this->username,
            'password' => md5($this->password),
            'responsetype' => 'json',
        ], $params);


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url . '/includes/api.php');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            http_build_query($params)
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($response, true);

        throw_if(
            is_null($json),
            new \Exception('WHMCS response is not valid JSON')
        );

        throw_if(
            isset($json['result']) && $json['result'] !== 'success',
            new \Exception($json['message'])
        );

        return $json;
    }

    public function api($action, $params = []): ?array
    {
        $params = array_merge([
            'api_token' => $this->api_token
        ], $params);


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url . '/modules/addons/PortIOInvoice/api/' . $action . '.php');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            http_build_query($params)
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($response, true);

        throw_if(
            is_null($json),
            new \Exception('WHMCS response is not valid JSON')
        );

        throw_if(
            isset($json['status']) && $json['status'] !== true,
            new \Exception($json['message'] ?? '未知错误')
        );

        return $json;
    }

    public function api_addTraffic(string $email, string $payment, int|float $traffic, int|float|string $price)
    {
        return $this->api('addTraffic', [
            'email' => $email,
            'traffic' => $traffic,
            'price' => $price,
            'platform' => $this->platform,
            'payment' => $payment
        ]);
    }

    public function api_openTicket(string $email, string $title, string $content)
    {
        return $this->api('openTicket', [
            'email' => $email,
            'title' => $title,
            'content' => $content,
            'department_id' => $this->config['department_id'] ?? 1,
        ]);
    }

}

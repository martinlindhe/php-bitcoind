<?php namespace Bitcoind;

/**
 * Client for the bitcoind JSON-RPC API
 */
class JsonRpcClient
{
    private $host = '127.0.0.1';
    private $port = 8332;
    private $username = 'bitcoinrpc';
    private $password;

    private $requestCounter = 0;

    public function setHost($s)
    {
        $this->host = $s;
    }

    public function setPort($n)
    {
        $this->port = $n;
    }

    public function setUsername($s)
    {
        $this->username = $s;
    }

    public function setPassword($s)
    {
        $this->password = $s;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $data encoded POST data
     * @return JsonRpcResponse
     * @throws \AuthenticationFailureException
     * @throws \ConnectionErrorException
     * @throws \ConnectionRefusedException
     * @throws \MethodNotFoundException
     * @throws \NotOkayException
     */
    private function postRequest($data)
    {
        $url = 'http://'.$this->username.':'.$this->password.'@'.$this->host.':'.$this->port.'/';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $output = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($curl_errno == 7) {
            throw new \ConnectionRefusedException($curl_error);
        } else if ($curl_errno > 0) {
            throw new \ConnectionErrorException($curl_error);
        }

        if ($httpCode == 401) {
            throw new \AuthenticationFailureException();
        } else if ($httpCode == 404) {
            throw new \MethodNotFoundException();
        } else if ($httpCode != 200) {
            throw new \NotOkayException();
        }

        $tmp = json_decode($output);

        $res = new JsonRpcResponse();
        $res->httpCode = $httpCode;
        $res->result = $tmp->result;
        $res->error = $tmp->error;
        $res->id = $tmp->id;
        return $res;
    }

    public function __call($methodName, $params = [])
    {
        return $this->request(strtolower($methodName), $params);
    }

    public function request($methodName, $params = [])
    {
        $this->requestCounter++;

        $params = [
            "method" => $methodName,
            "params" => $params,
            "id" => $this->requestCounter,
        ];

        return $this->postRequest(json_encode($params));
    }

    public function isValidAddress($address)
    {
        $res = $this->validateAddress($address);
        return $res->result->isvalid;
    }
}

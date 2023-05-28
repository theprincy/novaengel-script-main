<?php

namespace Mc\Novaengel;
ini_set('memory_limit', '-1');
class Api {
    function __construct(string $url, string $user, string $pass) {
        $this->baseUrl = $url;
        $this->user = $user;
        $this->pass = $pass;
        $this->debug = false;
    }

    public function get(
        string $operation
    ) {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "{$this->baseUrl}/api/$operation",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => [
                "Accept: application/json",
                "Content-Type: application/json",
                "application/x-www-form-urlencoded"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new \Exception("cURL Error #: $err");
        }

        $response = json_decode($response, true);

        if($this->debug){
            echo "GET RESPONSE: \n";
            print_r($response);
            // exit(1);
        }

        return $response;
    }

    public function post(
        string $operation,
        array $postData = []
    ) {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "{$this->baseUrl}/api/$operation",
            CURLOPT_RETURNTRANSFER => true,
            // CURLOPT_ENCODING => "",
            // CURLOPT_MAXREDIRS => 10,
            // CURLOPT_TIMEOUT => 30,
            // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode( $postData ),
            CURLOPT_HTTPHEADER => [
                "Accept: application/json",
                "Content-Type: application/json",
                // "application/x-www-form-urlencoded"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        // Then, after your curl_exec call:
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        print_r($curl);

        if ($err) {
            throw new \Exception("cURL Error #: $err");
        }

        $response = json_decode($response, true);

        if($this->debug){
            echo "POST RESPONSE: \n";
            print_r($response);
            // exit(1);
        }

        return $response;
    }


    function login() {
        $response = $this->post('login', [
            'user' => $this->user,
            'password' => $this->pass,
        ]);

        $this->token = $response['Token'];

        if($this->token === null) {
            throw new \Exception("Novaengel Login Error");
        }

        return $response;
    }

    function productsList() {
        return $this->get('/products/availables/' . $this->token . '/IT');
    }

    function productImage($productId) {
        return $this->get('/products/image/' . $this->token . '/' . $productId);
    }
}
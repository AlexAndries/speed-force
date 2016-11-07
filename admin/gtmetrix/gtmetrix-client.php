<?php

/**
 * Project: speed-force
 * User: Alex Andries <alexandru.andries@outlook.com>
 */
class GTmetrixClient {

  private $endpoint = 'https://gtmetrix.com/api/0.1';

  private $username;

  private $apiKey;

  private $websiteUser;

  private $websitePassword;

  public function __construct($username, $apiKey, $websiteUser = '', $websitePassword = '') {
    $this->username = $username;
    $this->apiKey = $apiKey;
    $this->websiteUser = $websiteUser;
    $this->websitePassword = $websitePassword;
  }

  protected function request($url, $data = array(), $json = true) {

    if ($this->websiteUser) {
      $data['login-user'] = $this->websiteUser;
    }

    if ($this->websitePassword) {
      $data['login-pass'] = $this->websitePassword;
    }

    $ch = curl_init($this->endpoint . $url);
    if (!empty($data)) {
      curl_setopt($ch, CURLOPT_POST, count($data));
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    }
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->apiKey);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if (!SPEED_FORCE_USE_SSL) {
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    }
    $result = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErrNo = curl_errno($ch);
    $curlError = curl_error($ch);
    curl_close($ch);

    if (!\preg_match('/^(2|3)/', $statusCode)) {
      if ($statusCode == 0) {
        throw new Exception('cURL error ' . $curlErrNo . ': ' . $curlError);
      }
      throw new Exception('API error ' . $statusCode . ': ' . $result);
    }

    if($json){
      $data = json_decode($result, true);
      if (json_last_error()) {
        throw new Exception('Invalid JSON received: ' . json_last_error_msg());
      }
    }else{
      $data = $result;
    }

    return $data;
  }
}
<?php

/**
 * Project: SpeedForce
 * User: Alex Andries <alexandru.andries@outlook.com>
 * @documentation: https://sites.google.com/a/webpagetest.org/docs/advanced-features/webpagetest-restful-apis
 */
class WebPageTestOrgAuth {

  const WEB_PAGE_TEST_ORG_URL = 'http://www.webpagetest.org/runtest.php';

  const WEB_PAGE_TEST_ORG_JSON_URL = 'http://www.webpagetest.org/jsonResult.php?test={test_id}';

  const WEB_PAGE_TEST_ORG_CSV_URL = 'http://www.webpagetest.org/result/{test_id}/requests.csv';

  const WEB_PAGE_TEST_ORG_TEST_URL = 'http://www.webpagetest.org/result/{test_id}/';

  private $api;

  private $login;

  private $format;

  private $client;

  protected $reportId;

  private $requestBody = array();

  public function __construct($api, $login = array(), $format = 'json') {
    $this->api = $api;
    $this->login = $login;
    $this->format = $format;
    $options = array();
    if (!SPEED_FORCE_USE_SSL) {
      $options = array(
        'curl'   => array(CURLOPT_SSL_VERIFYPEER => false),
        'verify' => false
      );
    }
    $this->client = new GuzzleHttp\Client($options);
  }

  protected function requestTest($url) {
    $this->buildParameters($url);

    $pageTest = $this->client->request('GET', self::WEB_PAGE_TEST_ORG_URL, $this->requestBody);

    if ($pageTest->getStatusCode() !== 200) {
      throw new Exception('Api Error'); //todo: update error messages
    }
    $data = json_decode($pageTest->getBody()
                                 ->getContents(), true);
    if (is_null($data)) {
      throw new Exception('Api Error'); //todo: update error messages
    }

    if ($data['statusCode'] !== 200) {
      throw new Exception($data['statusText']); //todo: update error messages
    }

    $this->reportId = $data['data']['testId'];
  }

  private function buildParameters($url) {
    $this->requestBody = array(
      'query' => array(
        'url' => $url,
        'k'   => $this->api,
        'f'   => $this->format
      )
    );

    if (!empty($this->login)) {
      $this->requestBody['query']['login'] = $this->login['user'];
      $this->requestBody['query']['password'] = $this->login['password'];
      $this->requestBody['query']['authType'] = $this->login['authType'];
    }
  }

  protected function getTestResults() {
    $report = $this->client->get(str_replace('{test_id}', $this->reportId, self::WEB_PAGE_TEST_ORG_JSON_URL));

    if ($report->getStatusCode() !== 200) {
      throw new Exception('Api Error'); //todo: update error messages
    }
    $data = json_decode($report->getBody()
                               ->getContents(), true);
    if (is_null($data)) {
      throw new Exception('Api Error'); //todo: update error messages
    }

    if ($data['statusCode'] !== 200 && $data['statusCode'] !== 100 && $data['statusCode'] !== 101) {
      var_dump($data['statusCode']);
      throw new Exception($data['statusText']); //todo: update error messages
    }

    return $data;
  }
}
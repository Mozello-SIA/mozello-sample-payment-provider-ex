<?php

final class AppsAPI
{
    # Fields #

    private $authCode = '';
    private $apiKey = '';

    # Constructor #

    public function __construct($apiKey, $authCode)
    {
        $this->authCode = $authCode;
        $this->apiKey = $apiKey;
    }

    # Methods #

    public function setSnippet($snippet, $languageCode = null)
    {
        $parameters['language'] = $languageCode;
        $parameters['snippet'] = $snippet;

        return $this->request('set_snippet', 'POST', json_encode($parameters));
    }

    public function getSnippetDefault()
    {
        return $this->request('get_snippet');
    }

    public function getSnippetByLanguage($languageCode)
    {
        return $this->request('get_snippet/' . $languageCode);
    }

    public function setHeadSnippet($snippet, $languageCode = null)
    {
        $parameters['language'] = $languageCode;
        $parameters['snippet'] = $snippet;

        return $this->request('set_head_snippet', 'POST', json_encode($parameters));
    }

    public function getHeadSnippetDefault()
    {
        return $this->request('get_head_snippet');
    }

    public function getHeadSnippetByLanguage($languageCode)
    {
        return $this->request('get_head_snippet/' . $languageCode);
    }

    public function setData($data)
    {
        $parameters['data'] = $data;
        return $this->request('set_data', 'POST', json_encode($parameters));
    }

    public function getData()
    {
        return $this->request('get_data');
    }

    public function installed()
    {
        return $this->request('installed', 'POST');
    }

    public function delete()
    {
        return $this->request('delete', 'POST');
    }

    public function getAppInfo($approved = false)
    {
        return $this->request('app_info' . ($approved ? '?approved=1' : ''));
    }

    public function getUser()
    {
        return $this->request('user');
    }

    public function getWebsite()
    {
        return $this->request('website');
    }

    public function getLanguages()
    {
        return $this->request('languages');
    }

    private function request($action, $method = 'GET', $payload = '')
    {
        $endpoint = 'https://api.mozello.com/v1/apps/' . trim($action, '/') . '/';
        $endpoint .= '?authcode=' . $this->authCode;

        $curl = curl_init($endpoint);

        curl_setopt_array($curl, [
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: ApiKey ' . $this->apiKey
            ],
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_HEADER => 0
        ]);

        $response = curl_exec($curl);
        $responseInfo = curl_getinfo($curl);
        $http_status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);

        $this->last_api_response = [ 'status' => $http_status, 'response' => $response ];
        return $response;
    }

    # End #
}
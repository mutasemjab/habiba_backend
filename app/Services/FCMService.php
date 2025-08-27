<?php

namespace App\Services;

use Google_Client;
use GuzzleHttp\Client;

class FCMService
{
    private $httpClient;
    private $firebaseUrl;

    public function __construct()
    {
        // Set up Google Client
        $client = new Google_Client();
        $client->setAuthConfig(public_path('js/habiba-app-43465c5ecb78.json'));
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        // Get an authorized Guzzle HTTP client
        $this->httpClient = new Client([
            'base_uri' => 'https://fcm.googleapis.com/',
            'headers' => [
                'Authorization' => 'Bearer ' . $client->fetchAccessTokenWithAssertion()['access_token'],
                'Content-Type' => 'application/json',
            ],
        ]);

        $this->firebaseUrl = 'v1/projects/' . $this->getProjectId() . '/messages:send';
    }

    private function getProjectId()
    {
        $credentials = json_decode(file_get_contents(public_path('js/habiba-app-43465c5ecb78.json')), true);
        return $credentials['project_id'];
    }

    public function sendPushNotification($deviceToken, $title, $body, $data = [])
    {
        $message = [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => $data,
            ],
        ];

        try {
            $response = $this->httpClient->post($this->firebaseUrl, [
                'json' => $message,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            throw new \Exception('Failed to send FCM message: ' . $e->getMessage());
        }
    }
}
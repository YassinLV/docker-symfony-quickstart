<?php


namespace App\Services;


use App\Entity\User;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Class NumberService
 */
class NumberService
{
    CONST OUTPUT_INDEX = 'output';
    CONST IS_VALID_OUTPUT_INDEX = 'isValid';

    private $params;
    private $client;

    public function __construct(
        ContainerBagInterface $params,
        HttpClientInterface $client
    )
    {
        $this->params = $params;
        $this->client = $client;
    }

    public function callApi(User $user)
    {
        $url = $this->params->get('url_api_number');
        $login = $this->params->get('login_api_number');
        $password = $this->params->get('password_api_number');
        $jsonData = json_encode([$user->jsonSerialize()]);

        $request = $this->client->request(
            Request::METHOD_POST, $url, [
            'auth_basic' => [$login, $password],
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'body' => $jsonData
        ]);

        $this->handleRequest($request);
    }

    private function handleRequest(ResponseInterface $response)
    {
        if (Response::HTTP_OK !== $response->getStatusCode()) {
            throw new \Exception('There is an error in the request.');
        }

        $data = json_decode($response->getContent(), true);

        dump($data[0]);

        if (empty($data) || !array_key_exists(self::OUTPUT_INDEX, $data[0])) {
            throw new \Exception('The data form request could not be handle.');
        }

        if (!$data[0][self::OUTPUT_INDEX][self::IS_VALID_OUTPUT_INDEX]) {
            throw new \Exception('The number format is invalid.');
        }

        dump('toto');
        die;
    }

    private function isValidFormat()
    {
        if(false) {
            return false;
        }

        return true;
    }
}
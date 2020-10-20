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
    CONST INTERNATIONAL_NUMBER_OUTPUT_INDEX = 'international';

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

    /**
     * get International Number from the ApiCall
     */
    public function getInternationNumberFromApi(User $user)
    {
        return $this->callApi($user->jsonSerialize());
    }

    /**
     * call external api to validate phone number format
     */
    private function callApi(array $data)
    {
        $url = $this->params->get('url_api_number');
        $login = $this->params->get('login_api_number');
        $password = $this->params->get('password_api_number');
        $jsonData = json_encode([$data]);

        $request = $this->client->request(
            Request::METHOD_POST, $url, [
            'auth_basic' => [$login, $password],
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'body' => $jsonData
        ]);

        return $this->handleRequest($request);
    }

    /**
     * Make many check to get the phone number format
     */
    private function handleRequest(ResponseInterface $response)
    {
        if (Response::HTTP_OK !== $response->getStatusCode()) {
            throw new \Exception('There is an error in the request.');
        }

        $data = json_decode($response->getContent(), true);

        if (empty($data) || !array_key_exists(self::OUTPUT_INDEX, $data[0])) {
            throw new \Exception('The data form request could not be handle.');
        }

        if (!$data[0][self::OUTPUT_INDEX][self::IS_VALID_OUTPUT_INDEX]) {
            throw new \Exception('The number format is invalid.');
        }

        if (!isset($data[0][self::OUTPUT_INDEX][self::INTERNATIONAL_NUMBER_OUTPUT_INDEX])) {
            throw new \Exception('The international number is not found.');
        }

        return $data[0][self::OUTPUT_INDEX][self::INTERNATIONAL_NUMBER_OUTPUT_INDEX];
    }
}
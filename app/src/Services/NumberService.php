<?php


namespace App\Services;


use App\Entity\User;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Class NumberService
 */
class NumberService
{
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

        $request = $this->client->request(
            'POST', $url, [
                'auth_basic' => [$login, $password],
                'body' => [json_encode($user->jsonSerialize())]
            ]
        );

        $this->handleRequest($request);
    }

    public function handleRequest(ResponseInterface $response)
    {
        if (!200 !== $response->getStatusCode()) {
            throw new \Exception('There is an error in the request');
        }

        $data = json_decode($response->getContent());
        if (empty($data) || $data['output']){
            return null;
        }


    }
}
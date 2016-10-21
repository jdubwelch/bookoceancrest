<?php namespace OceanCrest\Controllers;


use Mlaphp\Request;
use Mlaphp\Response;

class NotFound
{
    private $request;
    private $response;

    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function __invoke()
    {
        $url_path = parse_url(
            $this->request->server['REQUEST_URI'],
            PHP_URL_PATH
        );

        $this->response->setView('not-found.php');
        $this->response->setVars(array(
            'url_path' => $url_path
        ));

        return $this->response;
    }

}
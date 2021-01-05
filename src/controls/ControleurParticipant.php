<?php


namespace mywishlist\controls;


use mywishlist\vue\VueAccueil;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ControleurParticipant
{
    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function deconnexion(Request $rq, Response $rs, $args) : Response {
        session_destroy();
        $_SESSION = [];
        $vue = new VueAccueil( [], $this->container);
        $rs->getBody()->write($vue->render((8)));
        return $rs;

    }
}
<?php
namespace Codzo\Middleware\UnderMaintenance;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Codzo\Config\Config;

class UnderMaintenanceMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $config = new Config();
        $under_maintenance = $config->get(
            'undermaintenance.status',
            false
        );

        if(!$under_maintenance) {
            return $handler->handle($request);
        }

        $response = new Response();
        $redirect_url = $config->get("undermaintenance.redirect.url", '/error');
        return $response
            ->withHeader('Location', $redirect_url)
            ->withStatus(302);
    }
}

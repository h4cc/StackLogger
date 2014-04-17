<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require __DIR__ . '/vendor/autoload.php';

$app = new Silex\Application();

$app->get('/', function() {
      return new Response('content', 200);
  });

$stack = (new Stack\Builder())
  ->push('Silpion\Stack\Logger', array('logger' => new \Monolog\Logger('example_logger')))
;

$app = $stack->resolve($app);

$request = Request::create('/');
$response = $app->handle($request);
$app->terminate($request, $response);

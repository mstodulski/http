<?php
/**
 * This file is part of the EasyCore package.
 *
 * (c) Marcin Stodulski <marcin.stodulski@devsprint.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mstodulski\http;

use mstodulski\router\RouteException;
use mstodulski\router\Router;

class Request {

    private ?ParametersCollection $headers;
    private ?ParametersCollection $attributes;
    private ?ParametersCollection $parameters;
    private ?ParametersCollection $request;
    private ?ParametersCollection $server;
    private ?ParametersCollection $session;
    private ?ParametersCollection $cookies;
    private ?ParametersCollection $files;

    /** @throws RouteException */
    public function __construct(array $routes, Router $router = null)
    {
        session_start();

        $headers = apache_request_headers();
        $this->headers = new ParametersCollection();
        foreach ($headers as $name => $value) {
            $this->headers->addParameter($name, $value);
        }

        if ($router === null) {
            $router = new Router();
            $router->defineRoutes($routes);
        }

        $niceUrl = $this->getNiceUrl();
        $niceUrl = explode('?', $niceUrl);
        $niceUrl = $niceUrl[0];

        $route = $router->getRouteByLink($niceUrl, $_SERVER['REQUEST_METHOD']);

        $this->attributes = new ParametersCollection();
        foreach ($_GET as $name => $value)
        {
            $this->attributes->addParameter($name, $value);
        }

        $this->attributes->addParameter('schema', $route['linkSchema']);
        $this->attributes->addParameter('action', $route['options']['controller']);
        $this->attributes->addParameter('ajaxCall', $this->isAjaxCall());
        $this->attributes->addParameter('method', $this->getRequestMethod());
        $this->attributes->addParameter('ip', $this->getRequestIpNumber());

        $this->parameters = new ParametersCollection();
        foreach ($_GET as $name => $value)
        {
            $this->parameters->addParameter($name, $value);
        }

        foreach ($route['parameters'] as $name => $value) {
            $this->parameters->addParameter($name, $value);
        }

        $this->request = new ParametersCollection();
        foreach ($_POST as $name => $value)
        {
            $this->request->addParameter($name, $value);
        }

        $this->server = new ParametersCollection();
        foreach ($_SERVER as $name => $value)
        {
            $this->server->addParameter($name, $value);
        }

        $this->session = new ParametersCollection();
        foreach ($_SESSION as $name => $value)
        {
            $this->session->addParameter($name, $value);
        }

        $this->cookies = new ParametersCollection();
        foreach ($_COOKIE as $name => $value)
        {
            $this->cookies->addParameter($name, $value);
        }

        $this->files = new ParametersCollection();
        foreach ($_FILES as $name => $value)
        {
            $this->files->addParameter($name, $value);
        }
    }

    public function getAttributes(): ?ParametersCollection
    {
        return $this->attributes;
    }

    public function getRequest(): ?ParametersCollection
    {
        return $this->request;
    }

    public function getParameters(): ?ParametersCollection
    {
        return $this->parameters;
    }

    public function getHeaders(): ?ParametersCollection
    {
        return $this->headers;
    }

    public function getServer(): ?ParametersCollection
    {
        return $this->server;
    }

    public function getSession(): ?ParametersCollection
    {
        return $this->session;
    }

    public function getCookies(): ?ParametersCollection
    {
        return $this->cookies;
    }

    public function getFiles(): ?ParametersCollection
    {
        return $this->files;
    }

    private function isAjaxCall(): bool
    {
        return
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    private function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    private function getRequestIpNumber()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    private function getRewriteBase() : string
    {
        return str_replace(pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_BASENAME), '', $_SERVER['SCRIPT_NAME']);
    }

    private function getNiceUrl()
    {
        $rewriteBase = $this->getRewriteBase();

        $executedFileName = basename($_SERVER['SCRIPT_NAME']);
        $niceUrl = $_SERVER['REQUEST_URI'];

        $pos = strpos($niceUrl, $rewriteBase);
        if ($pos !== false) {
            $niceUrl = substr_replace($niceUrl, '', $pos, strlen($rewriteBase));
        }

        $niceUrl = str_replace($executedFileName, '', $niceUrl);
        if ($niceUrl == '') {
            $niceUrl = '/';
        }

        return $niceUrl;
    }
}

<?php namespace SmallTeam\Dashboard\Routing;

use Illuminate\Routing\Router as BaseRouter;
use Illuminate\Routing\Route;
use SmallTeam\Dashboard\Entity\EntityInterface;

/**
 * Router
 *
 * @author Max Kovpak <max_kovpak@hotmail.com>
 * @url www.max-kovpak.com
 * @date 27.05.2015
 * */
class Router
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_ANY = 'ANY';

    /** @var BaseRouter */
    protected $router;

    /** @var string */
    protected $entity;

    /** @var string */
    protected $prefix;

    /** @var string */
    protected $entity_name;

    /** @var string */
    protected $controller_name;

    /** @var array */
    public static $methods = array('GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS', 'ANY');

    /**
     * Router constructor
     *
     * @param string $entity
     * @param string $controller_name
     * @param string $prefix
     * @param string $entity_name
     * */
    public function __construct($entity, $controller_name, $prefix, $entity_name)
    {
        $this->router = app()->router;
        $this->entity = $entity;
        $this->prefix = $prefix;
        $this->entity_name = $entity_name;
        $this->controller_name = $controller_name;
    }

    /**
     * Create router instance.
     *
     * @param string $entity
     * @param string $controller_name
     * @param string $prefix
     * @param string $entity_name
     *
     * @return self
     * */
    public static function create($entity, $controller_name, $prefix, $entity_name)
    {
        return new self($entity, $controller_name, $prefix, $entity_name);
    }

    /**
     * @param string $uri
     * @param string $action Current controller action
     * @return Route
     * */
    public function get($uri, $action)
    {
        return $this->addRoute(self::METHOD_GET, $uri, $action);
    }

    /**
     * @param string $uri
     * @param string $action Current controller action
     * @return Route
     * */
    public function post($uri, $action)
    {
        return $this->addRoute(self::METHOD_POST, $uri, $action);
    }

    /**
     * @param string $uri
     * @param string $action Current controller action
     * @return Route
     * */
    public function put($uri, $action)
    {
        return $this->addRoute(self::METHOD_PUT, $uri, $action);
    }

    /**
     * @param string $uri
     * @param string $action Current controller action
     * @return Route
     * */
    public function patch($uri, $action)
    {
        return $this->addRoute(self::METHOD_PATCH, $uri, $action);
    }

    /**
     * @param string $uri
     * @param string $action Current controller action
     * @return Route
     * */
    public function delete($uri, $action)
    {
        return $this->addRoute(self::METHOD_DELETE, $uri, $action);
    }

    /**
     * @param string $uri
     * @param string $action Current controller action
     * @return Route
     * */
    public function options($uri, $action)
    {
        return $this->addRoute(self::METHOD_OPTIONS, $uri, $action);
    }

    /**
     * @param string $uri
     * @param string $action Current controller action
     * @return Route
     * */
    public function any($uri, $action)
    {
        return $this->addRoute(self::METHOD_ANY, $uri, $action);
    }

    /**
     * Add route
     *
     * @param string $method
     * @param string $uri
     * @param string $action
     * @return Route
     * @throws \InvalidArgumentException
     * */
    protected function addRoute($method, $uri, $action)
    {
        if (!is_string($action)) {
            throw new \InvalidArgumentException('Action must be string "' . $action . '"');
        }

        if (!in_array($method, self::$methods)) {
            throw new \InvalidArgumentException('Invalid method "' . $method . '"');
        }

        if (strpos($action, '@') !== false) {
            $action_arr = explode('@', $action);
            $action = array_pop($action_arr);
        }

        $use = $this->controller_name . '@' . $action;

        $self = $this;
        $route = null;
        $this->router->group(['prefix' => $this->prefix, 'as' => '.' . $this->entity_name], function () use ($method, $uri, $use, $self, &$route) {
            $route = call_user_func([$this->router, strtolower($method)], $uri, $use);
        });

        return $route;
    }

}
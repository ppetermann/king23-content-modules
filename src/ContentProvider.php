<?php

namespace King23\ContentModules;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

class ContentProvider
{
    /**
     * @var ContentModule[]
     */
    protected $modules = [];

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var string[]
     */
    private $handlers;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $key
     * @param string $classname
     */
    public function registerModule(string $key, string $classname)
    {
        $this->handlers[$key] = $classname;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ContentProvider
     */
    public function withRequest(ServerRequestInterface $request)
    {
        $newInstance = clone $this;
        $newInstance->request = $request;
        return $newInstance;
    }

    /**
     * @param string $name
     * @param array $args
     * @return null|mixed
     */
    public function __call($name, $args)
    {
        if (!isset($this->modules[$name]) && isset($this->handlers[$name])) {
            $content = $this->container->get($this->handlers[$name]);

            if ($content instanceof ContentModule) {
                $this->modules[$name] = $content->getContent($this->request);
            } else {
                $this->modules[$name] = $content;
            }
        }
        return isset($this->modules[$name]) ? $this->modules[$name] : null;
    }
}

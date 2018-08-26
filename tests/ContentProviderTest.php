<?php

namespace King23\ContentModules {


    use PHPUnit\Framework\TestCase;
    use Psr\Container\ContainerInterface;
    use Psr\Http\Message\ServerRequestInterface;

    class  ContentProviderTest extends TestCase
    {
        public function testWithMockModule()
        {
            $container = $this->createMock(ContainerInterface::class);
            $testmodule = $this->createMock(ContentModule::class);
            $request = $this->createMock(ServerRequestInterface::class);

            $testmodule->method('getContent')->will($this->returnValue(['content' => 'testvalue', 'request' => $request]));
            $container->method('get')->will($this->returnValue($testmodule));

            $provider = new ContentProvider($container);
            $provider->registerModule('test', '\Mock\TestModule');
            $provider = $provider->withRequest($request);

            // this should simulate twig calling
            $content = $provider->test();

            $this->assertEquals(['content' => 'testvalue', 'request' => $request], $content);
        }
    }
}


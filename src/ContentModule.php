<?php
namespace King23\ContentModules;

use Psr\Http\Message\ServerRequestInterface;

interface ContentModule
{
    public function getContent(ServerRequestInterface $request);
}

<?php
namespace App;
use Symfony\Component\BrowserKit\Client as BaseClient;
use Symfony\Component\BrowserKit\Response as ResponseBrowerKit;

class Client extends BaseClient
{
    protected function doRequest($request)
    {
        $response = $this->kernel->handle($request, HttpKernelInterface::MASTER_REQUEST, $this->catchExceptions);
        if ($this->kernel instanceof TerminableInterface) {
            $this->kernel->terminate($request, $response);
        }
        return $response;
    }
}
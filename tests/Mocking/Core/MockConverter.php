<?php

namespace CoreLib\Tests\Mocking\Core;

use CoreDesign\Core\ContextInterface;
use CoreDesign\Core\Request\RequestInterface;
use CoreDesign\Core\Response\ResponseInterface;
use CoreDesign\Sdk\ConverterInterface;
use CoreLib\Tests\Mocking\Other\MockClass;
use CoreLib\Tests\Mocking\Types\MockApiResponse;
use CoreLib\Tests\Mocking\Types\MockContext;
use CoreLib\Tests\Mocking\Types\MockRequest;
use CoreLib\Tests\Mocking\Types\MockResponse;

class MockConverter implements ConverterInterface
{
    public function createApiException(
        string $message,
        RequestInterface $request,
        ResponseInterface $response
    ): MockClass {
        return new MockClass(
            $message,
            $this->createHttpRequest($request),
            $this->createHttpResponse($response)
        );
    }

    public function createHttpContext(ContextInterface $context): MockContext
    {
        return new MockContext(
            $this->createHttpRequest($context->getRequest()),
            $this->createHttpResponse($context->getResponse())
        );
    }

    public function createHttpRequest(RequestInterface $request): MockRequest
    {
        return new MockRequest(
            $request->getHttpMethod(),
            $request->getHeaders(),
            $request->getQueryUrl(),
            $request->getParameters()
        );
    }

    public function createHttpResponse(ResponseInterface $response): MockResponse
    {
        return new MockResponse(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getRawBody()
        );
    }

    public function createApiResponse(ContextInterface $context, $deserializedBody): MockApiResponse
    {
        $decodedBody = $context->getResponse()->getBody();
        return MockApiResponse::createFromContext($decodedBody, $deserializedBody, $this->createHttpContext($context));
    }
}

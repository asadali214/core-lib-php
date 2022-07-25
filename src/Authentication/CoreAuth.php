<?php

declare(strict_types=1);

namespace CoreLib\Authentication;

use CoreDesign\Core\Authentication\AuthInterface;
use CoreDesign\Core\Request\ParamInterface;
use CoreDesign\Core\Request\RequestSetterInterface;
use InvalidArgumentException;

class CoreAuth implements AuthInterface
{
    private $parameters;
    private $isValid = false;

    /**
     * @param ParamInterface ...$parameters
     */
    public function __construct(...$parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function validate(): void
    {
        foreach ($this->parameters as $param) {
            $param->validate();
        }
        $this->isValid = true;
    }

    public function apply(RequestSetterInterface $request): void
    {
        if (!$this->isValid) {
            return;
        }
        foreach ($this->parameters as $param) {
            $param->apply($request);
        }
    }
}
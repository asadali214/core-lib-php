<?php

declare(strict_types=1);

namespace CoreLib\Core\Request\Parameters;

use CoreDesign\Core\Request\RequestSetterInterface;

class FormParam extends Parameter
{
    public static function init(string $key, $value): self
    {
        return new self($key, $value);
    }
    private function __construct(string $key, $value)
    {
        parent::__construct($key, $value, 'form');
    }

    public function required(): self
    {
        parent::required();
        return $this;
    }

    public function serializeBy(callable $serializerMethod): self
    {
        parent::serializeBy($serializerMethod);
        return $this;
    }

    public function typeGroup(string $typeGroup, array $serializerMethods = []): self
    {
        parent::typeGroup($typeGroup, $serializerMethods);
        return $this;
    }

    public function apply(RequestSetterInterface $request): void
    {
        parent::validate();
        $request->addFormParam($this->key, $this->value);
    }
}
<?php

declare(strict_types=1);

namespace CoreLib\Utils;

use apimatic\jsonmapper\JsonMapper;
use CoreDesign\Core\Request\TypeValidatorInterface;
use Exception;
use InvalidArgumentException;

class JsonHelper implements TypeValidatorInterface
{
    /**
     * @var JsonMapper
     */
    private $jsonMapper;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @param array<string,string[]> $inheritedModel
     * @param string|null $additionalPropertiesMethodName
     * @param string $modelNamespace
     */
    public function __construct(array $inheritedModel, ?string $additionalPropertiesMethodName, string $modelNamespace)
    {
        $this->jsonMapper = new JsonMapper();
        $this->jsonMapper->arChildClasses = $inheritedModel;
        $this->jsonMapper->sAdditionalPropertiesCollectionMethod = $additionalPropertiesMethodName;
        $this->namespace = $modelNamespace;
    }

    /**
     * @param mixed  $value                Value to be verified against the types
     * @param string $strictType           Strict single type i.e. string, ModelName, etc. or group of types
     *                                     in string format i.e. oneof(...), anyof(...)
     * @param array  $serializationMethods Methods required for the serialization of specific types in
     *                                     in the provided types/type, should be an array in the format:
     *                                     ['path/to/method argumentType', ...]. Default: []
     * @return mixed Returns validated and serialized $value
     * @throws InvalidArgumentException
     */
    public function verifyTypes($value, string $strictType, array $serializationMethods = [])
    {
        try {
            return $this->jsonMapper->checkTypeGroupFor($strictType, $value, $serializationMethods);
        } catch (Exception $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
    }

    /**
     * @param mixed  $value     Value to be mapped by the class
     * @param string $classname Name of the class to map
     * @param int    $dimension Greater than 0 if trying to map an array of
     *                          class with some dimensions, Default: 0
     * @return mixed Returns the mapped $value
     * @throws Exception
     */
    public function mapClass($value, string $classname, int $dimension = 0)
    {
        return $dimension <= 0 ? $this->jsonMapper->mapClass($value, "$this->namespace\\$classname")
            : $this->jsonMapper->mapClassArray($value, "$this->namespace\\$classname", $dimension);
    }

    /**
     * @param mixed  $value         Value to be mapped by the typeGroup
     * @param string $typeGroup     Group of types in string format i.e. oneof(...), anyof(...)
     * @param array  $deserializers Methods required for the de-serialization of specific types in
     *                              in the provided typeGroup, should be an array in the format:
     *                              ['path/to/method returnType', ...]. Default: []
     * @return mixed Returns the mapped $value
     * @throws Exception
     */
    public function mapTypes($value, string $typeGroup, array $deserializers = [])
    {
        return $this->jsonMapper->mapFor($value, $typeGroup, $this->namespace, $deserializers);
    }
}

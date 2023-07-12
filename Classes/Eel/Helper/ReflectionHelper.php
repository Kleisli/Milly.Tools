<?php
namespace Milly\Tools\Eel\Helper;

use Milly\Tools\Service\ReflectionService;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;

class ReflectionHelper implements ProtectedContextAwareInterface
{

    #[Flow\Inject]
    protected ReflectionService $reflectionService;

    /**
     * @param object $model
     * @param string $propertyName
     * @return string
     */
    public function getTypeOfProperty(object $model, string $propertyName): string
    {
        return $this->reflectionService->getTypeOfProperty($model::class, $propertyName);
    }

    /**
     * @param object $model
     * @param string $relationName
     * @return string
     */
    public function getTypeOfRelation(object $model, string $relationName): string
    {
        return $this->reflectionService->getTypeOfRelation($model::class, $relationName);
    }

    /**
     * @param object $model
     * @param string $relationName
     * @return bool
     */
    public function isToOneRelation(object $model, string $relationName): bool
    {
        return $this->reflectionService->isToOneRelation($model::class, $relationName);
    }

    /**
     * @param object $model
     * @param string $relationName
     * @return bool
     */
    public function isToManyRelation(object $model, string $relationName): bool
    {
        return $this->reflectionService->isToManyRelation($model::class, $relationName);
    }

    /**
     * All methods are considered safe
     *
     * @param string $methodName
     * @return boolean
     */
    public function allowsCallOfMethod($methodName)
    {
        return true;
    }

}

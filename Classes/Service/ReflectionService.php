<?php
namespace Milly\Tools\Service;

use Doctrine\ORM\EntityManagerInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Reflection\ReflectionService as FlowReflectionService;
use Neos\Utility\Exception\InvalidTypeException;
use Neos\Utility\TypeHandling;

/**
 * @Flow\Scope("singleton")
 */
class ReflectionService
{

    #[Flow\Inject]
    protected FlowReflectionService $reflectionService;

    #[Flow\Inject]
    protected EntityManagerInterface $entityManager;

    /**
     * @param $className
     * @param $propertyName
     * @return string
     */
    public function getTypeOfProperty($className, $propertyName): string
    {
        $className = $this->entityManager->getClassMetadata($className)->getName();
        return $this->reflectionService->getPropertyType($className, $propertyName) ?? $this->reflectionService->getPropertyTagValues($className, $propertyName, 'var')[0];
    }

    /**
     * @param $className
     * @param $relationName
     * @return string
     * @throws InvalidTypeException
     */
    public function getTypeOfRelation($className, $relationName): string
    {
        $className = $this->entityManager->getClassMetadata($className)->getName();
        if($this->isToManyRelation($className, $relationName)){
            $propertyType = $this->reflectionService->getPropertyTagValues($className, $relationName, 'var')[0];
        }else{
            $var = $this->reflectionService->getPropertyTagValues($className, $relationName, 'var');
            $propertyType = count($var) ? $var[0] : $this->reflectionService->getPropertyType($className, $relationName);
        }
        $types = TypeHandling::parseType($propertyType);
        return $this->isToManyRelation($className, $relationName) ? $types['elementType'] : $types['type'];
    }

    /**
     * @param $className
     * @param $relationName
     * @return bool
     */
    public function isToOneRelation($className, $relationName): bool
    {
        $className = $this->entityManager->getClassMetadata($className)->getName();
        return $this->reflectionService->isPropertyTaggedWith($className, $relationName, 'manytoone') ||
            $this->reflectionService->isPropertyTaggedWith($className, $relationName, 'onetoone');
    }

    /**
     * @param $className
     * @param $relationName
     * @return bool
     */
    public function isToManyRelation($className, $relationName): bool
    {
        $className = $this->entityManager->getClassMetadata($className)->getName();
        return $this->reflectionService->isPropertyTaggedWith($className, $relationName, 'manytomany') ||
            $this->reflectionService->isPropertyTaggedWith($className, $relationName, 'onetomany');
    }

}


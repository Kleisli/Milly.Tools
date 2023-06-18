<?php
namespace Milly\Tools\Service;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Reflection\ReflectionService as FlowReflectionService;

/**
 * @Flow\Scope("singleton")
 */
class ReflectionService
{

    /**
     * @Flow\Inject
     */
    protected FlowReflectionService $reflectionService;

    /**
     * @param $className
     * @param $propertyName
     * @return string
     */
    public function getTypeOfProperty($className, $propertyName){
        $className = self::cleanClassName($className);
        return $this->reflectionService->getPropertyType($className, $propertyName) ?? $this->reflectionService->getPropertyTagValues($className, $propertyName, 'var')[0];
    }

    /**
     * @param $className
     * @param $relationName
     * @return string
     */
    public function getTypeOfRelation($className, $relationName){
        $className = self::cleanClassName($className);
        //$propertyType = $this->reflectionService->getPropertyType($className, $relationName) ?? $this->reflectionService->getPropertyTagValues($className, $relationName, 'var')[0];
        $propertyType = $this->reflectionService->getPropertyTagValues($className, $relationName, 'var')[0];
        $parts =  explode('<', $propertyType);
        return substr($parts[1], 0, -1);
    }

    /**
     * @param $className
     * @param $relationName
     * @return string
     */
    public function isToOneRelation($className, $relationName){
        $className = self::cleanClassName($className);
        return $this->reflectionService->isPropertyTaggedWith($className, $relationName, 'manytoone') ||
            $this->reflectionService->isPropertyTaggedWith($className, $relationName, 'onetoone');
    }

    /**
     * @param $className
     * @param $relationName
     * @return string
     */
    public function isToManyRelation($className, $relationName){
        $className = self::cleanClassName($className);
        return $this->reflectionService->isPropertyTaggedWith($className, $relationName, 'manytomany') ||
            $this->reflectionService->isPropertyTaggedWith($className, $relationName, 'onetomany');
    }

    /**
     * @param object|string $class An object (class instance) or a string (class name)
     * @param $propertyName
     * @return bool
     * @throws \ReflectionException
     */
    public function isPropertyPublic($class, $propertyName){
        $rp = new \ReflectionProperty($class, $propertyName);
        return $rp->isPublic();
    }


    /**
     * @param $className
     * @return string
     */
    public static function cleanClassName($className){
        return str_replace('Neos\\Flow\\Persistence\\Doctrine\\Proxies\\__CG__\\', '', $className);
    }

}


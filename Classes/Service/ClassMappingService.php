<?php
namespace Milly\Tools\Service;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Exception;
use Neos\Flow\Mvc\Controller\ControllerInterface;
use Neos\Flow\Persistence\RepositoryInterface;
use Neos\Flow\Reflection\ReflectionService as FlowReflectionService;

/**
 * @Flow\Scope("singleton")
 */
class ClassMappingService
{
    const TYPE_CONTROLLER = 'Controller';
    const TYPE_MODEL = 'Model';
    const TYPE_REPOSITORY = 'Repository';

    /**
     * @Flow\Inject
     */
    protected FlowReflectionService $reflectionService;

    /**
     * @param object|string $model An object (class instance) or a string (class name) of a domain model
     * @return string
     * @throws Exception
     */
    public function getRepositoryClassByModel($model){
        $modelClassName = is_object($model) ? $model::class : $model;
        $repositoryClassNames = $this->reflectionService->getAllImplementationClassNamesForInterface(RepositoryInterface::class);

        foreach ($repositoryClassNames as $repositoryClassName) {
            if (defined($repositoryClassName . '::ENTITY_CLASSNAME') && $repositoryClassName::ENTITY_CLASSNAME == $modelClassName) {
                return $repositoryClassName;
            }
        }

        return ClassMappingService::getRepositoryClass($modelClassName);
    }

    /**
     * @param object|string $model An object (class instance) or a string (class name) of a domain model
     * @return string
     * @throws Exception
     */
    public function getControllerClassByModel($model){
        $modelClassName = is_object($model) ? $model::class : $model;
        $controllerClassNames = $this->reflectionService->getAllImplementationClassNamesForInterface(ControllerInterface::class);

        foreach ($controllerClassNames as $controllerClassName) {
            if (defined($controllerClassName . '::ENTITY_CLASSNAME') && $controllerClassName::ENTITY_CLASSNAME == $modelClassName) {
                return $controllerClassName;
            }
        }

        return ClassMappingService::getControllerClass($modelClassName);
    }

    /**
     * @param string $className a Controller, Model or Repository class name
     * @return string
     * @throws Exception
     */
    public static function getModelClass(string $className)
    {
        return self::convertClass($className, self::TYPE_MODEL);
    }

    /**
     * @param string $className a Controller, Model or Repository class name
     * @return string
     * @throws Exception
     */
    public static function getRepositoryClass(string $className)
    {
        return self::convertClass($className, self::TYPE_REPOSITORY);
    }

    /**
     * @param string $className a Controller, Model or Repository class name
     * @return string
     * @throws Exception
     */
    public static function getControllerClass(string $className)
    {
        return self::convertClass($className, self::TYPE_CONTROLLER);
    }

    /**
     * @param string $className a Controller, Model or Repository class name
     * @return string
     */
    public static function getPackageName(string $className): string
    {
        $className = ReflectionService::cleanClassName($className);
        $parts = explode('\\', $className);
        $packageName = array_shift($parts);
        foreach($parts as $part){
            if($part == "Controller" || $part == "Domain"){
                break;
            }
            $packageName .= '.'.$part;
        }
        return $packageName;
    }

    /**
     * @param string $className a Controller, Model or Repository class name
     * @return string
     * @throws \Neos\Flow\Exception
     */
    public static function getControllerName(string $className): string
    {
        $controllerClass = ClassMappingService::getControllerClass($className);
        $parts =  explode('\\Controller\\', $controllerClass);
        return substr($parts[1], 0, -10);
    }

    /**
     * @param string $className a Controller, Model or Repository class name
     * @return string
     * @throws \Neos\Flow\Exception
     */
    public static function getModelName(string $className): string
    {
        $modelClass = ClassMappingService::getModelClass($className);
        $parts = explode('\\Model\\', $modelClass);
        return $parts[1];
    }

    /**
     * @param string $className a Controller, Model or Repository class name
     * @param string $type one of the ClassMappingService::TYPE_* constants
     * @return string className
     * @throws Exception
     */
    static public function convertClass(string $className, string $type){
        $className = ReflectionService::cleanClassName($className);

        $className = str_replace(['\\Controller\\', '\\Domain\\Model\\', '\\Domain\\Repository\\'], '\\{Type}\\', $className);
        if(str_ends_with($className, 'Repository') || str_ends_with($className, 'Controller')){
            $className = substr($className, 0, -10);
        }

        $className = match ($type) {
            self::TYPE_CONTROLLER => str_replace('\\{Type}\\', '\\Controller\\', $className) . 'Controller',
            self::TYPE_MODEL => str_replace('\\{Type}\\', '\\Domain\\Model\\', $className),
            self::TYPE_REPOSITORY => str_replace('\\{Type}\\', '\\Domain\\Repository\\', $className) . 'Repository',
        };

        if(!class_exists($className)){
            throw new Exception('No '.$type.' found with class name '.$className);
        }

        return $className;
    }


}


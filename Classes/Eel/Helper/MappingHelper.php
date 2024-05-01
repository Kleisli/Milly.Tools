<?php
namespace Milly\Tools\Eel\Helper;

use Milly\Tools\Service\ClassMappingService;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Exception;
use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use Neos\Flow\Persistence\QueryResultInterface;
use Neos\Flow\Persistence\RepositoryInterface;
use Neos\Flow\Annotations as Flow;

class MappingHelper implements ProtectedContextAwareInterface
{
    #[Flow\Inject]
    protected ObjectManagerInterface $objectManager;

    #[Flow\Inject]
    protected ClassMappingService $classMappingService;

    /**
     * @param object|string $model An object (class instance) or a string (class name) of a domain model
     * @return string
     * @throws Exception
     */
    public function getControllerPackageNameByModel(object|string $model): string
    {
        $controllerClass = $this->classMappingService->getControllerClassByModel($model);
        return ClassMappingService::getPackageName($controllerClass);
    }

    /**
     * @param object|string $model An object (class instance) or a string (class name) of a domain model
     * @return string
     * @throws Exception
     */
    public function getControllerNameByModel(object|string $model): string
    {
        $controllerClass = $this->classMappingService->getControllerClassByModel($model);
        return ClassMappingService::getControllerName($controllerClass);
    }

    /**
     * @param object|string $model An object (class instance) or a string (class name) of a domain model
     * @return string
     * @throws Exception
     */
    public function getXliffIdPrefixByModel(object|string $model): string
    {
        $modelClassName = is_object($model) ? $model::class : $model;
        return ClassMappingService::getPackageName($modelClassName).':Model.'.ClassMappingService::getModelName($modelClassName).':';
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

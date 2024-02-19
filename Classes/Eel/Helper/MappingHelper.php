<?php
namespace Milly\Tools\Eel\Helper;

use Kleisli\CrudForms\Service\ConfigurationService;
use Milly\Tools\Service\ClassMappingService;
use Milly\Tools\Service\ReflectionService;
use Neos\Eel\ProtectedContextAwareInterface;
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
     */
    public function getPackageNameByModel(object|string $model): string
    {
        $modelClassName = is_object($model) ? $model::class : $model;
        $modelClassName = ReflectionService::cleanClassName($modelClassName);
        $controllerClass = $this->getControllerClassByModel($modelClassName);
        return ClassMappingService::getPackageName($controllerClass);
    }

    /**
     * @param string $className a Controller, Model or Repository class name
     * @return string
     * @deprecated
     */
    public function getPackageNameByModelClass(string $className): string
    {
        $controllerClass = $this->getControllerClassByModel($className);
        return ClassMappingService::getPackageName($controllerClass);
    }

    /**
     * @param string $className a Controller, Model or Repository class name
     * @return string
     * @throws \Neos\Flow\Exception
     */
    public function getControllerNameByClass(string $className): string
    {
        return ClassMappingService::getControllerName($className);
    }

    /**
     * @param string $className a Controller, Model or Repository class name
     * @return RepositoryInterface
     * @throws \Neos\Flow\Exception
     */
    public function getRepositoryByClass(string $className): RepositoryInterface
    {
        return $this->objectManager->get(ClassMappingService::getRepositoryClass($className));
    }

    /**
     * @param object|string $model An object (class instance) or a string (class name) of a domain model
     * @return string
     */
    public function getRepositoryClassByModel(object|string $model): string
    {
        return $this->classMappingService->getRepositoryClassByModel($model);
    }

    /**
     * @param object|string $model An object (class instance) or a string (class name) of a domain model
     * @return string
     */
    public function getControllerClassByModel(object|string $model): string
    {
        return $this->classMappingService->getControllerClassByModel($model);
    }

    /**
     * @param object|string $model An object (class instance) or a string (class name) of a domain model
     * @return string
     */
    public function getXliffIdPrefixByModel(object|string $model): string
    {
        $modelClassName = is_object($model) ? $model::class : $model;
        return ClassMappingService::getPackageName($modelClassName).':Model.'.ClassMappingService::getModelName($modelClassName).':';
    }

    /**
     * @param string $className a Controller, Model or Repository class name
     * @return QueryResultInterface
     * @throws \Neos\Flow\Exception
     */
    public function findAllByClass(string $className): QueryResultInterface
    {
        return $this->getRepositoryByClass($className)->findAll();
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

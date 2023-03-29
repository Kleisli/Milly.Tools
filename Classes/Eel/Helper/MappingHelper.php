<?php
namespace Milly\Tools\Eel\Helper;

use Kleisli\CrudForms\Service\ConfigurationService;
use Kleisli\Flow\Persistence\Repository;
use Milly\Tools\Service\ClassMappingService;
use Milly\Tools\Service\ReflectionService;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Flow\Persistence\QueryResultInterface;
use Neos\Flow\Persistence\RepositoryInterface;
use Profolio\Commerce\Domain\Model\Order;
use Profolio\Commerce\Domain\Model\Price;
use Profolio\Commerce\Domain\Model\Product;
use Profolio\Commerce\Domain\Model\ProductVariant;
use Profolio\Commerce\Domain\Model\QuantityDiscount;
use Profolio\Commerce\Domain\Repository\ProductRepository;
use Profolio\Data\Domain\Model\LpLearningGoal;
use Profolio\Data\Domain\Model\Stao\Dimension;
use Profolio\Data\Domain\Model\Stao\Item;
use Profolio\Data\Domain\Repository\LpLearningGoalRepository;
use Profolio\Data\Domain\Repository\Stao\DimensionRepository;
use Profolio\Data\Domain\Repository\Stao\ItemRepository;
use Profolio\UserManagement\Domain\Repository\UserRepository;

class MappingHelper implements ProtectedContextAwareInterface
{
    /**
     * @Flow\Inject
     */
    protected ObjectManagerInterface $objectManager;

    /**
     * @Flow\Inject
     */
    protected ClassMappingService $classMappingService;

    /**
     * @param string $className a Controller, Model or Repository class name
     * @return string
     */
    public function getPackageNameByClass(string $className): string
    {
        return ClassMappingService::getPackageName($className);
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

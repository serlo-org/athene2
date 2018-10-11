<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013-2014 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Uuid\Factory;

use ClassResolver\ClassResolverFactoryTrait;
use Common\Factory\AuthorizationServiceFactoryTrait;
use Common\Factory\EntityManagerFactoryTrait;
use Uuid\Manager\UuidManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UuidManagerFactory implements FactoryInterface
{
    use AuthorizationServiceFactoryTrait, EntityManagerFactoryTrait, ClassResolverFactoryTrait;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $moduleOptions        = $serviceLocator->get('Uuid\Options\ModuleOptions');
        $authorizationService = $this->getAuthorizationService($serviceLocator);
        $entityManager        = $this->getEntityManager($serviceLocator);
        $classResolver        = $this->getClassResolver($serviceLocator);
        $uuidManager          = new UuidManager($authorizationService, $classResolver, $moduleOptions, $entityManager);

        return $uuidManager;
    }
}

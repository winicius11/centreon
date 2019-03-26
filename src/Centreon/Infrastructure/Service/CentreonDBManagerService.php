<?php
namespace Centreon\Infrastructure\Service;

use Psr\Container\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Centreon\Infrastructure\CentreonLegacyDB\CentreonDBAdapter;
use Centreon\Infrastructure\CentreonLegacyDB\ServiceEntityRepository;
use Centreon\Infrastructure\CentreonLegacyDB\UnitOfWork;
use Centreon\Infrastructure\CentreonLegacyDB\ClassMetadataFactory;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;

/**
 * Compatibility with Doctrine Entity manager
 */
class CentreonDBManagerService implements ObjectManager
{

    /**
     * @var string
     */
    protected $defaultManager;

    /**
     * @var \Centreon\Infrastructure\CentreonLegacyDB\CentreonDBAdapter
     */
    protected $manager;

    /**
     * The metadata factory, used to retrieve the ORM metadata of entity classes.
     *
     * @var \Centreon\Infrastructure\CentreonLegacyDB\ClassMetadataFactory
     */
    protected $metadataFactory;

    /**
     * The UnitOfWork used to coordinate object-level transactions.
     *
     * @var \Centreon\Infrastructure\CentreonLegacyDB\UnitOfWork
     */
    protected $unitOfWork;

    /**
     * Construct
     * 
     * @param \Psr\Container\ContainerInterface $services
     */
    public function __construct(ContainerInterface $services)
    {
        $this->manager = [
            'configuration_db' => new CentreonDBAdapter($services->get('configuration_db'), $this),
            'realtime_db' => new CentreonDBAdapter($services->get('realtime_db'), $this),
        ];

        $this->defaultManager = 'configuration_db';

        $this->metadataFactory = new ClassMetadataFactory;
        $this->metadataFactory->setEntityManager($this);
        $this->unitOfWork = new UnitOfWork($this);
    }

    /**
     * Get adapter with DB connection
     *
     * @param string $alias
     * @return \Centreon\Infrastructure\CentreonLegacyDB\CentreonDBAdapter
     */
    public function getAdapter(string $alias): CentreonDBAdapter
    {
        $manager = array_key_exists($alias, $this->manager) ?
            $this->manager[$alias] :
            null;

        return $manager;
    }

    /**
     * Get default adapter with DB connection
     *
     * @return \Centreon\Infrastructure\CentreonLegacyDB\CentreonDBAdapter
     */
    public function getDefaultAdapter(): CentreonDBAdapter
    {
        return $this->manager[$this->defaultManager];
    }

    /**
     * {@inheritdoc}
     */
    public function find($className, $id)
    {
        return $this->getRepository($className)->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function persist($entity)
    {
        $this->unitOfWork->persist($entity);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($object)
    {
        // ...
    }

    /**
     * {@inheritdoc}
     */
    public function merge($object)
    {
        // ...
    }

    /**
     * {@inheritdoc}
     */
    public function clear($objectName = null)
    {
        // ...
    }

    /**
     * {@inheritdoc}
     */
    public function detach($object)
    {
        // ...
    }

    /**
     * {@inheritdoc}
     */
    public function refresh($object)
    {
        // ...
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        $this->unitOfWork->commit();
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository($className): ServiceEntityRepository
    {
        return $this->getAdapter($this->defaultManager)->getRepository($className);
    }

    /**
     * {@inheritdoc}
     */
    public function getClassMetadata($className) : ClassMetadata
    {
        return $this->metadataFactory->getMetadataFor($className);
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadataFactory() : ClassMetadataFactory
    {
        return $this->metadataFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function initializeObject($obj)
    {
        // ...
    }

    /**
     * {@inheritdoc}
     */
    public function contains($object)
    {
        // ...
    }
}

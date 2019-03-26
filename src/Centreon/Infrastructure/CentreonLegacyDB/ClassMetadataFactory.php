<?php

namespace Centreon\Infrastructure\CentreonLegacyDB;

use Doctrine\Common\Persistence\Mapping\ClassMetadataFactory as BaseClassMetadataFactory;
use Centreon\Infrastructure\Service\CentreonDBManagerService;
use Doctrine\ORM\Mapping\ClassMetadata;

class ClassMetadataFactory implements BaseClassMetadataFactory
{
    /**
     * @var \Centreon\Infrastructure\Service\CentreonDBManagerService
     */
    protected $em;

    /**
     * @var array
     */
    protected $metadata;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->metadata = [];
    }

    /**
     * Set Entity manager
     *
     * @param \Centreon\Infrastructure\Service\CentreonDBManagerService $em
     */
    public function setEntityManager(CentreonDBManagerService $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllMetadata()
    {
        return $this->metadata;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadataFor($className)
    {
        if ($this->hasMetadataFor($className) === false) {
            $classmeta = new ClassMetadata($className);
            $className::loadMetadata($classmeta);
            
            $this->setMetadataFor($className, $classmeta);
        }

        return $this->metadata[$className];
    }

    /**
     * {@inheritdoc}
     */
    public function hasMetadataFor($className) : bool
    {
        return array_key_exists($className, $this->metadata);
    }

    /**
     * {@inheritdoc}
     */
    public function setMetadataFor($className, $class)
    {
        $this->metadata[$className] = $class;
    }

    /**
     * @todo ...
     *
     * {@inheritdoc}
     */
    public function isTransient($className)
    {
        return false;
    }
}

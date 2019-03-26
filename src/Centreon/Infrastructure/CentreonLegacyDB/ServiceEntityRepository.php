<?php

namespace Centreon\Infrastructure\CentreonLegacyDB;

use CentreonDB;
use Centreon\Infrastructure\Service\CentreonDBManagerService;
//use Centreon\Infrastructure\CentreonLegacyDB\StatementCollector;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use PDO;

/**
 * Compatibility with Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository
 */
abstract class ServiceEntityRepository implements ObjectRepository
{

    /**
     * @var \CentreonDB
     */
    protected $db;

    /**
     * @var \Centreon\Infrastructure\Service\CentreonDBManagerService
     */
    protected $em;

    /**
     * Construct
     * 
     * @param \CentreonDB $db
     * @param \Centreon\Infrastructure\Service\CentreonDBManagerService $em
     */
    public function __construct(CentreonDB $db, CentreonDBManagerService $em = null)
    {
        $this->db = $db;
        $this->em = $em;
    }

    /**
     * Get access to entity manager
     *
     * @return \Centreon\Infrastructure\Service\CentreonDBManagerService
     */
    public function getEntityManager() : CentreonDBManagerService
    {
        return $this->em;
    }

    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        $metadata = $this->getClassName();

        $sql = 'SELECT * FROM `'.$metadata->getTableName().'` WHERE `'.$metadata->getIdentifierColumnNames()[0].'` = :id';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $metadata->getName());
        $stmt->execute();

        $entity = $stmt->fetch();
        
        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll()
    {
        $metadata = $this->getClassName();

        $sql = 'SELECT * FROM `'.$metadata->getTableName().'` WHERE `'.$metadata->getIdentifierColumnNames()[0].'` = :id';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $metadata->getName());
        $stmt->execute();

        $entities = $stmt->fetchAll();
        
        return $entities;
    }

    /**
     * {@inheritdoc}
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
    {
        // ...
    }

    /**
     * {@inheritdoc}
     */
    public function findOneBy(array $criteria)
    {
        // ...
    }

    /**
     * {@inheritdoc}
     */
    public function getClassName() : ClassMetadata
    {
        // @todo parse entity classname
        return $this->getEntityManager()->getClassMetadata($this->getEntityClassName());
    }

//    abstract public static function getEntityClassName() : string;
}

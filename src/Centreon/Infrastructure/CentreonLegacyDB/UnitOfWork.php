<?php

namespace Centreon\Infrastructure\CentreonLegacyDB;

use Centreon\Infrastructure\Service\CentreonDBManagerService;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Limitation: it works only with default adapter
 */
class UnitOfWork
{

    /**
     * @var \Centreon\Infrastructure\Service\CentreonDBManagerService
     */
    protected $em;
    protected $entityInsertions;

    /**
     * Construct
     *
     * @param \Centreon\Infrastructure\Service\CentreonDBManagerService $em
     */
    public function __construct(CentreonDBManagerService $em)
    {
        $this->em = $em;
    }

    public function persist($entity)
    {
        $this->entityInsertions[] = $this->em->getClassMetadata(get_class($entity));
    }

    public function commit()
    {
        if (!$this->entityInsertions) {
            return;
        }
        
        $conn = $this->em->getDefaultAdapter();

        $conn->beginTransaction();
        
        try {
            // Collection deletions (deletions of complete collections)
//            foreach ($this->collectionDeletions as $collectionToDelete) {
//                $this->getCollectionPersister($collectionToDelete->getMapping())->delete($collectionToDelete);
//            }
            if ($this->entityInsertions) {
                foreach ($this->entityInsertions as $class) {
                    $this->executeInserts($class);
                }
            }
//            if ($this->entityUpdates) {
//                foreach ($commitOrder as $class) {
//                    $this->executeUpdates($class);
//                }
//            }
            // Extra updates that were requested by persisters.
//            if ($this->extraUpdates) {
//                $this->executeExtraUpdates();
//            }
            // Collection updates (deleteRows, updateRows, insertRows)
//            foreach ($this->collectionUpdates as $collectionToUpdate) {
//                $this->getCollectionPersister($collectionToUpdate->getMapping())->update($collectionToUpdate);
//            }
            // Entity deletions come last and need to be in reverse commit order
//            if ($this->entityDeletions) {
//                foreach (array_reverse($commitOrder) as $committedEntityName) {
//                    if (! $this->entityDeletions) {
//                        break; // just a performance optimisation
//                    }
//                    $this->executeDeletions($committedEntityName);
//                }
//            }
            $conn->commit();
        } catch (\Throwable $e) {
            $conn->rollBack();
            throw $e;
        }

        $conn->commit();
    }

    /**
     * Executes all entity insertions for entities of the specified type.
     */
    private function executeInserts(ClassMetadata $class) : void
    {
//        $className      = $class->getClassName();
        var_dump($class->getColumnNames());exit;
//        $persister      = $this->getEntityPersister($className);
//        $invoke         = $this->listenersInvoker->getSubscribedSystems($class, Events::postPersist);
//        $generationPlan = $class->getValueGenerationPlan();
        foreach ($this->entityInsertions as $oid => $entity) {
//            if ($this->em->getClassMetadata(get_class($entity))->getClassName() !== $className) {
//                continue;
//            }
//            $persister->insert($entity);
//            if ($generationPlan->containsDeferred()) {
//                // Entity has post-insert IDs
//                $oid = spl_object_id($entity);
//                $id  = $persister->getIdentifier($entity);
//                $this->entityIdentifiers[$oid]  = $this->em->getIdentifierFlattener()->flattenIdentifier($class, $id);
//                $this->entityStates[$oid]       = self::STATE_MANAGED;
//                $this->originalEntityData[$oid] = $id + $this->originalEntityData[$oid];
//                $this->addToIdentityMap($entity);
//            }
//            unset($this->entityInsertions[$oid]);
//            if ($invoke !== ListenersInvoker::INVOKE_NONE) {
//                $eventArgs = new LifecycleEventArgs($entity, $this->em);
//                $this->listenersInvoker->invoke($class, Events::postPersist, $entity, $eventArgs, $invoke);
//            }
        }
    }
}

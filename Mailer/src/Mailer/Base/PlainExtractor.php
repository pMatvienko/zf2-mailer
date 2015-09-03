<?php
namespace Mailer\Base;

use Doctrine\ORM\Mapping\ClassMetadata;
use Zend\Stdlib\Extractor\ExtractionInterface;

class PlainExtractor implements ExtractionInterface
{
    private $em = null;
    private $recursive = false;
    private $joinBy = '.';

    public function __construct($em, $joinBy = null, $recursive=false)
    {
        $this->setEm($em);
        $this->setRecursive(boolval($recursive));
        $this->setJoinBy($joinBy);
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @param null $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }

    /**
     * Extract values from an object
     *
     * @param  object $entity
     *
     * @return array
     */
    public function extract($entity, $keyPrefix = null, $disableMapping = null)
    {
        return $this->isEntity($entity)
            ? $this->extractDoctrineObject($entity, $keyPrefix, $disableMapping)
            : $this->extractArray($entity, $keyPrefix, $disableMapping);
    }

    private function extractArray($array, $keyPrefix = null, $disableMapping = null)
    {
        $data = array();
        foreach ($array as $field => $value) {
            $dataKey = ((null != $keyPrefix) ? ($keyPrefix . $this->getJoinBy()) : '') . $field;

            if ($this->isEntity($value) || is_array($value)) {
                if ($this->isRecursive()) {
                    $data += $this->extract($value, $dataKey);
                }
            } else {
                $data[$dataKey] = $value;
            }
        }

        return $data;
    }

    private function extractDoctrineObject($entity, $keyPrefix = null, $disableMapping = null)
    {
        $data = array();
        $className = get_class($entity);

        $uow = $this->getEm()->getUnitOfWork();
        $originalData = $uow->getOriginalEntityData($entity);
        if (empty($originalData) && $entity instanceof \Doctrine\ORM\Proxy\Proxy) {
            $this->getEm()->refresh($entity);
            $originalData = $uow->getOriginalEntityData($entity);
        }

        $entityPersister = $uow->getEntityPersister($className);
        $classMetadata = $entityPersister->getClassMetadata();

        foreach ($originalData as $field => $mapping) {
            $dataKey = ((null != $keyPrefix) ? ($keyPrefix . $this->getJoinBy()) : '') . $field;
            if ($mapping instanceof \Doctrine\ORM\PersistentCollection) {
                continue;
            } elseif ($mapping instanceof \DateTime) {
                $data[$dataKey] = $mapping->format('Y-m-d H:i:s');
            } elseif (isset($classMetadata->associationMappings[$field])) {
                if ($this->isRecursive()) {
                    $assoc = $classMetadata->associationMappings[$field];
                    if (
                        (ClassMetadata::TO_ONE == $assoc['type'] || ClassMetadata::ONE_TO_ONE == $assoc['type'] || $assoc['isOwningSide']) &&
                        $assoc['fieldName'] != $disableMapping
                    ) {
                        if (null == $mapping) {
                            $data[$dataKey] = $mapping;
                        } else {
                            $data += $this->extract($mapping, $dataKey, $assoc['mappedBy']);
                        }
                    }
                }
            } elseif (is_object($mapping)) {
                $data[$dataKey] = (string)$mapping;
            } else {
                $data[$dataKey] = $mapping;
            }
        }

        return $data;
    }

    public function isEntity($class)
    {
        if (is_object($class)) {
            $class = ($class instanceof \Doctrine\ORM\Proxy\Proxy)
                ? get_parent_class($class)
                : get_class($class);
        } else {
            return false;
        }

        return !$this->getEm()->getMetadataFactory()->isTransient($class);
    }

    /**
     * @return boolean
     */
    public function isRecursive()
    {
        return $this->recursive;
    }

    /**
     * @param boolean $recursive
     */
    public function setRecursive($recursive)
    {
        $this->recursive = $recursive;
    }

    /**
     * @return string
     */
    public function getJoinBy()
    {
        return $this->joinBy;
    }

    /**
     * @param string $joinBy
     *
     * @return $this
     */
    public function setJoinBy($joinBy)
    {
        $this->joinBy = $joinBy;

        return $this;
    }
}
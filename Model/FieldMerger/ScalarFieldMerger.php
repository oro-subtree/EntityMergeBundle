<?php

namespace Oro\Bundle\EntityMergeBundle\Model\FieldMerger;

use Doctrine\DBAL\Types\Type;
use Oro\Bundle\EntityMergeBundle\Data\FieldData;

class ScalarFieldMerger implements FieldMergerInterface
{
    public static $scope;

    public function __construct()
    {
        //todo: should we copy types into this class?
        self::$scope = array_keys(Type::getTypesMap());
    }

    /**
     * {@inheritdoc}
     */
    public function merge(FieldData $fieldData)
    {
        $entityData    = $fieldData->getEntityData();
        $masterEntity  = $entityData->getMasterEntity();
        $fieldMetadata = $fieldData->getMetadata();
        $sourceEntity  = $fieldData->getSourceEntity();

        $getMethod = $fieldMetadata->has('merge_getter') ?
            $fieldMetadata->get('merge_getter') :
            'get' . ucfirst($fieldMetadata->getFieldName());

        $setMethod = $fieldMetadata->has('merge_setter') ?
            $fieldMetadata->get('merge_setter') :
            'set' . ucfirst($fieldMetadata->getFieldName());

        $masterEntity->$setMethod($sourceEntity->$getMethod());
    }

    /**
     * {@inheritdoc}
     */
    public function supports(FieldData $fieldData)
    {
        if ($fieldData->getMetadata()->getDoctrineMetadata()->has('name')) {
            $fieldData->getMetadata()->getDoctrineMetadata()->get('name');
        }

        return false;
    }
} 
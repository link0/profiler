<?php

namespace Link0\Profiler;

use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Metadata\PropertyMetadata;
use Metadata\Driver\DriverInterface;

/**
 * Class ProfileMetadataDriver
 * Metadata driver implementation for the JMSSerializer
 *
 * @package Link0\Profiler
 */
class ProfileMetadataDriver implements DriverInterface
{
    /**
     * @var ProfileFactoryInterface
     */
    private $profileFactory;

    /**
     * @param ProfileFactoryInterface $profileFactory
     */
    public function __construct(ProfileFactoryInterface $profileFactory)
    {
        $this->profileFactory = $profileFactory;
    }

    /**
     * @param \ReflectionClass $class
     *
     * @return ClassMetadata
     * @throws Exception
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        $className = $class->getName();
        $factoryClassName = $this->profileFactory->getClassName();

        if($className !== $factoryClassName) {
            throw new Exception("Class name '" . $className . "' does not match ProfileFactory for '" . $factoryClassName . "'");
        }

        $classMetadata = new ClassMetadata($class->getName());

        foreach ($class->getProperties() as $reflectionProperty) {
            $this->createMetadataProperty($class, $reflectionProperty, $classMetadata);
        }

        return $classMetadata;
    }

    /**
     * @param \ReflectionClass $class
     * @param \ReflectionProperty $reflectionProperty
     * @param ClassMetaData $classMetadata
     */
    private function createMetadataProperty(\ReflectionClass $class, \ReflectionProperty $reflectionProperty, ClassMetaData $classMetadata)
    {
        $propertyMetadata = new PropertyMetadata($class->getName(), $reflectionProperty->getName());

        $propertyMetadata->setType('array');
        if ($reflectionProperty->getName() == 'identifier') {
            $propertyMetadata->setType('string');
        }

        $classMetadata->addPropertyMetadata($propertyMetadata);
    }
}

<?php

/**
 * Serializer.php
 */
namespace Link0\Profiler;

use JMS\Serializer\Builder\CallbackDriverFactory;
use JMS\Serializer\Exception\UnsupportedFormatException;
use JMS\Serializer\Serializer as JMSSerializer;
use JMS\Serializer\SerializerBuilder;

/**
 * Serializer implementation for PHPs serialize() and unserialize() functions
 *
 * @author Dennis de Greef <github@link0.net>
 */
final class Serializer implements SerializerInterface
{
    /**
     * @const string TYPE_JSON Type of serialization
     */
    const TYPE_JSON = 'json';

    /**
     * @var ProfileFactoryInterface
     */
    private $profileFactory;

    /**
     * @var string
     */
    private $type;

    /**
     * @var JMSSerializer
     */
    private $serializer;

    /**
     * @param ProfileFactoryInterface $profileFactory
     * @param string $type
     */
    public function __construct(ProfileFactoryInterface $profileFactory, $type = self::TYPE_JSON)
    {
        $this->profileFactory = $profileFactory;
        $this->type = $type;

        $this->serializer = SerializerBuilder::create()
            ->setMetadataDriverFactory(new CallbackDriverFactory(function() use ($profileFactory) {
                return new ProfileMetadataDriver($profileFactory);
            }))
            ->build();
    }

    /**
     * @param ProfileInterface $profile
     *
     * @return string
     * @throws SerializerException
     */
    public function serialize(ProfileInterface $profile)
    {
        try {
            return $this->serializer->serialize($profile, $this->type);
        } catch(UnsupportedFormatException $ufe) {
            throw new SerializerException($ufe->getMessage());
        }
    }

    /**
     * @param string $data
     *
     * @throws SerializerException
     * @return ProfileInterface
     */
    public function unserialize($data)
    {
        try {
            return $this->serializer->deserialize($data, $this->profileFactory->getClassName(), $this->type);
        } catch(UnsupportedFormatException $ufe) {
            throw new SerializerException($ufe->getMessage());
        }

    }
}

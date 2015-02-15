<?php

/**
 * PersistenceHandler.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler;

/**
 * Abstract PersistenceHandler should be used as a base for all implementations
 *
 * @package Link0\Profiler
 */
abstract class PersistenceHandler
{
    /**
     * @var ProfileFactoryInterface
     */
    private $profileFactory;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Constructor
     */
    public function __construct(ProfileFactoryInterface $profileFactory = null, SerializerInterface $serializer = null)
    {
        if($profileFactory === null) {
            $profileFactory = new ProfileFactory();
        }

        if ($serializer === null) {
            $serializer = new Serializer();
        }

        $this->profileFactory = $profileFactory;
        $this->serializer = $serializer;
    }

    /**
     * @param ProfileFactoryInterface $profileFactoryInterface
     */
    public function setProfileFactory(ProfileFactoryInterface $profileFactoryInterface)
    {
        $this->profileFactory = $profileFactoryInterface;
    }

    /**
     * @return ProfileFactoryInterface
     */
    public function getProfileFactory()
    {
        return $this->profileFactory;
    }

    /**
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @return SerializerInterface
     */
    public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * @param array $profileData
     *
     * @return ProfileInterface
     */
    public function createProfileFromProfileData($profileData)
    {
        return $this->getProfileFactory()->fromArray($this->getSerializer()->unserialize($profileData));
    }

    /**
     * Returns a list of Identifier strings
     * Unfortunately the list() method is reserved
     *
     * @return string[]
     */
    abstract public function getList();

    /**
     * @param  string       $identifier
     * @return Profile|null $profile
     */
    abstract public function retrieve($identifier);

    /**
     * @param  ProfileInterface            $profile
     * @return PersistenceHandlerInterface $this
     */
    abstract public function persist(ProfileInterface $profile);
}

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
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Constructor
     *
     * @param null|SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer = null)
    {
        if($serializer === null) {
            $profileFactory = new ProfileFactory();
            $serializer = new Serializer($profileFactory);
        }
        $this->serializer = $serializer;
    }

    /**
     * @param ProfileInterface $profile
     *
     * @return string
     */
    protected function serialize(ProfileInterface $profile)
    {
        return $this->serializer->serialize($profile);
    }

    /**
     * @param string $content
     *
     * @return ProfileInterface
     */
    protected function unserialize($content)
    {
        return $this->serializer->unserialize($content);
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

<?php

/**
 * Profile.php
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler;

use Rhumsaa\Uuid\Uuid;

/**
 * Profile model encapsulates a profiled run
 *
 * @package Link0\Profiler
 */
final class Profile
{
    /**
     * @var string $identifier Usually a UUIDv4 string
     */
    private $identifier;
    private $serverData = array();
    private $applicationData = array();
    private $profileData = array();

    /**
     * @param string|null $identifier If null is given, a UUIDv4 will be generated
     */
    public function __construct($identifier = null)
    {
        if ($identifier === null) {
            $identifier = (string) Uuid::uuid4();
        }
        $this->identifier = $identifier;
    }

    /**
     * @param  string  $identifier
     *
     * @return Profile $this
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return string $identifier
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param  array   $serverData
     *
     * @return Profile $this
     */
    public function setServerData($serverData)
    {
        $this->serverData = $serverData;

        return $this;
    }

    /**
     * @return array $serverData
     */
    public function getServerData()
    {
        return $this->serverData;
    }

    /**
     * @param array $applicationData
     *
     * @return Profile $this
     */
    public function setApplicationData($applicationData)
    {
        $this->applicationData = $applicationData;

        return $this;
    }

    /**
     * @return array $applicationData
     */
    public function getApplicationData()
    {
        return $this->applicationData;
    }

    /**
     * @param array $profileData
     *
     * @return Profile $this
     */
    public function setProfileData($profileData)
    {
        $this->profileData = $profileData;

        return $this;
    }

    /**
     * @return array $profileData
     */
    public function getProfileData()
    {
        return $this->profileData;
    }
}

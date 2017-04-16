<?php declare(strict_types=1);

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
final class Profile implements ProfileInterface
{
    /**
     * @var string $identifier Usually a UUIDv4 string
     */
    private $identifier;

    /**
     * @var array $serverData
     */
    private $serverData = array();

    /**
     * @var array $applicationData
     */
    private $applicationData = array();

    /**
     * @var array $profileData
     */
    private $profileData = array();

    /**
     * @param string|null $identifier If null is given, a UUIDv4 will be generated
     */
    private function __construct($identifier = null)
    {
        if ($identifier === null) {
            $identifier = (string) Uuid::uuid4();
        }
        $this->identifier = $identifier;
    }

    /**
     * @param  string  $identifier
     *
     * @return ProfileInterface $this
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
     * @return ProfileInterface $this
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
     * @return ProfileInterface $this
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
     * @return ProfileInterface $this
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

    /**
     * Returns an array representation of this object.
     * This array representation is also used for persistence.
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'identifier'      => $this->getIdentifier(),
            'profileData'     => $this->getProfileData(),
            'applicationData' => $this->getApplicationData(),
            'serverData'      => $this->getServerData(),
        );
    }

    /**
     * @param array $arrayData
     * @return ProfileInterface $profile
     */
    public static function fromArray($arrayData)
    {
        $profile = self::create($arrayData['identifier']);
        $profile->setProfileData($arrayData['profileData']);
        $profile->setApplicationData($arrayData['applicationData']);
        $profile->setServerData($arrayData['serverData']);

        return $profile;
    }

    /**
     * @param string|null $identifier
     *
     * @return ProfileInterface
     */
    public static function create($identifier = null)
    {
        return new self($identifier);
    }
}

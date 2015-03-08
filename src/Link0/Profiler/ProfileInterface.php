<?php

/**
 * ProfileInterface.php
 */
namespace Link0\Profiler;

/**
 * Interface ProfileInterface
 *
 * @author Dennis de Greef <github@link0.net>
 */
interface ProfileInterface
{
    /**
     * @param  string  $identifier
     *
     * @return ProfileInterface $this
     */
    public function setIdentifier($identifier);

    /**
     * @return string $identifier
     */
    public function getIdentifier();

    /**
     * @param  array   $serverData
     *
     * @return ProfileInterface $this
     */
    public function setServerData($serverData);

    /**
     * @return array $serverData
     */
    public function getServerData();

    /**
     * @param array $applicationData
     *
     * @return ProfileInterface $this
     */
    public function setApplicationData($applicationData);

    /**
     * @return array $applicationData
     */
    public function getApplicationData();

    /**
     * @param array $profileData
     *
     * @return ProfileInterface $this
     */
    public function setProfileData($profileData);

    /**
     * @return array $profileData
     */
    public function getProfileData();

    /**
     * Returns an array representation of this object.
     * This array representation is also used for persistence.
     *
     * @return array
     */
    public function toArray();

    /**
     * @param array $arrayData
     * @return ProfileInterface $profile
     */
    public static function fromArray($arrayData);

    /**
     * @param string|null $identifier
     *
     * @return ProfileInterface
     */
    public static function create($identifier = null);
}

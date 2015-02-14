<?php

/**
 * ProfileFactoryInterface.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler;

/**
 * Profile factory interface for type-hinting a profile factory
 *
 * @package Link0\Profiler
 */
interface ProfileFactoryInterface
{
    /**
     * @param array $profileData
     * @param array $applicationData OPTIONAL
     * @param array $serverData      OPTIONAL
     *
     * @return Profile
     */
    public function create($profileData, $applicationData = array(), $serverData = array());

    /**
     * @param array $array
     *
     * @return mixed
     */
    public function fromArray($array);

    /**
     * @param string $serializedData
     *
     * @return Profile
     */
    public function fromSerializedData($serializedData);
}

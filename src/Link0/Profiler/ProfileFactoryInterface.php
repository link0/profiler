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
     * @param array $profileData     OPTIONAL
     * @param array $applicationData OPTIONAL
     * @param array $serverData      OPTIONAL
     *
     * @return ProfileInterface
     */
    public function create($profileData = array(), $applicationData = array(), $serverData = array());

    /**
     * @param array $array
     *
     * @return ProfileInterface
     */
    public function fromArray($array);
}

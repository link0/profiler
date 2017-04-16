<?php declare(strict_types=1);

/**
 * ProfileFactory.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler;

/**
 * Profile factory
 *
 * @package Link0\Profiler
 */
final class ProfileFactory implements ProfileFactoryInterface
{
    /**
     * @param array  $profileData
     * @param array  $applicationData OPTIONAL
     * @param array  $serverData      OPTIONAL
     *
     * @return ProfileInterface
     */
    public function create($profileData = array(), $applicationData = array(), $serverData = array())
    {
        $profile = Profile::create();
        $profile->setProfileData($profileData);
        $profile->setApplicationData($applicationData);
        $profile->setServerData($serverData);

        return $profile;
    }

    /**
     * @param array $array
     *
     * @return ProfileInterface
     */
    public function fromArray($array)
    {
        return Profile::fromArray($array);
    }
}

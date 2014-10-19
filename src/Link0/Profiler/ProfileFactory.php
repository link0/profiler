<?php

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
     * @param null|array $data
     * @return Profile
     */
    public function create($data = null)
    {
        $profile = new Profile();

        if($data !== null) {
            $profile->loadData($data);
        }

        return $profile;
    }
}
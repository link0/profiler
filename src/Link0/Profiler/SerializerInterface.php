<?php

/**
 * SerializerInterface.php
 */
namespace Link0\Profiler;

/**
 * Interface SerializerInterface
 *
 * @package Link0\Profiler
 * @author  Dennis de Greef <github@link0.net>
 */
interface SerializerInterface
{
    /**
     * @param ProfileInterface $profile
     * @return string
     */
    public function serialize(ProfileInterface $profile);

    /**
     * @param string $data
     * @return ProfileInterface
     */
    public function unserialize($data);
}

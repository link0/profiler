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
     * @param null|array $data
     * @return Profile
     */
    public function create($data = null);
}
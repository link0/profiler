<?php declare(strict_types=1);

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
     * @param mixed $data
     * @return string
     */
    public function serialize($data);

    /**
     * @param string $data
     * @return mixed
     */
    public function unserialize($data);
}

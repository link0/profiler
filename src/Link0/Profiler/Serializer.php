<?php

/**
 * Serializer.php
 */
namespace Link0\Profiler;

/**
 * Serializer implementation for PHPs serialize() and unserialize() functions
 *
 * @author Dennis de Greef <github@link0.net>
 */
final class Serializer implements SerializerInterface
{
    /**
     * @param mixed $data
     * @return string
     */
    public function serialize($data)
    {
        return serialize($data);
    }

    /**
     * @param string $data
     * @return mixed
     */
    public function unserialize($data)
    {
        return unserialize($data);
    }
}

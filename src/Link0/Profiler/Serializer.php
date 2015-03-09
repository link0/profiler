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
     * @param string $data
     * @return string
     */
    public function serialize($data)
    {
        return serialize($data);
    }

    /**
     * @param string $data
     *
     * @throws SerializerException
     * @return string
     */
    public function unserialize($data)
    {
        $object = @unserialize($data);
        if($object === false) {
            throw new SerializerException("Unable to unserialize data: " . $data);
        }

        return $object;
    }
}

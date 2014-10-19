<?php

/**
 * Profile.php
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler;

use Rhumsaa\Uuid\Uuid;

/**
 * Profile model encapsulates a profiled run
 *
 * @package Link0\Profiler
 */
final class Profile
{
    /**
     * @var string $identifier Usually a UUIDv4 string
     */
    protected $identifier;

    /**
     * @var FunctionCall[] $functionCalls
     */
    protected $functionCalls = array();

    /**
     * @param string|null $identifier If null is given, a UUIDv4 will be generated
     */
    public function __construct($identifier = null)
    {
        if ($identifier === null) {
            $identifier = (string) Uuid::uuid4();
        }
        $this->identifier = $identifier;
    }

    /**
     * @param  string  $identifier
     * @return Profile $this
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return string $identifier
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Adds a function call to this profile
     *
     * @param  FunctionCall $functionCall
     * @return Profile      $this
     */
    public function addFunctionCall(FunctionCall $functionCall)
    {
        $this->functionCalls[] = $functionCall;

        return $this;
    }

    /**
     * @return FunctionCall[] $functionCalls
     */
    public function getFunctionCalls()
    {
        return $this->functionCalls;
    }

    /**
     * Creates a Profiles filled with FunctionCall objects from a data-array given by the profiler itself
     *
     * @param  array   $profilerDatas
     * @return Profile
     */
    public function loadData($profilerDatas)
    {
        foreach ($profilerDatas as $functionTransition => $profilerData) {
            $this->addFunctionCall($this->createFunctionCallFromData($functionTransition, $profilerData));
        }

        return $this;
    }

    /**
     * @param string $functionTransition
     * @param array $profilerData
     * @return FunctionCall
     */
    private function createFunctionCallFromData($functionTransition, $profilerData)
    {
        $parts = explode('==>', $functionTransition);

        $caller = $parts[0];
        $functionName = '';
        if(isset($parts[1])) {
            $functionName = $parts[1];
        }

        return $this->loadFunctionCallData($functionName, $caller, $profilerData);
    }

    /**
     * Creates a FunctionCall object based upon profiler data
     *
     * @param  string       $functionName
     * @param  string       $caller
     * @param  array        $profilerData
     * @return FunctionCall
     */
    protected function loadFunctionCallData($functionName, $caller, $profilerData)
    {
        return new FunctionCall(
            $functionName,
            $caller,
            $profilerData['ct'],
            $profilerData['wt'],
            $profilerData['cpu'],
            $profilerData['mu'],
            $profilerData['pmu']
        );
    }

    /**
     * @return array $data
     */
    public function toData()
    {
        $data = array();
        foreach ($this->getFunctionCalls() as $functionCall) {
            $data[] = $functionCall->toData();
        }

        return $data;
    }
}

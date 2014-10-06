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
        if($identifier === null) {
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
     * @param FunctionCall $functionCall
     * @return Profile $this
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
     * @param  array $profilerDatas
     * @param  bool  $shouldClearObject OPTIONAL
     * @return Profile
     */
    public function loadData($profilerDatas, $shouldClearObject = true)
    {
        if($shouldClearObject) {
            $this->functionCalls = array();
        }

        foreach($profilerDatas as $functionTransition => $profilerData) {
            $functionTransitionParts = explode("==>", $functionTransition);
            $caller       = isset($functionTransitionParts[0]) ? $functionTransitionParts[0] : '';
            $functionName = isset($functionTransitionParts[1]) ? $functionTransitionParts[1] : '';

            $functionCall = $this->loadFunctionCallData($functionName, $caller, $profilerData);
            $this->addFunctionCall($functionCall);
        }
        return $this;
    }

    /**
     * Creates a FunctionCall object based upon profiler data
     *
     * @param string $functionName
     * @param string $caller
     * @param array  $profilerData
     * @return FunctionCall
     */
    protected function loadFunctionCallData($functionName, $caller, $profilerData)
    {
        return new FunctionCall(
            $functionName,
            $caller,
            isset($profilerData['ct'])  ? $profilerData['ct']  : 0,
            isset($profilerData['wt'])  ? $profilerData['wt']  : 0,
            isset($profilerData['cpu']) ? $profilerData['cpu'] : 0,
            isset($profilerData['mu'])  ? $profilerData['mu']  : 0,
            isset($profilerData['pmu']) ? $profilerData['pmu'] : 0
        );
    }

    /**
     * @return array $data
     */
    public function toData()
    {
        $data = array();
        foreach($this->getFunctionCalls() as $functionCall) {
            $data[] = $functionCall->toData();
        }
        return $data;
    }
}
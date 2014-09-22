<?php

namespace Link0\Profiler;

use Rhumsaa\Uuid\Uuid;

class Profile
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
     * Creates a Profiles filled with FunctionCall objects from a data-array given by xhprof itself
     *
     * @param  array $xhprofDatas
     * @return Profile
     */
    public static function fromData($xhprofDatas)
    {
        $self = new self();
        foreach($xhprofDatas as $functionTransition => $xhprofData) {
            @list($caller, $functionName) = explode("==>", $functionTransition);

            $functionCall = new FunctionCall(
                $functionName,
                $caller,
                (isset($xhprofData['ct'])  ? $xhprofData['ct']  : 0),
                (isset($xhprofData['wt'])  ? $xhprofData['wt']  : 0),
                (isset($xhprofData['cpu']) ? $xhprofData['cpu'] : 0),
                (isset($xhprofData['mu'])  ? $xhprofData['mu']  : 0),
                (isset($xhprofData['pmu']) ? $xhprofData['pmu'] : 0)
            );
            $self->addFunctionCall($functionCall);
        }
        return $self;
    }
}
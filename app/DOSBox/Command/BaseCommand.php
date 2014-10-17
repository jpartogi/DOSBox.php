<?php

namespace DOSBox\Command;

use DOSBox\Interfaces\IDrive;
use DOSBox\Interfaces\IOutputter;

abstract class BaseCommand {
    protected $commandName;
    protected $drive;
    protected $params = array();

    const INCORRECT_SYNTAX = "The syntax of the command is incorrect.";
    const DEFAULT_ERROR_MESSAGE_WRONG_PARAMETER = "Wrong parameter entered.";

    public function __construct($commandName, IDrive $drive){
        $this->commandName = $commandName;
        $this->drive = $drive;
    }

    protected abstract function execute(IOutputter $outputter);

    protected abstract function checkNumberOfParameters($numberOfParametersEntered);

    protected abstract function checkParameterValues(IOutputter $outputter);

    public final function checkParameters(IOutputter $outputter){ // TODO: throws exception
        if(!$this->checkNumberOfParameters($this->getParameterCount())){
            $outputter->printLine(self::INCORRECT_SYNTAX);
            return false;
        }

        if(!$this->checkParameterValues($outputter)) {
            if (!$outputter->hasCharactersPrinted())
                $outputter->printLine(self::DEFAULT_ERROR_MESSAGE_WRONG_PARAMETER);

            return false;
        }

        return true;
    }

    public function compareCmdName($commandName){
        return ($commandName === $this->commandName);
    }

    public function setParams($params){
        $this->params = $params;
    }

    protected function getParameterCount(){
        return count($this->params);
    }

    protected function getParameterAt($parameterIndex){
        if($parameterIndex < 0 || $parameterIndex >= sizeof($this->params)) throw new Exception("Index out of range.");

        return $this->params[$parameterIndex];
    }

    protected function getDrive(){
        return $this->drive;
    }

    public function getCommandName(){
        return $this->commandName;
    }
}
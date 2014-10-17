<?php

namespace DOSBox\Command;

use DOSBox\Interfaces\IExecuteCommand;
use DOSBox\Interfaces\IOutputter;

class CommandInvoker implements IExecuteCommand {
    private $commands = array();

    public function __construct(){}

    public function setCommands($commands){
        $this->commands = $commands;
    }

    public function addCommand($command){
        array_push($this->commands, $command);
    }

    public function getCommands(){
        return $this->commands;
    }

    public function executeCommand($command, IOutputter $outputter) {
        $cmdName = $this->parseCommandName($command);
        $params = $this->parseCommandParams($command);

        try{
            foreach($this->commands as $cmd){
                if($cmd->compareCmdName($cmdName)){
                    $cmd->setParams($params);

                    if($cmd->checkParameters($outputter) == false) {
                        $outputter->printLine("Wrong parameter entered.");
                        return;
                    }

                    $cmd->execute($outputter);
                    return;
                }
            }
            $outputter->printLine("'{$command}' is not recognized as an internal or external command, operable program or batch file.");
        } catch(Exception $e){
            if($e->getMessage() != null) {
                $outputter->printLine("Unexpected exception while execution command: " . $e->getMessage());
            }
            else {
                $outputter->printLine("Unknown exception caught");
                $outputter->printLine($e->toString());
                $e->printStackTrace();
            }
        }
    }

    public function parseCommandName($command){
        $cmd = strtolower($command);
        $cmdName = NULL;

        $cmd = trim($cmd);
        $cmd = str_replace(",", " ", $cmd);
        $cmd = str_replace(";", " ", $cmd);

        $cmdName = $cmd;
        for($i=0; $i < strlen($cmd); $i++){
            if($cmd[$i] === ' '){
                $cmdName = substr($cmd, 0, $i);
                break;
            }
        }

        return $cmdName;
    }

    public function extractCommandParams($command){
        $params = trim(substr($command, strlen($this->parseCommandName($command)), strlen($command)));
        return $params;
    }

    public function parseCommandParams($command) {
        $params = array();
        $str_params = $this->extractCommandParams($command);

        $str_params = trim($str_params);
        $str_params = str_replace(",", " ", $str_params);
        $str_params = str_replace(";", " ", $str_params);

        $tmp_params = array();
        if(!empty($str_params))
            $tmp_params = explode(" ", $str_params);

        foreach($tmp_params as $param){
            if(!empty($param)) array_push($params, trim($param));
        }

        return $params;
    }
}
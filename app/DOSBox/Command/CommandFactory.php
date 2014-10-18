<?php

namespace DOSBox\Command;

use DOSBox\Interfaces\IDrive;
use DOSBox\Command\Library\CmdCd as CmdCd;
use DOSBox\Command\Library\CmdDir as CmdDir;
use DOSBox\Command\Library\CmdMkDir as CmdMkDir;
use DOSBox\Command\Library\CmdMkFile as CmdMkFile;

class CommandFactory {
    private $commands = array();

    public function __construct(IDrive $drive){
        array_push($this->commands, new CmdDir("dir", $drive));
        array_push($this->commands, new CmdCd("cd", $drive));
        array_push($this->commands, new CmdCd("chdir", $drive));
        array_push($this->commands, new CmdMkDir("mkdir", $drive));
        array_push($this->commands, new CmdMkDir("md", $drive));
        array_push($this->commands, new CmdMkFile("mkfile", $drive));
        array_push($this->commands, new CmdMkFile("mf", $drive));

        // Add your commands here
    }

    public function getCommands(){
        return $this->commands;
    }
}
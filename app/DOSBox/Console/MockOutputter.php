<?php

namespace DOSBox\Console;

class MockOutputter extends ConsoleOutputter {
    private $output = "";

    public function printLine($text){
        $this->output .= $text;
        $this->analyzePrintedCharacters($text);
    }

    public function printNoLine($text) {
        $this->output .= $text;
        $this->analyzePrintedCharacters($text);
    }

    public function newLine(){
        // do nothing
    }

    public function getOutput(){
        return $this->output;
    }

    public function _empty(){
        $this->output = "";
    }

} 
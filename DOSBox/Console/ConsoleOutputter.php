<?php

namespace DOSBox\Console;

use DOSBox\Interfaces\IOutputter;

class ConsoleOutputter implements IOutputter {
    private $numberOfPrintedCharacters;

    public function printLine($text){
        print $text . "\n" ;
        $this->analyzePrintedCharacters($text);
    }

    public function printNoLine($text){
        print $text;
        $this->analyzePrintedCharacters($text);
    }

    public function newLine(){
        print "\n";
    }

    public function readSingleCharacter() {
        $readChar = 0;
        // TODO: Unit test this??
        while ($in = fread(STDIN, 1)) {
            if($in != '\n' && $in != '\r')  // do not consider \r and \n
                $readChar = $in;
        }

        return $readChar;
    }

    public function numberOfCharactersPrinted() {
        return $this->numberOfPrintedCharacters;
    }

    public function hasCharactersPrinted() {
        return $this->numberOfPrintedCharacters > 0;
    }

    public function resetStatistics() {
        $this->numberOfPrintedCharacters = 0;
    }

    protected function analyzePrintedCharacters($printedString){
        $tempString = trim($printedString);
        $this->numberOfPrintedCharacters += strlen($tempString);
    }
}
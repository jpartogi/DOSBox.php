<?php

namespace DOSBox\Interfaces;

interface IOutputter {
    public function printLine($text);

    public function printNoLine($text);

    public function newLine();

    public function readSingleCharacter();

    public function numberOfCharactersPrinted();

    public function hasCharactersPrinted();

    public function resetStatistics();
}
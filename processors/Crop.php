<?php

namespace app\processors;

class Crop extends Processor
{
    public string $id = 'crop';


    public function execute(): bool
    {
        return true;
    }
}

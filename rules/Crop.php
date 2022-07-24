<?php

namespace app\rules;

class Crop extends Rule
{
    public string $name = 'crop';


    public function execute(): bool
    {
        return true;
    }
}

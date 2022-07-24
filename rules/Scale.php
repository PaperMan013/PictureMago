<?php

namespace app\rules;

class Scale extends Rule
{
    public string $name = 'small';
    public int $maxWidth = 300;
    public int $maxHeight = 300;
    public bool $allowUpscale = false;


    public function execute(): bool
    {
        return true;
    }
}

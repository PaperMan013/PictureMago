<?php

namespace app\rules;

class Original extends Rule
{
    public string $name = 'original';


    public function execute(): bool
    {
        return true;
    }
}

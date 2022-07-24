<?php

namespace app\interfaces;

interface RuleInterface
{
    public function getName(): string;

    public function execute(): bool;
}

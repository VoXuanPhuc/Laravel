<?php

namespace Encoda\Core\AppContext;

abstract class ContextBase
{
    abstract public function get();
    abstract public function check(): bool;
}

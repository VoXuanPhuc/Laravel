<?php

namespace Encoda\Core\AppContext;

use Encoda\Core\Exceptions\ServerErrorException;
use Illuminate\Support\Arr;

class ContextManager
{
    /**
     * @var array
     */
    private array $contexts;

    /**
     * @param array $contexts
     * @return void
     */
    public function __construct(array $contexts)
    {
        $this->contexts = $contexts;
    }

    /**
     * Return the Context
     *
     * @param string $key
     *
     * @return \Laravel\Lumen\Application|mixed
     * @throws ServerErrorException
     */
    public function get(string $key): mixed
    {
        $context = Arr::get($this->contexts, $key);
        if ($context) {
            return app($context);
        }

        throw new ServerErrorException(sprintf('%s is not a valid Context', $key));
    }


    /**
     * @return RequestContext
     * @throws ServerErrorException
     */
    public function request(): RequestContext
    {
        return $this->get('request');
    }
}

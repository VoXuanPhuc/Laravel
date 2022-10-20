<?php

namespace Encoda\EasyLog\Resolvers;

use Closure;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Eloquent\Model;
use Encoda\EasyLog\Exceptions\CouldNotLogAction;
use Throwable;

class CauserResolver
{
    protected AuthManager $authManager;

    protected string | null $authDriver;

    protected Closure | null $resolverOverride = null;

    protected Model | null $causerOverride = null;

    /**
     * @param Repository $config
     * @param AuthManager $authManager
     */
    public function __construct(Repository $config, AuthManager $authManager)
    {
        $this->authManager = $authManager;

        $this->authDriver = $config['easylog']['default_auth_driver'];
    }

    /**
     * @throws Throwable
     * @throws CouldNotLogAction
     */
    public function resolve(Model | int | string | null $subject = null): ?Model
    {
        if ($this->causerOverride !== null) {
            return $this->causerOverride;
        }

        if ($this->resolverOverride !== null) {
            $resultCauser = ($this->resolverOverride)($subject);

            if (! $this->isResolvable($resultCauser)) {
                throw CouldNotLogAction::couldNotDetermineUser($resultCauser);
            }

            return $resultCauser;
        }

        return $this->getCauser($subject);
    }

    /**
     * @throws Throwable
     */
    protected function resolveUsingId(int | string $subject): Model
    {
        $guard = $this->authManager->guard($this->authDriver);

        $provider = method_exists($guard, 'getProvider') ? $guard->getProvider() : null;
        $model = method_exists($provider, 'retrieveById') ? $provider->retrieveById($subject) : null;

        throw_unless($model instanceof Model, CouldNotLogAction::couldNotDetermineUser($subject));

        return $model;
    }

    /**
     * @throws Throwable
     */
    protected function getCauser(Model | int | string | null $subject = null)
    {
        if ($subject instanceof Model) {
            return $subject;
        }

        if (is_null($subject)) {
            return $this->getDefaultCauser();
        }

        return $this->resolveUsingId($subject);
    }

    /**
     * Override the resolver using callback.
     */
    public function resolveUsing(Closure $callback): static
    {
        $this->resolverOverride = $callback;

        return $this;
    }

    /**
     * Override default causer.
     */
    public function setCauser(?Model $causer): static
    {
        $this->causerOverride = $causer;

        return $this;
    }

    /**
     * @param mixed $model
     * @return bool
     */
    protected function isResolvable(mixed $model): bool
    {
        return $model instanceof Model || is_null($model);
    }

    /**
     * @return Authenticatable
     */
    protected function getDefaultCauser(): Authenticatable
    {
        return $this->authManager->guard($this->authDriver)->user();
    }
}

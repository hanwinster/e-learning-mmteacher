<?php

namespace App\Providers;

use Laravel\Horizon\Horizon;
use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\HorizonApplicationServiceProvider;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // Horizon::routeSmsNotificationsTo('15556667777');
        // Horizon::routeMailNotificationsTo('example@example.com');
        // Horizon::routeSlackNotificationsTo('slack-webhook-url', '#channel');

        // Horizon::night();

        Horizon::auth(function ($request) {
            if (!$request->user()) {
                throw new UnauthorizedHttpException('Unauthorized');
            } elseif (!$request->user()->isAdmin()) {
                throw new UnauthorizedHttpException('Unauthorized');
            }

            return true;
        });
    }

    /**
     * Register the Horizon gate.
     *
     * This gate determines who can access Horizon in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewHorizon', function ($user) {
            $type = null;

            if (isset($user->type)) {
                $type = $user->type;
            }

            return in_array($type, [
                App\User::TYPE_ADMIN
            ]);
        });
    }
}

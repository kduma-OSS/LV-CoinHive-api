<?php

namespace KDuma\CoinHive\Laravel;

use Illuminate\Support\ServiceProvider;
use KDuma\CoinHive\CoinHiveApi;
use KDuma\CoinHive\CoinHiveCaptchaDisplayer;

class CoinHiveServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $app = $this->app;

        $this->loadTranslationsFrom(__DIR__.'/../../translations/', 'coinhive');

        $app['validator']->extend('coinhive_captcha_token', function ($attribute, $value, $parameters) use ($app) {
            return $app[CoinHiveApi::class]->verifyToken($value, isset($parameters[0]) ? $parameters[0] : config('services.coinhive.default_hashes'))['success'];
        }, trans('coinhive::messages.invalid_captcha'));

        if ($app->bound('form')) {
            $app['form']->macro('CoinHiveCaptcha', function ($required_hashes = null, $attributes = []) use ($app) {
                return $app[CoinHiveCaptchaDisplayer::class]->display($required_hashes, $attributes);
            });
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CoinHiveApi::class, function ($app) {
            return new CoinHiveApi(config('services.coinhive.site_key'), config('services.coinhive.secret_key'));
        });
        $this->app->singleton(CoinHiveCaptchaDisplayer::class, function ($app) {
            return new CoinHiveCaptchaDisplayer(config('services.coinhive.site_key'), config('services.coinhive.default_hashes'), config('services.coinhive.use_authedmine_url', true));
        });
    }
}

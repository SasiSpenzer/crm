<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LpwAdminServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {
		//
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		$this->app->bind('App\Contracts\AdvertisementInterface', 'App\Repositories\EloquentAdvertisementRepository');
		$this->app->bind('App\Contracts\MemberInterface', 'App\Repositories\EloquentMemberRepository');
		$this->app->bind('App\Contracts\CustomerInterface', 'App\Repositories\EloquentCustomerRepository');
		$this->app->bind('App\Contracts\DashboardInterface', 'App\Repositories\EloquentDashboardRepository');
		$this->app->bind('App\Contracts\MemberActionInterface', 'App\Repositories\EloquentMemberActionRepository');
	}
}

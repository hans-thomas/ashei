<?php


	namespace Hans\Ashei;


	use Illuminate\Support\ServiceProvider;

	class AsheiServiceProvider extends ServiceProvider {

		/**
		 * Register any application services.
		 *
		 * @return void
		 */
		public function register() {
			//
		}

		/**
		 * Bootstrap any application services.
		 *
		 * @return void
		 */
		public function boot() {
			$this->mergeConfigFrom( __DIR__ . '/../config/config.php', 'ashei' );

			if ( $this->app->runningInConsole() ) {
				$this->registerPublishes();
			}
		}

		/**
		 * Register publishable files
		 *
		 * @return void
		 */
		private function registerPublishes() {
			$this->publishes( [
				__DIR__ . '/../config/config.php' => config_path( 'ashei.php' )
			], 'ashei-config' );
		}

	}

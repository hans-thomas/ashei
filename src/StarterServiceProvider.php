<?php


	namespace Hans\Starter;


	use Illuminate\Support\Facades\Route;
	use Illuminate\Support\ServiceProvider;

	class StarterServiceProvider extends ServiceProvider {
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
			$this->loadMigrationsFrom( __DIR__ . '/../database/migrations' );
			$this->mergeConfigFrom( __DIR__ . '/../config/config.php', 'starter' );

			$this->registerRoutes();
			if ( $this->app->runningInConsole() ) {
				$this->registerCommands();
				$this->registerPublishes();
			}
		}

		/**
		 * Define routes setup.
		 *
		 * @return void
		 */
		private function registerRoutes() {
			Route::prefix( 'starter' )->middleware( 'api' )->group( __DIR__ . '/../routes/api.php' );
		}

		/**
		 * Register created commands
		 *
		 * @return void
		 */
		private function registerCommands() {
			$this->commands( [
				// commands register here
			] );
		}

		/**
		 * Register publishable files
		 *
		 * @return void
		 */
		private function registerPublishes() {
			$this->publishes( [
				__DIR__ . '/../config/config.php' => config_path( 'starter.php' )
			], 'starter-config' );
		}

	}

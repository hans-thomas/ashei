<?php

	namespace Hans\Ashei\Facades;

	use Hans\Ashei\Services\AsheiService;
	use Illuminate\Support\Facades\Facade;
	use RuntimeException;

	/**
	 * @method static read( string $book )
	 * @see AsheiService
	 */
	class Ashei extends Facade {
		/**
		 * Get the registered name of the component.
		 *
		 * @return string
		 *
		 * @throws RuntimeException
		 */
		protected static function getFacadeAccessor() {
			return 'ashei-service';
		}


	}
<?php

	namespace Hans\Ashei\Tests\Feature;

	use Hans\Ashei\Facades\Ashei;
	use Hans\Ashei\Tests\TestCase;

	class EpubTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function read() {
			$file = realpath( __DIR__ . '/../resources/the-demon-girl.epub' );
			self::assertEquals(
				require __DIR__ . '/../resources/content.php',
				Ashei::read( $file )
			);
		}

	}

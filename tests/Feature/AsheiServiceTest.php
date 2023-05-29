<?php

	namespace Hans\Ashei\Tests\Feature;

	use Hans\Ashei\Facades\Ashei;
	use Hans\Ashei\Tests\TestCase;

	class AsheiServiceTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function read() {
			$file = realpath( __DIR__ . '/../resources/the-demon-girl-cropped.epub' );
			self::assertEquals(
				require __DIR__ . '/../resources/chapter-one.php',
				Ashei::read( $file )
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function iterator() {
			$file = realpath( __DIR__ . '/../resources/the-demon-girl-full.epub' );

			foreach ( Ashei::iterator( $file ) as $number => $page ) {
				$data[] = $page;
				if ( $number > 1 ) {
					break;
				}
			}

			self::assertEquals(
				require __DIR__ . '/../resources/first-page.php',
				$data[ 0 ]
			);
			self::assertEquals(
				require __DIR__ . '/../resources/chapter-one.php',
				$data[ 1 ]
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function setParagraphLength(): void {
			$file = realpath( __DIR__ . '/../resources/the-demon-girl-cropped.epub' );

			self::assertEquals(
				require __DIR__ . '/../resources/chapter-one-50-paragraph-length.php',
				Ashei::setParagraphLength( 50 )->read( $file )
			);
		}


	}

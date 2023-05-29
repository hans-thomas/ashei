<?php

	namespace Hans\Ashei\Services;

	use Epubli\Epub\Epub;
	use Epubli\Exception\Exception;
	use Generator;
	use Illuminate\Support\Arr;
	use Illuminate\Support\Str;

	class AsheiService {

		/**
		 * Read whole book at once
		 *
		 * @param string $book
		 *
		 * @return array
		 * @throws Exception
		 */
		public function read( string $book ): array {
			// setting up
			$epub    = new Epub( $book );
			$content = null;
			foreach ( $epub->getSpine() as $index => $spine ) {
				// find titles
				$titles = $this->extractTitles( $spine );
				// parse contents
				$text = [];
				foreach ( Arr::wrap( $spine->getContents() ) as $item ) {
					$data = $this->extractText( $item, $titles );
					$text = array_merge( $text, $data );
				}
				if ( empty( $text ) ) {
					continue;
				}
				$content[ $index ] = $text;
				$spine->close();
			}

			// put together
			return $this->makeResult( $content );
		}

		/**
		 * Read one section at each iterate
		 *
		 * @param string $book
		 *
		 * @return Generator
		 * @throws Exception
		 */
		public function iterator( string $book ): Generator {
			// setting up
			$epub    = new Epub( $book );
			$content = null;
			foreach ( $epub->getSpine() as $index => $spine ) {
				// find titles
				$titles = $this->extractTitles( $spine->getData() );
				// parse contents
				$text = [];
				foreach ( Arr::wrap( $spine->getContents() ) as $item ) {
					$data = $this->extractText( $item, $titles );
					$text = array_merge( $text, $data );
					if ( empty( $text ) ) {
						continue;
					}
					yield $this->makeResult( [ $index => $text ] );
				}
				$spine->close();
			}

		}

		/**
		 * Extract titles from page content
		 *
		 * @param string $data
		 *
		 * @return array
		 */
		private function extractTitles( string $data ): array {
			// find titles
			$matches   = [];
			$ifMatched = preg_match_all( '/(<h[1-4].*>.*<\/h[1-4]>)+/', $data, $matches );
			$titles    = [];
			if ( $ifMatched ) {
				foreach ( $matches as $match ) {
					foreach ( $match as $item ) {
						$title  = [];
						$titled = preg_match_all( '/\>(.+)<\/+/', $item, $title );
						if ( $titled ) {
							$titles[] = Str::remove( [ "/>", "</" ], $title[ 1 ][ 0 ] );
						}
					}
				}
			}

			return array_unique( $titles );
		}

		/**
		 * Extract text from retrieved content
		 *
		 * @param string|array $content
		 * @param array        $titles
		 *
		 * @return array
		 */
		private function extractText( string|array $content, array $titles ): array {
			$text = Str::replace( [ "\t\t\t\n", "\t\t\n", "\t\t\t", "\t" ], '', $content );
			$text = preg_split( '/(\\n)+/', $text );
			if ( is_array( $text ) ) {
				foreach ( $text as $key => $item ) {
					if ( Str::wordCount( $item ) == 0 ) {
						unset( $text[ $key ] );
						continue;
					}
					// wrap titles with title tag
					if ( in_array( $item, $titles ) ) {
						$text[ $key ] = "<h3>" . $item . "</h3>";
					}
				}
			}

			return $text;
		}

		/**
		 * Chunk content and make final result
		 *
		 * @param $content
		 *
		 * @return array
		 */
		private function makeResult( $content ): array {
			$result    = [];
			$paragraph = '';
			foreach ( $content as $pages ) {
				foreach ( $pages as $index => $page ) {
					$paragraph .= Str::of( $page )->endsWith( "</h3>" ) ?
						$page :
						Str::of( $page )->finish( "<br>" );

					if ( strlen( $paragraph ) <= 2000 and $index < count( $pages ) - 1 ) {
						continue;
					}

					$result[]  = $paragraph;
					$paragraph = '';
				}
			}

			return $result;
		}

	}
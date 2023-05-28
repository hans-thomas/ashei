<?php

	namespace Hans\Ashei\Services;

	use Epubli\Epub\Data\Item;
	use Epubli\Epub\Epub;
	use Epubli\Exception\Exception;
	use Illuminate\Support\Str;

	class AsheiService {

		/**
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
				$text = $this->extractText( $spine, $titles );
				if ( empty( $text ) ) {
					continue;
				}
				$content[ $index ] = $text;
				$spine->close();
			}

			// put together
			return $this->makeResult( $content );
		}

		private function extractTitles( Item $spine ): array {
			// find titles
			$matches   = [];
			$ifMatched = preg_match_all( '/(<h[1-4].*>.*<\/h[1-4]>)+/', $spine->getData(), $matches );
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

			return $titles;
		}

		private function extractText( Item $spine, array $titles ): array {
			$text = $spine->getContents();
			$text = Str::replace( [ "\t\t\t\n", "\t\t\n", "\t\t\t", "\t" ], '', $text );
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

		private function makeResult( $content ): array {
			$result    = [];
			$paragraph = '';
			foreach ( $content as $index => $pages ) {
				foreach ( $pages as $page ) {
					$paragraph .= Str::of( $page )->endsWith( "</h3>" ) ?
						$page :
						Str::of( $page )->finish( "<br>" );

					if ( strlen( $paragraph ) <= 2000 ) {
						continue;
					}

					$result[]  = $paragraph;
					$paragraph = '';
				}
			}

			return $result;
		}

	}
<?php

	namespace Hans\Ashei\Services;

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
				// parse contents
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
					if ( empty( $text ) ) {
						continue;
					}
				} elseif ( is_string( $text ) ) {
					if ( Str::wordCount( $text ) == 0 ) {
						continue;
					}
				}
				$content[ $index ] = $text;
				$spine->close();
			}

			// put together
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
<?php

namespace Hans\Ashei\Facades;

use Hans\Ashei\Services\AsheiService;
use Illuminate\Support\Facades\Facade;
use RuntimeException;

/**
 * @method static read( string $book )
 * @method static iterator( string $book )
 * @method static setParagraphLength( int $paragraph_length )
 *
 * @see AsheiService
 */
class Ashei extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @throws RuntimeException
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ashei-service';
    }
}

<?php

namespace Mvdnbrk\Postmark\Support;

use DateTime;
use Exception;
use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * This file is part of the Postmark Inbound package.
 *
 * @property-read string $timezone
 * @property-read bool $isUtc
 */
class PostmarkDate extends DateTime
{
    /**
     *  Constants for numeric representation of the day of the week.
     */
    public const SUNDAY = 0;
    public const MONDAY = 1;
    public const TUESDAY = 2;
    public const WEDNESDAY = 3;
    public const THURSDAY = 4;
    public const FRIDAY = 5;
    public const SATURDAY = 6;

    /**
     * Textual representation of a day, three letters.
     *
     * @var array<int, string>
     */
    protected static array $days_abbreviated = [
        self::SUNDAY => 'Sun',
        self::MONDAY => 'Mon',
        self::TUESDAY => 'Tue',
        self::WEDNESDAY => 'Wed',
        self::THURSDAY => 'Thu',
        self::FRIDAY => 'Fri',
        self::SATURDAY => 'Sat',
    ];

    /**
     * Get the textual respresentation of a day as three letters.
     *
     * @return array<int, string>
     */
    public static function getAbbreviatedDays(): array
    {
        return static::$days_abbreviated;
    }

    /**
     * Get a collection of the textual respresentation of a day as three letters.
     *
     * @return \Illuminate\Support\Collection<int, string>
     */
    public static function getAbbreviatedDaysCollection(): Collection
    {
        return collect(static::getAbbreviatedDays());
    }

    /**
     * Set the instance to UTC timezone.
     */
    public function inUtcTimezone(): static
    {
        return parent::setTimezone(timezone_open('UTC'));
    }

    /**
     * Create a new instance from a string.
     */
    public static function parse(?string $date): static
    {
        try {
            return new static(
                collect(explode(' ', (string) $date))
                    ->reject(fn (string $value): bool => self::getAbbreviatedDaysCollection()->contains(trim($value, ',')))
                    ->take(5)
                    ->implode(' ')
            );
        } catch (Exception $e) {
            //
        }

        return new static();
    }

    /**
     * Dynamically retrieve attributes on the data model.
     *
     * @throws \InvalidArgumentException
     */
    public function __get(string $key): mixed
    {
        return match ($key) {
            'isUtc' => $this->getOffset() === 0,
            'timezone' => $this->getTimezone()->getName(),
            default => throw new InvalidArgumentException(sprintf("Unknown getter '%s'", $key)),
        };
    }
}

<?php

namespace Mvdnbrk\Postmark\Tests\Support;

use Mvdnbrk\Postmark\Support\PostmarkDate;
use PHPUnit\Framework\TestCase;
use Tightenco\Collect\Support\Collection;

class PostmarkDateTest extends TestCase
{
    /** @test */
    public function can_get_abbreviated_days_as_array()
    {
        $this->assertEquals([
            0 => 'Sun',
            1 => 'Mon',
            2 => 'Tue',
            3 => 'Wed',
            4 => 'Thu',
            5 => 'Fri',
            6 => 'Sat',
        ], PostmarkDate::getAbbreviatedDays());
    }

    /** @test */
    public function can_get_abbreviated_days_as_collection()
    {
        $this->assertInstanceOf(Collection::class, PostmarkDate::getAbbreviatedDaysCollection());
        $this->assertEquals([
            0 => 'Sun',
            1 => 'Mon',
            2 => 'Tue',
            3 => 'Wed',
            4 => 'Thu',
            5 => 'Fri',
            6 => 'Sat',
        ], PostmarkDate::getAbbreviatedDaysCollection()->toArray());
    }

    /** @test */
    public function can_parse_different_date_values_from_postmarks_date_field()
    {
        $this->assertSame('2018-04-27 12:00:00 +0800', PostmarkDate::parse('27 Apr 2018 12:00:00 +0800')->format('Y-m-d H:i:s O'));
        $this->assertSame('2018-04-27 19:00:00 +0200', PostmarkDate::parse('27 Apr 2018 19:00:00 +0200')->format('Y-m-d H:i:s O'));
        $this->assertSame('2018-04-27 19:00:00 +0200', PostmarkDate::parse('Fri, 27 Apr 2018 19:00:00 +0200 (CEST)')->format('Y-m-d H:i:s O'));
        $this->assertSame('2018-02-02 15:00:00 +0300', PostmarkDate::parse('Fri, 2 Feb 2018 15:00:00 +0300 (GMT+03:00)')->format('Y-m-d H:i:s O'));
        $this->assertSame('2017-12-05 19:00:00 +0100', PostmarkDate::parse('Tue, 5 Dec 2017 19:00:00 +0100 (West-Europe')->format('Y-m-d H:i:s O'));
        $this->assertSame('2017-12-05 19:00:00 +0100', PostmarkDate::parse('Tue, 5 Dec 2017 19:00:00 +0100 (West-Europe (stand')->format('Y-m-d H:i:s O'));
    }

    /** @test */
    public function can_get_a_date_in_utc_timezone()
    {
        $date = PostmarkDate::parse('27 Apr 2018 19:00:00 +0200');
        $this->assertTrue($date->inUtcTimezone()->isUtc);
        $this->assertSame('17:00', $date->inUtcTimezone()->format('H:i'));
    }

    /** @test */
    public function can_determine_if_the_instance_is_in_the_utc_timezone()
    {
        $this->assertTrue(PostmarkDate::parse('27 Apr 2018 12:00:00 +0000')->isUtc);
        $this->assertFalse(PostmarkDate::parse('27 Apr 2018 12:00:00 +0200')->isUtc);
    }

    /** @test */
    public function can_determine_the_current_timezone_of_the_instance()
    {
        $this->assertSame('+00:00', PostmarkDate::parse('27 Apr 2018 12:00:00 +0000')->timezone);
        $this->assertSame('+02:00', PostmarkDate::parse('27 Apr 2018 12:00:00 +0200')->timezone);
    }

    /** @test */
    public function getting_unknown_getter_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $date = new PostmarkDate('now');
        $date->doesNotExist;
    }

    /** @test */
    public function if_it_failes_to_parse_a_time_string_it_should_return_the_current_date_time()
    {
        $date = PostmarkDate::parse('21 May 2018 27:03:20 +0700');

        $this->assertNotNull($date->format('Y-m-d H:i:s O'));
    }
}

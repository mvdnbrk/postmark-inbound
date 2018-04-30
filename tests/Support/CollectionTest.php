<?php

namespace Mvdnbrk\Postmark\Tests\Support;

use PHPUnit\Framework\TestCase;
use Mvdnbrk\Postmark\Support\Collection;

class CollectionTest extends TestCase
{
    /** @test */
    public function offset_access()
    {
        $c = new Collection(['name' => 'john']);
        $this->assertEquals('john', $c['name']);

        $c['name'] = 'jane';
        $this->assertEquals('jane', $c['name']);

        $this->assertTrue(isset($c['name']));
        unset($c['name']);
        $this->assertFalse(isset($c['name']));

        $c[] = 'jason';
        $this->assertEquals('jason', $c[0]);
    }

    /** @test */
    public function array_access_offset_exists()
    {
        $c = new Collection(['foo', 'bar']);
        $this->assertTrue($c->offsetExists(0));
        $this->assertTrue($c->offsetExists(1));
        $this->assertFalse($c->offsetExists(1000));
    }

    /** @test */
    public function array_access_offset_get()
    {
        $c = new Collection(['foo', 'bar']);
        $this->assertEquals('foo', $c->offsetGet(0));
        $this->assertEquals('bar', $c->offsetGet(1));
    }

    /** @test */
    public function array_access_offset_set()
    {
        $c = new Collection(['foo', 'foo']);
        $c->offsetSet(1, 'bar');
        $this->assertEquals('bar', $c[1]);
        $c->offsetSet(null, 'qux');
        $this->assertEquals('qux', $c[2]);
    }

    /** @test */
    public function array_access_offset_unset()
    {
        $c = new Collection(['foo', 'bar']);
        $c->offsetUnset(1);
        $this->assertFalse(isset($c[1]));
    }

    /** @test */
    public function to_array_returns_an_array()
    {
        $c = new Collection(['foo', 'bar']);
        $this->assertEquals(['foo', 'bar'], $c->toArray());

        $c = new Collection(['key' => 'value']);
        $this->assertEquals(['key' => 'value'], $c->toArray());
    }

    /** @test */
    public function change_key_case_returns_array_with_changed_key_case()
    {
        $c = new Collection(['FOO' => 'BAR', 'bAr' => 'bAz']);
        $this->assertEquals(['foo' => 'BAR', 'bar' => 'bAz'], $c->changeKeyCase()->all());
    }

    /** @test */
    public function contains_method()
    {
        $c = new Collection([1, 3, 5]);
        $this->assertTrue($c->contains(1));
        $this->assertFalse($c->contains(2));
        $this->assertTrue($c->contains(function ($value) {
            return $value < 5;
        }));
        $this->assertFalse($c->contains(function ($value) {
            return $value > 5;
        }));

        $c = new Collection(['date', 'class', (object) ['foo' => 50]]);
        $this->assertTrue($c->contains('date'));
        $this->assertTrue($c->contains('class'));
        $this->assertFalse($c->contains('foo'));

        $c = new Collection([null, 1, 2,]);
        $this->assertTrue($c->contains(function ($value) {
            return is_null($value);
        }));
    }

    /** @test */
    public function filter()
    {
        $c = new Collection([['id' => 1, 'name' => 'Hello'], ['id' => 2, 'name' => 'World']]);
        $this->assertEquals([1 => ['id' => 2, 'name' => 'World']], $c->filter(function ($item) {
            return $item['id'] == 2;
        })->all());

        $c = new Collection(['', 'Hello', '', 'World']);
        $this->assertEquals(['Hello', 'World'], $c->filter()->values()->toArray());

        $c = new Collection(['id' => 1, 'first' => 'Hello', 'second' => 'World']);
        $this->assertEquals(['first' => 'Hello', 'second' => 'World'], $c->filter(function ($item, $key) {
            return $key != 'id';
        })->all());
    }

    /** @test */
    public function first_returns_first_item_in_collection()
    {
        $c = new Collection(['foo', 'bar']);
        $this->assertEquals('foo', $c->first());
    }

    /** @test */
    public function first_with_callback()
    {
        $data = new Collection(['foo', 'bar', 'baz']);

        $result = $data->first(function ($value) {
            return $value === 'bar';
        });

        $this->assertEquals('bar', $result);
    }

    /** @test */
    public function first_with_callback_and_default()
    {
        $data = new Collection(['foo', 'bar']);

        $result = $data->first(function ($value) {
            return $value === 'baz';
        }, 'default');

        $this->assertEquals('default', $result);
    }

    /** @test */
    public function first_with_default_and_without_callback()
    {
        $data = new Collection;
        $result = $data->first(null, 'default');
        $this->assertEquals('default', $result);
    }

    /** @test */
    public function get_with_default()
    {
        $data = new Collection;
        $result = $data->get('foo', 'default');
        $this->assertEquals('default', $result);
    }

    /** @test */
    public function last_returns_last_item_in_collection()
    {
        $c = new Collection(['foo', 'bar']);
        $this->assertEquals('bar', $c->last());
    }

    /** @test */
    public function last_with_default()
    {
        $data = new Collection;
        $result = $data->last('default');
        $this->assertEquals('default', $result);
    }

    /** @test */
    public function collection_is_countable()
    {
        $c = new Collection(['foo', 'bar']);
        $this->assertCount(2, $c);
    }

    /** @test */
    public function has()
    {
        $data = new Collection(['id' => 1, 'first' => 'Hello', 'second' => 'World']);
        $this->assertTrue($data->has('first'));
        $this->assertFalse($data->has('third'));
        $this->assertTrue($data->has(['first', 'second']));
        $this->assertFalse($data->has(['third', 'first']));
    }

    /** @test */
    public function each()
    {
        $c = new Collection($original = [1, 2, 'foo' => 'bar', 'bam' => 'baz']);

        $result = [];
        $c->each(function ($item, $key) use (&$result) {
            $result[$key] = $item;
        });
        $this->assertEquals($original, $result);

        $result = [];
        $c->each(function ($item, $key) use (&$result) {
            $result[$key] = $item;
            if (is_string($key)) {
                return false;
            }
        });
        $this->assertEquals([1, 2, 'foo' => 'bar'], $result);
    }

    /** @test */
    public function make_method()
    {
        $collection = Collection::make(['foo']);
        $this->assertEquals(['foo'], $collection->all());
    }

    /** @test */
    public function map()
    {
        $data = new Collection(['first' => 'john', 'last' => 'doe']);
        $data = $data->map(function ($item, $key) {
            return $key.'-'.strrev($item);
        });

        $this->assertEquals(['first' => 'first-nhoj', 'last' => 'last-eod'], $data->all());
    }

    /** @test */
    public function map_with_keys()
    {
        $data = new Collection([
            ['name' => 'Blastoise', 'type' => 'Water', 'idx' => 9],
            ['name' => 'Charmander', 'type' => 'Fire', 'idx' => 4],
            ['name' => 'Dragonair', 'type' => 'Dragon', 'idx' => 148],
        ]);
        $data = $data->mapWithKeys(function ($pokemon) {
            return [$pokemon['name'] => $pokemon['type']];
        });

        $this->assertEquals(
            ['Blastoise' => 'Water', 'Charmander' => 'Fire', 'Dragonair' => 'Dragon'],
            $data->all()
        );
    }

    /** @test */
    public function reject_removes_elements_passing_truth_test()
    {
        $c = new Collection(['foo', 'bar']);
        $this->assertEquals(['foo'], $c->reject('bar')->values()->all());

        $c = new Collection(['foo', 'bar']);
        $this->assertEquals(['foo'], $c->reject(function ($v) {
            return $v == 'bar';
        })->values()->all());

        $c = new Collection(['foo', null]);
        $this->assertEquals(['foo'], $c->reject(null)->values()->all());

        $c = new Collection(['foo', 'bar']);
        $this->assertEquals(['foo', 'bar'], $c->reject('baz')->values()->all());

        $c = new Collection(['foo', 'bar']);
        $this->assertEquals(['foo', 'bar'], $c->reject(function ($v) {
            return $v == 'baz';
        })->values()->all());

        $c = new Collection(['id' => 1, 'primary' => 'foo', 'secondary' => 'bar']);
        $this->assertEquals(['primary' => 'foo', 'secondary' => 'bar'], $c->reject(function ($item, $key) {
            return $key == 'id';
        })->all());
    }

    /** @test */
    public function slice_offset()
    {
        $c = new Collection([1, 2, 3, 4, 5, 6, 7, 8]);
        $this->assertEquals([4, 5, 6, 7, 8], $c->slice(3)->values()->toArray());
    }

    /** @test */
    public function slice_negative_offset()
    {
        $c = new Collection([1, 2, 3, 4, 5, 6, 7, 8]);
        $this->assertEquals([6, 7, 8], $c->slice(-3)->values()->toArray());
    }

    /** @test */
    public function slice_offset_and_length()
    {
        $c = new Collection([1, 2, 3, 4, 5, 6, 7, 8]);
        $this->assertEquals([4, 5, 6], $c->slice(3, 3)->values()->toArray());
    }

    /** @test */
    public function slice_offset_and_negative_length()
    {
        $c = new Collection([1, 2, 3, 4, 5, 6, 7, 8]);
        $this->assertEquals([4, 5, 6, 7], $c->slice(3, -1)->values()->toArray());
    }

    /** @test */
    public function slice_negative_offset_and_length()
    {
        $c = new Collection([1, 2, 3, 4, 5, 6, 7, 8]);
        $this->assertEquals([4, 5, 6], $c->slice(-5, 3)->values()->toArray());
    }

    /** @test */
    public function slice_negative_offset_and_negative_length()
    {
        $c= new Collection([1, 2, 3, 4, 5, 6, 7, 8]);
        $this->assertEquals([3, 4, 5, 6], $c->slice(-6, -2)->values()->toArray());
    }

    /** @test */
    public function take()
    {
        $c = new Collection(['foo', 'bar', 'baz']);
        $c = $c->take(2);
        $this->assertEquals(['foo', 'bar'], $c->all());
    }

    /** @test */
    public function take_negative_limit()
    {
        $c = new Collection(['foo', 'bar', 'baz']);
        $c = $c->take(-2);
        $this->assertEquals(['bar', 'baz'], $c->values()->toArray());
    }

    /** @test */
    public function values()
    {
        $c = new Collection([['id' => 1, 'name' => 'Hello'], ['id' => 2, 'name' => 'World']]);

        $this->assertEquals([['id' => 2, 'name' => 'World']], $c->filter(function ($item) {
            return $item['id'] == 2;
        })->values()->all());
    }
}

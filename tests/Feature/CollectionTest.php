<?php

namespace Tests\Feature;

use App\Data\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertEqualsCanonicalizing;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

class CollectionTest extends TestCase
{
    /**
     * collection test.
     */
    public function testCollection(): void
    {
        $collection = collect([1,2,3]);
        assertEqualsCanonicalizing([1,2,3], $collection->all());
    }

    /**
     * collection test foreach.
     */
    public function testForEach(): void
    {
        $collection = collect([1,2,3]);
        foreach ($collection as $key => $value) {
            assertEquals($key + 1, $value);
        }
    }

    /**
     * collection test crud.
     */
    public function testCrud(): void
    {
        $collection = collect([]);

        // save data pada collection
        $collection->push(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
        assertEquals([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], $collection->all());


        // ambil dan hapus data index terakhir
        $pop = $collection->pop();
        // test isi variable
        assertEquals(10, $pop);
        // data pda index terakhir harus kosong
        assertEquals([1, 2, 3, 4, 5, 6, 7, 8, 9], $collection->all());
    }

    /**
     * Collection test Map
     */
    public function testMap(): void {
        $collection = collect([1, 2, 3]);

        $result = $collection->map(function ($item){
            return $item * 2;
        });

        assertEquals([2, 4, 6], $result->all());
    }

    /**
     * Collection test MapInto
     */
    public function testMapInto(): void {
        $collection = collect(['fulan']);

        $result = $collection->mapInto(Person::class);

        assertEquals([new Person('fulan')], $result->all());
    }

    /**
     * Collection test Map Spread
     */
    public function testMapSpread(): void {
        $collection = collect([['ahmad', 'fulan'], ['mustafa', 'bisri']]);

        $result = $collection->mapSpread(function ($firstName, $lastName){
            return new Person($firstName . ' ' . $lastName);
        });

        assertEquals([
            new Person('ahmad fulan'),
            new Person('mustafa bisri')],
            $result->all());
    }

    /**
     * Collection test Map To Group
     */
    public function testMapToGroup(): void {
        $collection = collect([
            ['name'=>'ahmad', 'departement'=>'Agama'],
            ['name'=>'mustafa', 'departement'=>'Agama'],
            ['name'=>'umam', 'departement'=>'IT']
        ]);

        $result = $collection->mapToGroups(function ($item){
            return [$item['departement']=>$item['name']];
        });

        assertEquals([
            'Agama' => collect(['ahmad', 'mustafa']),
            'IT' => collect(['umam']),          
        ],$result->all());
    }

    /**
     * Collection test Zip
     */
    public function testZip(): void {
       $collection1 = collect([1, 2, 3]);
       $collection2 = collect([4, 5, 6]);

       $collection3 = $collection1->zip($collection2);

       assertEquals([
        collect([1, 4]),
        collect([2, 5]),
        collect([3, 6])
       ], $collection3->all());

    }

    /**
     * Collection test Concat
     */
    public function testConcat(): void {
       $collection1 = collect([1, 2, 3]);
       $collection2 = collect([4, 5, 6]);

       $collection3 = $collection1->concat($collection2);

       assertEquals([1,2,3,4,5,6], $collection3->all());
    }

    /**
     * Collection test Combine
     */
    public function testCombine(): void {
       $collection1 = collect(['name', 'country']);
       $collection2 = collect(['fulan', 'Indonesia']);

       $collection3 = $collection1->combine($collection2);

       assertEquals([
        'name'=>'fulan',
        'country'=>'Indonesia'
       ], $collection3->all());
    }

    /**
     * Collection test Collaps
     */
    public function testCollaps(): void {
       $collection = collect([[1,2,3],[4,5,6],[7,8,9]]);
       $result = $collection->collapse();
       assertEquals([1,2,3,4,5,6,7,8,9], $result->all());
    }

    /**
     * Collection test Flat Map
     */
    public function testFlatMap(): void {
       $collection = collect([
        [
            'name'=>'fulan',
            'hobbies'=>['makan', 'tidur','coding']
        ],
        [
            'name'=>'sandi',
            'hobbies'=>['catur', 'olaharaga','belajar']
        ],
       ]);

       $result = $collection->flatMap(function($item){
        return $item['hobbies'];
       });

       assertEqualsCanonicalizing(['catur', 'olaharaga', 'belajar', 'makan', 'tidur', 'coding'], $result->all());
    }

    /**
     * Collection test String Representation
     */
    public function testStringRepresentation(): void {
       $collection = collect(['test1', 'test2', 'test3', 'test4', 'test5']);

       assertEquals('test1-test2-test3-test4-test5', $collection->join('-'));
       assertEquals('test1, test2, test3, test4 and test5', $collection->join(', ', ' and '));
    }

    /**
     * Collection test Filter
     */
    public function testFilter(): void {
       $collection = collect([
        'fulan1' => 100,
        'fulan2' => 90,
        'fulan3' => 80
       ]);

       $result = $collection->filter(function($value, $key){
        return $value >= 90;
       });

       assertEquals(['fulan1' => 100, 'fulan2' => 90], $result->all());

    }

    /**
     * Collection test Filter Index
     */
    public function testFilterIndex(): void {
       $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

       $result = $collection->filter(function($value, $key){
        return $value % 2 == 0;
       });

       assertEqualsCanonicalizing([2, 4, 6, 8, 10], $result->all());
    }
    
    /**
     * Collection test Partition
     */
    public function testPartition(): void {
        $collection = collect([
         'fulan1' => 100,
         'fulan2' => 90,
         'fulan3' => 80
        ]);
 
        [$result1, $result2] = $collection->partition(function($value, $key){
         return $value >= 90;
        });
 
        assertEquals(['fulan1' => 100, 'fulan2' => 90], $result1->all());
        assertEquals(['fulan3' => 80], $result2->all());
 
    }

    /**
     * Collection test Testing
     */
    public function testTesting(): void {
        $collection = collect(['fulan1', 'fulan2', 'fulan3']);
 
        assertTrue($collection->contains('fulan2'));
        assertTrue($collection->contains(function ($value, $key) {
            return $value == 'fulan2';
        }));
    }

    /**
     * Collection test Grouping
     */
    public function testGrouping(): void {
        $collection = collect([
            ['name'=>'ahmad', 'departement'=>'Agama'],
            ['name'=>'mustafa', 'departement'=>'Agama'],
            ['name'=>'umam', 'departement'=>'IT']
        ]);

        $result = $collection->groupBy('departement');
 
        assertEquals([
            'Agama' => collect([
                ['name'=>'ahmad', 'departement'=>'Agama'],
                ['name'=>'mustafa', 'departement'=>'Agama'],
            ]),
            'IT' => collect([
                ['name'=>'umam', 'departement'=>'IT']
            ])
        ], $result->all());

        $result = $collection->groupBy(function ($value, $key){
            return strtolower($value['departement']);
        });

        assertEquals([
            'agama' => collect([
                ['name'=>'ahmad', 'departement'=>'Agama'],
                ['name'=>'mustafa', 'departement'=>'Agama'],
            ]),
            'it' => collect([
                ['name'=>'umam', 'departement'=>'IT']
            ])
        ], $result->all());
    }

    /**
     * Collection test Slicing
     */
    public function testSlicing(): void {
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

        $result = $collection->slice(5);
        assertEqualsCanonicalizing([6, 7, 8, 9, 10], $result->all());

        $result = $collection->slice(5, 3);
        assertEqualsCanonicalizing([6, 7, 8], $result->all());
    }

    /**
     * Collection test Take
     */
    public function testTake(): void {
        $collection = collect([1, 2, 3, 1, 2, 3, 7, 8, 9, 10]);

        $result = $collection->take(3);
        assertEqualsCanonicalizing([1, 2, 3], $result->all());

        $result = $collection->takeUntil(function ($value, $key) {
            return $value == 3;
        });
        assertEquals([1, 2], $result->all());

        $result = $collection->takeWhile(function ($value, $key) {
            return $value < 3;
        });
        assertEquals([1, 2], $result->all());
    }

    /**
     * Collection test Skip
     */
    public function testSkip(): void {
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

        $result = $collection->skip(3);
        assertEqualsCanonicalizing([4, 5, 6, 7, 8, 9, 10], $result->all());

        $result = $collection->skipUntil(function ($value, $key) {
            return $value == 3;
        });
        assertEqualsCanonicalizing([3, 4, 5, 6, 7, 8, 9, 10], $result->all());

        $result = $collection->skipWhile(function ($value, $key) {
            return $value < 3;
        });
        assertEqualsCanonicalizing([3, 4, 5, 6, 7, 8, 9, 10], $result->all());
    }

    /**
     * Collection test Chunked
     */
    public function testChunked(): void {
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $result = $collection->chunk(3);
        assertEqualsCanonicalizing([1, 2, 3], $result->all()[0]->all());
        assertEqualsCanonicalizing([4, 5, 6], $result->all()[1]->all());
        assertEqualsCanonicalizing([7, 8, 9], $result->all()[2]->all());
        assertEqualsCanonicalizing([10], $result->all()[3]->all());
    }

    /**
     * Collection test First
     */
    public function testFirst(): void {
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $result = $collection->first();
        assertEquals(1, $result);

        $result = $collection->first(function ($value, $key){
            return $value > 5;
        });

        assertEquals(6, $result);
    }

    /**
     * Collection test Last
     */
    public function testLast(): void {
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $result = $collection->last();
        assertEquals(10, $result);

        $result = $collection->last(function ($value, $key){
            return $value < 5;
        });

        assertEquals(4, $result);
    }

    /**
     * Collection test Random
     */
    public function testRandom(): void {
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

        $result = $collection->random();
        assertTrue(in_array($result, $collection->all()));

        // $result = $collection->random(5);
        // assertEquals([1, 2, 3, 4, 5], $result->all());
    }

    /**
     * Collection test Checking Existence
     */
    public function testCheckingExistence(): void {
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

        assertTrue($collection->isNotEmpty());
        assertFalse($collection->isEmpty());
        assertTrue($collection->contains(1));
        assertFalse($collection->contains(0));
        assertTrue($collection->contains(function ($value, $key) {
            return $value == 9;
        }));
    }

    /**
     * Collection test Ordering
     */
    public function testOrdering(): void {
        $collection = collect([1, 4, 5, 2, 3, 6, 9, 8, 7, 10]);
        $result = $collection->sort();
        // $result = $collection->sortDesc();

        assertEqualsCanonicalizing([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], $result->all());
    }

    /**
     * Collection test Agregat
     */
    public function testAgregat(): void {
        $collection = collect([1, 4, 5, 2, 3, 6, 9, 8, 7, 10]);

        $result = $collection->sum();
        assertEquals(55, $result);

        $result = $collection->max();
        assertEquals(10, $result);

        $result = $collection->min();
        assertEquals(1, $result);

        $result = $collection->avg();
        assertEquals(5.5, $result);
    }
}

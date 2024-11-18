<?php

namespace Tests\Feature;

use App\Models\RootCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class RootCategoryTest extends TestCase
{
//    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
   public function testRootCategoryCreation()
   {
       $title = fake()->word;

       $testData = [
           'title' => fake()->word,
           'slug' => Str::slug($title)
       ];


       $rootCategory = RootCategory::create($testData);

       $this->assertDatabaseHas('root_categories', $testData);
   }
}

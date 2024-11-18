<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    // This trait ensures that the database is reset after each test, providing a clean state.
//    Use RefreshDatabase;
    /**
     * A basic feature test example.
     */
   public function testUserCreation()
   {
       $name = "Test " . fake()->name;

       $otpValue = rand(100000, 999999);

       $phone = fake()->unique()->phoneNumber;

       $email = fake()->safeEmail;

       $user = User::create([
           'name' => $name,
           'email' => $email,
           'phone' => $phone,
           'password' => Hash::make('password'),
           'otp' => $otpValue,
       ]);

        // This assertion checks if the user's table contains the specified data.
       $this->assertDatabaseHas('users', [
           'name' => $name,
           'email' => $email,
           'phone' => $phone,
           'otp' => $otpValue
       ]);

       // Check the password is correctly hashed
       $this->assertTrue(Hash::check('password', $user->password));
   }
}

<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private array $attributes;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->attributes = [
            'username' => 'john_doe-123',
            'email' => 'john.doe@example.com',
            'email_verified_at' => Carbon::parse('2024-01-20 15:05:35'),
            'password' => Hash::make('password'),
            'remember_token' => 'FqBbJbJDOS',
            'is_admin' => false,
            'created_at' => Carbon::parse('2024-01-20 14:05:35'),
            'updated_at' => Carbon::parse('2024-01-20 16:05:35'),
        ];

        $this->user = User::factory()->create($this->attributes);
    }

    public function test_user_attributes(): void
    {
        $this->assertSame($this->attributes['username'], $this->user->username);
        $this->assertSame($this->attributes['email'], $this->user->email);
        $this->assertTrue($this->attributes['email_verified_at']->equalTo($this->user->email_verified_at));
        $this->assertSame($this->attributes['password'], $this->user->password);
        $this->assertSame($this->attributes['remember_token'], $this->user->remember_token);
        $this->assertSame($this->attributes['is_admin'], $this->user->is_admin);
        $this->assertTrue($this->attributes['created_at']->equalTo($this->user->created_at));
        $this->assertTrue($this->attributes['updated_at']->equalTo($this->user->updated_at));
    }

    public function test_user_casts(): void
    {
        $this->assertTrue($this->user->email_verified_at instanceof Carbon);
        $this->assertTrue($this->user->created_at instanceof Carbon);
        $this->assertTrue($this->user->updated_at instanceof Carbon);
        $this->assertTrue(getType($this->user->is_admin) === 'boolean');
        $this->assertTrue(Hash::isHashed($this->user->password));
    }

    public function test_user_traits(): void
    {
        $this->assertTrue(in_array(HasApiTokens::class, class_uses($this->user)));
        $this->assertTrue(in_array(HasFactory::class, class_uses($this->user)));
        $this->assertTrue(in_array(Notifiable::class, class_uses($this->user)));
    }

    public function test_user_interfaces(): void
    {
        $this->assertTrue(in_array(MustVerifyEmail::class, class_implements($this->user)));
    }

    public function test_user_parent_classes(): void
    {
        $this->assertTrue($this->user instanceof \Illuminate\Foundation\Auth\User);
    }
}

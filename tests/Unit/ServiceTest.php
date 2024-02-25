<?php

namespace Tests\Unit;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Spatie\Translatable\HasTranslations;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    private array $attributes;
    private Service $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->attributes = [
            'name' => 'EverestService',
            'description' => [
                'en' => 'Sample service on EverestServer',
                'pl' => 'Przykładowa usługa na EverestServer',
            ],
            'icon' => 'fa-server fa-solid',
            'link' => 'https://service.everestserver.test',
            'is_public' => true,
            'is_enabled' => true,
            'created_at' => Carbon::parse('2024-01-20 14:05:35'),
            'updated_at' => Carbon::parse('2024-01-20 16:05:35'),
        ];

        $this->service = Service::factory()->create($this->attributes);
    }

    public function test_service_attributes(): void
    {
        $this->assertSame($this->attributes['name'], $this->service->name);
        $this->assertSame($this->attributes['description'], $this->service->getTranslations('description'));
        $this->assertSame($this->attributes['icon'], $this->service->icon);
        $this->assertSame($this->attributes['link'], $this->service->link);
        $this->assertSame($this->attributes['is_public'], $this->service->is_public);
        $this->assertSame($this->attributes['is_enabled'], $this->service->is_enabled);
        $this->assertTrue($this->attributes['created_at']->equalTo($this->service->created_at));
        $this->assertTrue($this->attributes['updated_at']->equalTo($this->service->updated_at));
    }

    public function test_service_casts(): void
    {
        $this->assertTrue(getType($this->service->is_public) === 'boolean');
        $this->assertTrue(getType($this->service->is_enabled) === 'boolean');
        $this->assertTrue($this->service->created_at instanceof Carbon);
        $this->assertTrue($this->service->updated_at instanceof Carbon);
    }

    public function test_service_traits(): void
    {
        $this->assertTrue(in_array(HasFactory::class, class_uses($this->service)));
        $this->assertTrue(in_array(HasTranslations::class, class_uses($this->service)));
    }

    public function test_service_parent_classes(): void
    {
        $this->assertTrue($this->service instanceof Model);
    }
}

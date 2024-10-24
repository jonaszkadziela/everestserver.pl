<?php

namespace Tests\Unit;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Translatable\HasTranslations;
use Tests\TestCase;

class ServiceTest extends TestCase
{
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

    public function test_service_enabled_scope(): void
    {
        $enabledService = Service::factory()->create();
        $disabledService = Service::factory()->disabled()->create();

        $foundEnabledService = Service::enabled()->find($enabledService->id);
        $foundDisabledService = Service::enabled(false)->find($disabledService->id);

        $this->assertTrue($enabledService->is_enabled);
        $this->assertSame($enabledService->id, $foundEnabledService->id);

        $this->assertFalse($disabledService->is_enabled);
        $this->assertSame($disabledService->id, $foundDisabledService->id);
    }

    public function test_service_public_scope(): void
    {
        $publicService = Service::factory()->create();
        $privateService = Service::factory()->private()->create();

        $foundPublicService = Service::public()->find($publicService->id);
        $foundPrivateService = Service::public(false)->find($privateService->id);

        $this->assertTrue($publicService->is_public);
        $this->assertSame($publicService->id, $foundPublicService->id);

        $this->assertFalse($privateService->is_public);
        $this->assertSame($privateService->id, $foundPrivateService->id);
    }

    public function test_service_users_relation(): void
    {
        $user = User::factory()->create();

        DB::table('services_users')
            ->insert([
                'service_id' => $this->service->id,
                'user_id' => $user->id,
                'identifier' => $user->email,
            ]);

        $this->assertTrue($this->service->users() instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany);
        $this->assertCount(1, $this->service->users()->get());
        $this->assertSame($user->id, $this->service->users()->first()->id);
        $this->assertSame($user->email, $this->service->users()->first()->pivot->identifier);
    }
}

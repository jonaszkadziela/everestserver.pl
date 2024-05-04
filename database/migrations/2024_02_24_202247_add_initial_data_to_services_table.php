<?php

use App\Models\Service;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Arr;

return new class () extends Migration {
    private array $services = [
        [
            'name' => 'EverestCloud',
            'description' => [
                'en' => 'EverestCloud is a cloud storage platform. It allows you to manage data, view photos and listen to music',
                'pl' => 'EverestCloud to platforma do przechowywania danych w chmurze. Pozwala zarządzać danymi, oglądać zdjęcia oraz słuchać muzyki'
            ],
            'icon' => 'fa-solid fa-cloud text-4xl',
            'link' => 'https://cloud.everestserver.pl',
        ],
        [
            'name' => 'EverestPass',
            'description' => [
                'en' => 'EverestPass is a password management platform. Thanks to it, you no longer have to remember all the passwords, you only need to know one',
                'pl' => 'EverestPass to platforma do zarządzania hasłami. Dzięki niej nie musisz już pamiętać wszystkich haseł, wystarczy znać tylko jedno'
            ],
            'icon' => 'fa-solid fa-unlock-keyhole text-4xl',
            'link' => 'https://pass.everestserver.pl',
        ],
        [
            'name' => 'EverestGit',
            'description' => [
                'en' => 'EverestGit is a platform for managing Git repositories. Unlike GitHub, we don\'t teach AI on someone else\'s code',
                'pl' => 'EverestGit to platforma do zarządzania repozytoriami typu Git. W przeciwieństwie do GitHuba, nie uczymy sztucznej inteligencji na cudzym kodzie'
            ],
            'icon' => 'fa-brands fa-git-alt text-5xl -mt-1.5',
            'link' => 'https://git.everestserver.pl',
        ],
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->services as $service) {
            Service::firstOrCreate(
                Arr::only($service, 'name'),
                Arr::except($service, 'name'),
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Service::whereIn('name', collect($this->services)->pluck('name'))
            ->delete();
    }
};

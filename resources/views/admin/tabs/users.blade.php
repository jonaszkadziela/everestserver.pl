<div class="flex items-start justify-between mb-8">
    <div class="flex flex-col">
        <h3 class="font-semibold text-xl mb-2">
            {{ Lang::get('admin.panel.users.title') }}
        </h3>
        <p class="text-gray-600 text-sm">
            {{ Lang::get('admin.panel.users.description') }}
        </p>
    </div>
    <x-primary-button class="flex-shrink-0">
        <span class="mr-2">
            {{ Lang::get('admin.panel.users.add-user') }}
        </span>
        <i class="fa-solid fa-user-plus"></i>
    </x-primary-button>
</div>
<div class="bg-white overflow-x-auto rounded-lg shadow text-left">
    @if (!empty($data))
        <table class="w-full">
            <thead>
                <tr>
                    @foreach ($data[0] as $key => $value)
                        <th class="bg-slate-50 border-b p-4 @if($loop->first) rounded-tl-lg @endif">
                            {{ Lang::get('admin.panel.users.columns.' . $key) }}
                        </th>
                    @endforeach
                    <th class="bg-slate-50 border-b p-4 rounded-tr-lg">
                        {{ Lang::get('admin.panel.users.columns.actions') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                    <tr class="{{ $loop->last ? '' : 'border-b' }}">
                        @foreach ($row as $key => $value)
                            <td class="p-4">
                                {{ $value }}
                            </td>

                            @if ($loop->last)
                                <td class="p-4">
                                    <a href="#" class="text-blue-700 hover:text-blue-900">
                                        {{ Lang::get('admin.panel.users.edit') }}
                                    </a>
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="p-4">
            {{ Lang::get('admin.panel.users.no-results') }}
        </p>
    @endif
</div>

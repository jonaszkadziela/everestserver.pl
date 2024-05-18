<x-main-layout title="{{ Lang::get('main.titles.authorization-request') }}"
               body-class="bg-blue-700 bg-image-primary bg-image-responsive flex flex-col min-h-screen"
               with-actions-menu
               with-analytics
               with-footer
>
    <main class="flex flex-1 items-center justify-center my-12 w-full">
        <div class="bg-white border max-w-xl md:w-2/3 mx-8 p-8 rounded-lg shadow-lg text-center w-full">
            <a href="{{ route('home') }}" class="inline-block mb-6">
                <div class="bg-blue-700 flex h-24 items-center justify-center m-auto rounded-full w-24">
                    <x-application-logo class="block h-16 pb-1 w-16" />
                </div>
            </a>
            <h1 class="mb-6 text-3xl">
                {{ Lang::get('auth.authorization-request.title') }}
            </h1>
            <div class="mb-4 text-sm text-gray-600">
                {!! Lang::get('auth.authorization-request.description', ['name' => $client->name]) !!}
            </div>

            @if (count($scopes) > 0)
                <div class="bg-gray-100 border p-4 rounded-lg">
                    <p class="font-medium mb-1">
                        {{ Lang::get('auth.authorization-request.scopes') }}:
                    </p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($scopes as $scope)
                            <li>{{ Lang::get('auth.scopes.' . $scope->id) }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex gap-4 items-center justify-center mt-4">
                <form method="post" action="{{ route('passport.authorizations.deny') }}">
                    @csrf
                    @method('DELETE')

                    <input type="hidden" name="state" value="{{ $request->state }}">
                    <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                    <input type="hidden" name="auth_token" value="{{ $authToken }}">
                    <button class="focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 hover:text-gray-900 rounded-md text-gray-600 text-sm underline">
                        {{ Lang::get('auth.authorization-request.cancel') }}
                    </button>
                </form>

                <form method="post" action="{{ route('passport.authorizations.approve') }}">
                    @csrf

                    <input type="hidden" name="state" value="{{ $request->state }}">
                    <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                    <input type="hidden" name="auth_token" value="{{ $authToken }}">
                    <x-primary-button>
                        {{ Lang::get('auth.authorization-request.authorize') }}
                    </x-primary-button>
                </form>
            </div>
        </div>
    </main>
</x-main-layout>

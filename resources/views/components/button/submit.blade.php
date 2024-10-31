<button x-data="{
            loading: false,
            form: $el.closest('form'),
            validateForm: event => {
                event.stopPropagation()

                if (!event.target.checkValidity()) {
                    event.preventDefault()
                    event.target.reportValidity()
                    $dispatch('invalid')
                }
            }
        }"
        x-init="$data.form.setAttribute('novalidate', ''); $data.form.addEventListener('submit', $data.validateForm)"
        x-bind:class="loading ? 'pointer-events-none' : ''"
        type="submit"
        @click="loading = true"
        @invalid="loading = false"
        {{ $attributes->merge(['class' => 'bg-blue-700 border flex hover:bg-blue-800 items-center justify-center px-4 py-2 relative rounded-full text-white transition-colors']) }}
>
    <span x-show="loading"
          x-cloak
          class="absolute flex inset-0 items-center justify-center"
    >
        <i class="fa-circle-notch fa-solid fa-spin"></i>
    </span>
    <span x-bind:class="loading ? 'opacity-0' : ''">
        {{ $slot }}
    </span>
</button>

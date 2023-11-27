<div id="privacy-warning" class="bg-white border duration-500 hidden hover:shadow-xl max-w-md opacity-0 p-4 rounded-lg shadow-md transition-all" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="text-sm">
        <p>
            {{ Lang::get('main.cookies.description') }}
            <a href="{{ route('privacy') }}" class="text-blue-700 hover:text-blue-900">{{ Lang::get('main.cookies.privacy-policy') }}</a>.
        </p>
        <div class="border-t flex gap-4 items-center justify-end mt-4 pt-4">
            <a href="{{ route('privacy') }}" class="text-blue-700 hover:text-blue-900">
                {{ Lang::get('main.cookies.learn-more') }}
            </a>
            <button type="button" class="bg-blue-700 hover:bg-blue-800 px-2 py-1.5 rounded text-white transition-colors" data-dismiss="#privacy-warning">
                {{ Lang::get('main.cookies.acknowledge') }}
            </button>
        </div>
    </div>
</div>

<script>
    const privacyWarning = document.querySelector('#privacy-warning')
    const dismissButton = document.querySelector('[data-dismiss="#privacy-warning"]')

    let dismissed = localStorage.getItem('privacy-warning-dismissed')

    if (privacyWarning !== null && dismissed !== 'true') {
        privacyWarning.style.setProperty('display', 'block')

        setTimeout(() => privacyWarning.style.setProperty('opacity', 0.8), 250)

        dismissButton.addEventListener('click', () => {
            dismissed = 'true'
            localStorage.setItem('privacy-warning-dismissed', dismissed)
            privacyWarning.style.setProperty('opacity', 0)

            setTimeout(() => privacyWarning.remove(), 500)
        })

        privacyWarning.addEventListener('mouseenter', () => {
            if (dismissed === 'true') {
                return
            }

            privacyWarning.style.setProperty('opacity', 1)
        })

        privacyWarning.addEventListener('mouseleave', () => {
            if (dismissed === 'true') {
                return
            }

            privacyWarning.style.setProperty('opacity', 0.8)
        })
    }
</script>

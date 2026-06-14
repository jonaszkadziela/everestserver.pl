import Alpine from 'alpinejs'

// Import brand images globally to avoid issues with Vite manifest
import '../images/brand/everestserver-logo.png?url'
import '../images/brand/everestserver-logo.svg?url'
import '../images/brand/favicon.png?url'
import '../images/brand/og-image.jpg?url'

window.Alpine = Alpine

Alpine.start()

const html = document.body.parentNode

if (!html.classList.contains('light') && !html.classList.contains('dark')) {
    html.classList.toggle('dark', window.matchMedia('(prefers-color-scheme: dark)').matches)
    html.classList.toggle('light', window.matchMedia('(prefers-color-scheme: light)').matches)
}

import Alpine from 'alpinejs'
import.meta.glob([
    '../images/**',
])

window.Alpine = Alpine

Alpine.start()

const html = document.body.parentNode

if (!html.classList.contains('light') && !html.classList.contains('dark')) {
    html.classList.toggle('dark', window.matchMedia('(prefers-color-scheme: dark)').matches)
    html.classList.toggle('light', window.matchMedia('(prefers-color-scheme: light)').matches)
}

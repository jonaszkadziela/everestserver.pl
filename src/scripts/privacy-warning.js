import { Toast } from 'bootstrap'

const privacyWarning = document.querySelector('#privacy-warning')
const dismissed = localStorage.getItem('privacy-warning-dismissed')

if (privacyWarning !== null && dismissed !== 'true') {
    new Toast(privacyWarning).show()

    privacyWarning.addEventListener('hidden.bs.toast', () => {
        localStorage.setItem('privacy-warning-dismissed', 'true')
    })

    privacyWarning.addEventListener('mouseenter', () => {
        privacyWarning.style.setProperty('background-color', 'rgba(255, 255, 255, 1)')
    })

    privacyWarning.addEventListener('mouseleave', () => {
        privacyWarning.style.setProperty('background-color', 'rgba(255, 255, 255, 0.85)')
    })
}

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

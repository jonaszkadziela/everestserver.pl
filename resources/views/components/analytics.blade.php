<script async src="https://www.googletagmanager.com/gtag/js?id={{ $gId }}"></script>
<script>
    window.dataLayer = window.dataLayer || []

    function gtag() {
        dataLayer.push(arguments)
    }

    gtag('js', new Date())
    gtag('config', '{{ $gId }}')
</script>

const THEME_KEY = 'theme'

function setTheme(theme, persist = false) {
    document.body.classList.remove('light', 'dark')
    document.body.classList.add(theme)
    document.documentElement.setAttribute('data-bs-theme', theme)

    if (persist) {
        localStorage.setItem(THEME_KEY, theme)
    }

    document.querySelectorAll('#toggle-dark').forEach((toggler) => {
        toggler.checked = theme === 'dark'
    })
}

function initTheme() {
    const storedTheme = localStorage.getItem(THEME_KEY)
    setTheme(storedTheme === 'dark' ? 'dark' : 'light')
}

function initializeThemeToggles() {
    document.querySelectorAll('#toggle-dark').forEach((toggler) => {
        toggler.checked = document.documentElement.getAttribute('data-bs-theme') === 'dark'
        toggler.addEventListener('input', (event) => {
            setTheme(event.target.checked ? 'dark' : 'light', true)
        })
    })
}

initTheme()

if (document.readyState === 'loading') {
    window.addEventListener('DOMContentLoaded', initializeThemeToggles, { once: true })
} else {
    initializeThemeToggles()
}

window.addEventListener('storage', (event) => {
    if (event.key === THEME_KEY) {
        setTheme(event.newValue === 'dark' ? 'dark' : 'light')
    }
})

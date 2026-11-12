import $ from 'jquery'
import * as bootstrap from 'bootstrap'

window.$ = window.jQuery = $
window.bootstrap = bootstrap

const theme = localStorage.getItem('theme')

document.documentElement.setAttribute('data-bs-theme', theme === 'dark' ? 'dark' : 'light')

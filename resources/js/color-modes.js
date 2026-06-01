/**
 * CoreUI 5 Color Modes Script
 * Adapted from CoreUI documentation
 */
(() => {
    'use strict'

    const THEME_STORAGE_KEY = 'coreui-free-vue-admin-template-theme'

    const getStoredTheme = () => localStorage.getItem(THEME_STORAGE_KEY)
    const setStoredTheme = theme => localStorage.setItem(THEME_STORAGE_KEY, theme)

    const getPreferredTheme = () => {
        const storedTheme = getStoredTheme()
        if (storedTheme) {
            return storedTheme
        }
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
    }

    const setTheme = theme => {
        if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-coreui-theme', 'dark')
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.setAttribute('data-coreui-theme', theme)
            if (theme === 'dark') {
                document.documentElement.classList.add('dark')
            } else {
                document.documentElement.classList.remove('dark')
            }
        }
    }

    const showActiveTheme = (theme, focus = false) => {
        const themeSwitcher = document.querySelector('.theme-icon-active')
        if (!themeSwitcher) {
            return
        }

        const btnToActive = document.querySelector(`[data-coreui-theme-value="${theme}"]`)
        if (!btnToActive) {
            return
        }

        document.querySelectorAll('[data-coreui-theme-value]').forEach(element => {
            element.classList.remove('active')
        })

        btnToActive.classList.add('active')

        // Update icon class based on theme
        const iconClass = theme === 'dark' ? 'fa-moon' : (theme === 'auto' ? 'fa-circle-half-stroke' : 'fa-sun')
        themeSwitcher.className = `fas ${iconClass} theme-icon-active`

        if (focus) {
            themeSwitcher.focus()
        }
    }

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        const storedTheme = getStoredTheme()
        if (storedTheme !== 'light' && storedTheme !== 'dark') {
            setTheme(getPreferredTheme())
        }
    })

    window.addEventListener('DOMContentLoaded', () => {
        showActiveTheme(getPreferredTheme())

        document.querySelectorAll('[data-coreui-theme-value]')
            .forEach(toggle => {
                toggle.addEventListener('click', () => {
                    const theme = toggle.getAttribute('data-coreui-theme-value')
                    setStoredTheme(theme)
                    setTheme(theme)
                    showActiveTheme(theme, true)
                })
            })
    })
})()

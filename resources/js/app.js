import './bootstrap';

import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';

window.Alpine = Alpine;
Alpine.plugin(focus);

Alpine.data('appShell', () => {
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const initialDark = localStorage.theme === 'dark' || (!('theme' in localStorage) && prefersDark);

    return {
        dark: initialDark,
        mobileOpen: false,
        userOpen: false,
        init() {
            this.mobileOpen = false;
            this.userOpen = false;
            this.updateTheme();
        },
        toggle() {
            this.dark = !this.dark;
            this.updateTheme();
        },
        updateTheme() {
            document.documentElement.classList.toggle('dark', this.dark);
            localStorage.theme = this.dark ? 'dark' : 'light';
        },
    };
});

Alpine.start();

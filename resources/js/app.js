import './bootstrap';


document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('[data-theme-toggle]');

    if (!toggle) {
        return;
    }

    const setTheme = (theme) => {
        document.documentElement.dataset.theme = theme;
        localStorage.setItem('theme', theme);
    };

    toggle.addEventListener('click', () => {
        const currentTheme = document.documentElement.dataset.theme || 'light';
        setTheme(currentTheme === 'dark' ? 'light' : 'dark');
    });
});

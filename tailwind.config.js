import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'

export default {
    content: ['./resources/**/*.blade.php','./resources/**/*.js'],
    theme: {
        extend: {
            fontFamily: {
                serif:  ['Georgia', 'Cambria', ...defaultTheme.fontFamily.serif],
                mono:   ['JetBrains Mono', 'Courier New', ...defaultTheme.fontFamily.mono],
                sans:   ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                gold:   { DEFAULT: '#D4A017', light: '#F59E0B', dark: '#92400E' },
                hawk:   { 50:'#f0f4ff', 100:'#e0eaff', 500:'#1D4ED8', 900:'#0B1E3D', 950:'#060D1F' },
            },
            backgroundImage: {
                'hawk-gradient': 'linear-gradient(135deg, #060D1F 0%, #0B1E3D 50%, #1E3A8A 100%)',
                'gold-gradient': 'linear-gradient(135deg, #D4A017 0%, #F59E0B 100%)',
                'card-gradient': 'linear-gradient(145deg, rgba(30,58,138,0.3) 0%, rgba(6,13,31,0.8) 100%)',
            },
            boxShadow: {
                'gold': '0 0 30px rgba(212,160,23,0.15), 0 4px 20px rgba(0,0,0,0.4)',
                'card': '0 8px 32px rgba(0,0,0,0.3), 0 2px 8px rgba(0,0,0,0.2)',
                'glow': '0 0 20px rgba(6,182,212,0.3)',
            },
        },
    },
    plugins: [forms],
}

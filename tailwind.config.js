module.exports = {
    purge: ['./resources/**/*.blade.php', './resources/**/*.vue'],
    theme: {
        fontFamily: {
            default: ['Calibre', 'sans-serif'],
        },
        fontSize: {
            sm: ['16px', '22px'],
            base: ['18px', '26px'],
            lg: ['20px', '28px'],
            xl: ['22px', '28px'],
            '2xl': ['30px', '38px'],
            '3xl': ['42px', '48px'],
            '4xl': ['54px', '60px'],
            '5xl': ['68px', '72px'],
        },
        fontWeight: {
            normal: 400,
            semibold: 600,
        },
        colors: {
            black: '#161616',
            white: '#ffffff',
        },
        container: {
            center: true,
            padding: {
                default: '24px',
                md: '48px',
            },
        },
        // screens: {
        //   sm: '640px',
        //   md: '768px',
        //   lg: '1024px',
        //   xl: '1280px',
        // },
    },
};

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./resources/**/*/*.blade.php",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Quicksand", "ui-sans-serif", "system-ui"],
                quicksand: ["Quicksand", "sans-serif"],
            },
            colors: {
                zinc: {
                    50: "#fafafa",
                    100: "#f5f5f5",
                    200: "#e5e5e5",
                    300: "#d4d4d4",
                    400: "#a3a3a3",
                    500: "#737373",
                    600: "#525252",
                    700: "#404040",
                    800: "#262626",
                    900: "#171717",
                    950: "#0a0a0a",
                },
            },
        },
    },
    plugins: [require("@tailwindcss/line-clamp")],
};

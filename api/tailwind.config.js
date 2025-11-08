/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./view/**/*.{html,js,php}",],
    safelist: [
      'bg-[url(\'/resources/images/Flag_of_the_Netherlands.svg\')]',
      'bg-[url(\'/resources/images/Flag_of_the_United_Kingdom.svg\')]'
    ],
     theme: {
       extend: {},
 },
     plugins: [],
}
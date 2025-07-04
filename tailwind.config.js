import preset from './vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/**/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './awcodes/filament-table-repeater/resources/**/*.blade.php',
        './jaocero/activity-timeline/resources/views/**/*.blade.php',
    ],
}

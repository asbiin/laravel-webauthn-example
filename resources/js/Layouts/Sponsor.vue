<template>
<a href="https://github.com/sponsors/asbiin" rel="noopener noreferrer" title="Sponsor me on GitHub" target="_blank"
    class="px-4 py-2 bg-gray-100 dark:bg-gray-800 border border-slate-400 dark:border-slate-600 rounded-md text-gray-900 dark:text-gray-100 tracking-widest hover:bg-gray-200 dark:hover:bg-gray-800 active:bg-gray-200 dark:active:bg-gray-800 focus:outline-hidden focus:border-gray-900 dark:focus:border-gray-100 focus:ring-3 focus:ring-gray-300 dark:focus:ring-gray-700 disabled:opacity-25"
>
    <svg aria-hidden="true" height="16" viewBox="0 0 16 16" version="1.1" width="16" data-view-component="true" class="inline icon-sponsor mr-1 align-middle color-fg-sponsors overflow-visible"  fill="currentColor" >
        <path fill-rule="evenodd" d="M4.25 2.5c-1.336 0-2.75 1.164-2.75 3 0 2.15 1.58 4.144 3.365 5.682A20.565 20.565 0 008 13.393a20.561 20.561 0 003.135-2.211C12.92 9.644 14.5 7.65 14.5 5.5c0-1.836-1.414-3-2.75-3-1.373 0-2.609.986-3.029 2.456a.75.75 0 01-1.442 0C6.859 3.486 5.623 2.5 4.25 2.5zM8 14.25l-.345.666-.002-.001-.006-.003-.018-.01a7.643 7.643 0 01-.31-.17 22.075 22.075 0 01-3.434-2.414C2.045 10.731 0 8.35 0 5.5 0 2.836 2.086 1 4.25 1 5.797 1 7.153 1.802 8 3.02 8.847 1.802 10.203 1 11.75 1 13.914 1 16 2.836 16 5.5c0 2.85-2.045 5.231-3.885 6.818a22.08 22.08 0 01-3.744 2.584l-.018.01-.006.003h-.002L8 14.25zm0 0l.345.666a.752.752 0 01-.69 0L8 14.25z"></path>
    </svg>
    <span class="align-middle">
        <slot>
            Sponsor me!
        </slot>
    </span>
</a>
</template>

<style scoped>
    .icon-sponsor {
        transition: transform .15s cubic-bezier(0.2, 0, 0.13, 2);
    }

    a:hover .icon-sponsor {
        transform: scale(1.1);
    }

    .color-fg-sponsors {
        color: #c96198 !important;
    }
</style>

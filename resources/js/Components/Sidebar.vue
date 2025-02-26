<script setup>
import { Link } from '@inertiajs/vue3';

const navigation = [
    { name: 'All', href: route('chirps.index'), icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
    { name: 'CEIT', href: route('chirps.ceit'), icon: 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' },
    { name: 'CAS', href: route('chirps.cas'), icon: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253' },
    { name: 'CTE', href: route('chirps.cte'), icon: 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z' },
    { name: 'COT', href: route('chirps.cot'), icon: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10' },
];
</script>

<template>
    <div class="flex flex-col w-48 bg-white h-screen px-1 py-4 border-r">
        <h1 class="text-sm font-semibold text-center mb-3">Menu</h1>
        
        <nav class="space-y-1">
            <Link
                v-for="item in navigation"
                :key="item.name"
                :href="item.href"
                :class="[$page.url.startsWith(item.href) ? 
                    'bg-indigo-100 text-indigo-700' : 
                    'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                    'group flex items-center px-2 py-1 text-xs font-medium rounded-md']"
            >
                <svg
                    class="mr-1 h-4 w-4"
                    :class="$page.url.startsWith(item.href) ? 'text-indigo-700' : 'text-gray-400 group-hover:text-gray-500'"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        :d="item.icon"
                    />
                </svg>
                {{ item.name }}
            </Link>
        </nav>
    </div>
</template> 
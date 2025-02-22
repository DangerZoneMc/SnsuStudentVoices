<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps(['organizations']);
</script>

<template>
    <Head title="Organizations" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Organizations</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="org in organizations" :key="org.id" 
                    class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-semibold text-gray-900">{{ org.name }}</h3>
                            <span class="px-3 py-1 text-sm font-medium text-white bg-green-600 rounded-full">
                                {{ org.college }}
                            </span>
                        </div>
                        <p class="text-gray-600">{{ org.description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template> 
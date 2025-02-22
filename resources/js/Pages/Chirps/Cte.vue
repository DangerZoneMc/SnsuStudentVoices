<script setup>
import '@/../css/chirps.css';
import Chirp from '@/Components/Chirp.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps(['chirps']);

const form = useForm({
    message: '',
    media: null,
    department: 'cte',
});

const previewUrl = ref(null);

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.media = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            previewUrl.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const submit = () => {
    form.post(route('chirps.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            form.department = 'cte';
            previewUrl.value = null;
        },
    });
};
</script>

<template>
    <Head title="CTE Chirps" />

    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <form @submit.prevent="submit" class="space-y-4">
                <input type="hidden" v-model="form.department">
                <textarea
                    v-model="form.message"
                    placeholder="What's on your mind?"
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                ></textarea>
                <InputError :message="form.errors.message" class="mt-2" />
                
                <div class="mt-2">
                    <input
                        type="file"
                        @change="handleFileUpload"
                        accept="image/*,video/*"
                        class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-indigo-50 file:text-indigo-700
                        hover:file:bg-indigo-100"
                    />
                </div>
                <InputError :message="form.errors.media" class="mt-2" />
                
                <PrimaryButton class="mt-4">Chirp</PrimaryButton>
            </form>

            <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
                <Chirp
                    v-for="chirp in chirps"
                    :key="chirp.id"
                    :chirp="chirp"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template> 
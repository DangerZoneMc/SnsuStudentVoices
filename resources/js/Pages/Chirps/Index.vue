<script setup>
import '@/../css/chirps.css'; // Import external CSS
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
});

const previewUrl = ref(null);

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.media = file;
        // Create preview URL
        previewUrl.value = URL.createObjectURL(file);
    }
};

const removeMedia = () => {
    form.media = null;
    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
        previewUrl.value = null;
    }
};

const isImage = (file) => {
    return file?.type?.startsWith('image/');
};

const isVideo = (file) => {
    return file?.type?.startsWith('video/');
};
</script>
 
<template>
    <Head title="Chirps" />
 
    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <form @submit.prevent="form.post(route('chirps.store'), { onSuccess: () => { form.reset(); removeMedia(); } })">
                <textarea
                    v-model="form.message"
                    placeholder="What's on your mind?"
                    class="block w-full rounded-md shadow-sm"
                ></textarea>
                <InputError :message="form.errors.message" class="mt-2" />
                
                <!-- Media preview -->
                <div v-if="previewUrl" class="mt-2 relative">
                    <button 
                        @click.prevent="removeMedia"
                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
                        title="Remove media"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <img
                        v-if="isImage(form.media)"
                        :src="previewUrl"
                        alt="Upload preview"
                        class="rounded-lg max-h-96 object-contain"
                        @error="(e) => e.target.src = '/path/to/fallback-image.png'"
                    />
                    <video
                        v-else-if="isVideo(form.media)"
                        :src="previewUrl"
                        controls
                        class="rounded-lg max-h-96 w-full"
                    >
                        Your browser does not support the video tag.
                    </video>
                </div>

                <!-- File input -->
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

<script setup>
import CommentSection from '@/Components/CommentSection.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import InputError from '@/Components/InputError.vue';
import LikeButton from '@/Components/LikeButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import { ref } from 'vue';

dayjs.extend(relativeTime);

const props = defineProps(['chirp']);

const form = useForm({
    message: props.chirp.message,
    media: null,
});

const editing = ref(false);
const previewUrl = ref(null);

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.media = file;
        previewUrl.value = URL.createObjectURL(file);
    }
};

const isImage = (media) => {
    if (typeof media === 'string') {
        return media === 'image';
    }
    return media?.type?.startsWith('image/');
};

const isVideo = (media) => {
    if (typeof media === 'string') {
        return media === 'video';
    }
    return media?.type?.startsWith('video/');
};

const getMediaUrl = (url) => {
    if (!url) return null;
    // If the URL starts with 'http' or 'https', return it as is
    if (url.startsWith('http')) {
        return url;
    }
    // Otherwise, prepend the base URL
    return `${window.location.origin}${url}`;
};

const removeMedia = () => {
    form.media = null;
    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
        previewUrl.value = null;
    }
};
</script>

<template>
    <div class="p-6 flex space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        <div class="flex-1">
            <div class="flex justify-between items-center">
                <div>
                    <span class="text-gray-800">{{ chirp.user.name }}</span>
                    <small class="ml-2 text-sm text-gray-600">{{ dayjs(chirp.created_at).fromNow() }}</small>
                    <small v-if="chirp.created_at !== chirp.updated_at" class="text-sm text-gray-600"> &middot; edited</small>
                </div>
                <Dropdown v-if="chirp.user.id === $page.props.auth.user.id">
                    <template #trigger>
                        <button>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                            </svg>
                        </button>
                    </template>
                    <template #content>
                        <button class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:bg-gray-100 transition duration-150 ease-in-out" @click="editing = true">
                            Edit
                        </button>
                        <DropdownLink as="button" :href="route('chirps.destroy', chirp.id)" method="delete">
                            Delete
                        </DropdownLink>
                    </template>
                </Dropdown>
            </div>
            <form v-if="editing" @submit.prevent="form.put(route('chirps.update', chirp.id), { onSuccess: () => { editing = false; removeMedia(); } })">
                <textarea v-model="form.message" class="mt-4 w-full text-gray-900 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea>
                <InputError :message="form.errors.message" class="mt-2" />
                
                <!-- Media preview when editing -->
                <div v-if="previewUrl || chirp.media_url" class="mt-2 relative">
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
                        v-if="(previewUrl && isImage(form.media)) || (!previewUrl && isImage(chirp.media_type))"
                        :src="previewUrl || chirp.media_url"
                        alt="Media preview"
                        class="rounded-lg max-h-96 object-contain"
                        @error="(e) => e.target.src = '/path/to/fallback-image.png'"
                    />
                    <video
                        v-else-if="(previewUrl && isVideo(form.media)) || (!previewUrl && isVideo(chirp.media_type))"
                        :src="previewUrl || chirp.media_url"
                        controls
                        class="rounded-lg max-h-96 w-full"
                    >
                        Your browser does not support the video tag.
                    </video>
                </div>

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
                
                <div class="space-x-2">
                    <PrimaryButton class="mt-4">Save</PrimaryButton>
                    <button class="mt-4" @click="editing = false; form.reset(); removeMedia(); form.clearErrors()">Cancel</button>
                </div>
            </form>
            
            <div v-else>
                <p class="mt-4 text-lg text-gray-900">{{ chirp.message }}</p>
                
                <!-- Display existing media -->
                <div v-if="chirp.media_url" class="mt-3">
                    <img
                        v-if="isImage(chirp.media_type)"
                        :src="getMediaUrl(chirp.media_url)"
                        alt="Chirp media"
                        class="rounded-lg max-h-96 object-contain"
                        @error="(e) => console.error('Image failed to load:', e)"
                    />
                    <video
                        v-else-if="isVideo(chirp.media_type)"
                        :src="getMediaUrl(chirp.media_url)"
                        controls
                        class="rounded-lg max-h-96 w-full"
                    >
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>

            <div class="flex items-center mt-2">
                <LikeButton 
                    :chirp-id="chirp.id" 
                    :likes="chirp.likes.length"
                    :is-liked="chirp.likes.some(like => like.user_id === $page.props.auth.user.id)"
                />
            </div>
            <CommentSection :chirp-id="chirp.id" :comments="chirp.comments" class="mt-4" />
        </div>
    </div>
</template>

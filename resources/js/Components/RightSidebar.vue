<script setup>
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import { ref } from 'vue';

dayjs.extend(relativeTime);

const props = defineProps({
    announcements: Array,
    isAdmin: Boolean,
});

const form = useForm({
    title: '',
    content: '',
});

const showModal = ref(false);
const selectedAnnouncement = ref(null);

const submit = () => {
    form.post(route('announcements.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};

const openAnnouncement = (announcement) => {
    selectedAnnouncement.value = announcement;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedAnnouncement.value = null;
};
</script>

<template>
    <div class="fixed right-0 top-16 h-screen w-64 bg-white shadow-lg p-4 overflow-y-auto">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Announcements</h2>
            
            <!-- Admin Form -->
            <form v-if="isAdmin" @submit.prevent="submit" class="mb-6 space-y-4">
                <div>
                    <input
                        v-model="form.title"
                        type="text"
                        placeholder="Announcement Title"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    />
                    <InputError :message="form.errors.title" class="mt-2" />
                </div>
                
                <div>
                    <textarea
                        v-model="form.content"
                        placeholder="Announcement Content"
                        rows="3"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    ></textarea>
                    <InputError :message="form.errors.content" class="mt-2" />
                </div>
                
                <PrimaryButton type="submit">Post Announcement</PrimaryButton>
            </form>

            <!-- Announcements List -->
            <div class="space-y-4">
                <div v-for="announcement in announcements" :key="announcement.id" 
                    class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 cursor-pointer transition-colors duration-200"
                    @click="openAnnouncement(announcement)"
                >
                    <h3 class="font-semibold text-gray-900 truncate">{{ announcement.title }}</h3>
                    <p class="text-gray-600 text-sm mt-2 line-clamp-2">{{ announcement.content }}</p>
                    <div class="mt-2 text-xs text-gray-500">
                        Posted by {{ announcement.user.name }}
                        <span class="mx-1">•</span>
                        {{ dayjs(announcement.created_at).fromNow() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcement Modal -->
    <Modal :show="showModal" @close="closeModal">
        <div v-if="selectedAnnouncement" class="p-4">
            <div class="flex justify-between items-start">
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ selectedAnnouncement.title }}
                </h2>
                <button @click="closeModal" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="mt-4">
                <p class="text-gray-600 whitespace-pre-wrap">{{ selectedAnnouncement.content }}</p>
            </div>
            
            <div class="mt-6 text-sm text-gray-500">
                Posted by {{ selectedAnnouncement.user.name }}
                <span class="mx-1">•</span>
                {{ dayjs(selectedAnnouncement.created_at).format('MMMM D, YYYY h:mm A') }}
            </div>
        </div>
    </Modal>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style> 
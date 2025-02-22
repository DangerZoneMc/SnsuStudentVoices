<script setup>
import Dropdown from '@/Components/Dropdown.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import { ref } from 'vue';

dayjs.extend(relativeTime);

const props = defineProps({
    chirpId: { type: Number, required: true },
    comments: { type: Array, required: true },
});

const form = useForm({
    content: '',
});

const editingComment = ref(null);
const editForm = useForm({
    content: '',
});

const submitComment = () => {
    if (!form.content.trim()) return;

    form.post(route('chirps.comments.store', { chirp: props.chirpId }), {
        preserveScroll: true,
        preserveSnapshot: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const startEditing = (comment) => {
    editingComment.value = comment.id;
    editForm.content = comment.content;
};

const cancelEditing = () => {
    editingComment.value = null;
    editForm.reset();
};

const updateComment = (commentId) => {
    if (!editForm.content.trim()) return;
    
    editForm.patch(route('chirps.comments.update', { 
        chirp: props.chirpId, 
        comment: commentId 
    }), {
        preserveScroll: true,
        onSuccess: () => {
            editingComment.value = null;
            editForm.reset();
        },
        onError: (errors) => {
            console.error('Failed to update comment:', errors);
        },
    });
};

const deleteComment = (commentId) => {
    if (confirm('Are you sure you want to delete this comment?')) {
        form.delete(route('chirps.comments.destroy', { chirp: props.chirpId, comment: commentId }), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <div class="mt-4">
        <form @submit.prevent="submitComment">
            <textarea 
                v-model="form.content"
                placeholder="Write a comment..."
                class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                rows="2"
            ></textarea>
            <InputError :message="form.errors.content" class="mt-2" />
            <PrimaryButton 
                class="mt-2" 
                :disabled="form.processing || !form.content.trim()"
            >
                Comment
            </PrimaryButton>
        </form>
        
        <div class="mt-4 space-y-4">
            <div 
                v-for="comment in comments" 
                :key="comment.id" 
                class="p-4 bg-gray-50 rounded-lg"
            >
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="font-medium text-gray-900">{{ comment.user?.name }}</span>
                        <small class="text-gray-500">{{ dayjs(comment.created_at).fromNow() }}</small>
                    </div>
                    
                    <!-- Three dots menu for comment owner -->
                    <Dropdown 
                        v-if="comment.user?.id === $page.props.auth.user.id"
                        align="right"
                        width="48"
                    >
                        <template #trigger>
                            <button class="flex items-center text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                </svg>
                            </button>
                        </template>

                        <template #content>
                            <button
                                class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:bg-gray-100"
                                @click="startEditing(comment)"
                            >
                                Edit
                            </button>
                            <button
                                class="block w-full px-4 py-2 text-left text-sm leading-5 text-red-600 hover:bg-gray-100 focus:bg-gray-100"
                                @click="deleteComment(comment.id)"
                            >
                                Delete
                            </button>
                        </template>
                    </Dropdown>
                </div>
                
                <!-- Edit form -->
                <div v-if="editingComment === comment.id" class="mt-2">
                    <form @submit.prevent="updateComment(comment.id)">
                        <textarea 
                            v-model="editForm.content"
                            class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                            rows="2"
                            required
                        ></textarea>
                        <InputError :message="editForm.errors.content" class="mt-2" />
                        <div class="flex space-x-2 mt-2">
                            <PrimaryButton 
                                :disabled="editForm.processing || !editForm.content.trim()"
                                type="submit"
                            >
                                Save Changes
                            </PrimaryButton>
                            <button 
                                type="button"
                                class="px-4 py-2 text-sm text-gray-700 hover:text-gray-900 border border-gray-300 rounded-md"
                                @click="cancelEditing"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
                <!-- Display comment content when not editing -->
                <p v-else class="mt-2 text-gray-700">{{ comment.content }}</p>
            </div>
            <p v-if="!comments.length" class="text-gray-500 text-center">
                No comments yet. Be the first to comment!
            </p>
        </div>
    </div>
</template>

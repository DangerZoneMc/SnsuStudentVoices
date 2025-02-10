<script setup>
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';

defineProps({
    chirpId: { type: Number, required: true },
    comments: { type: Array, required: true },
});

const form = useForm({
    comment: '',
});

const submitComment = () => {
    if (!form.comment.trim()) return;

    form.post(route('chirps.comment', { chirp: props.chirpId }), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

</script>

<template>
    <div class="mt-4">
        <form @submit.prevent="submitComment">
            <textarea v-model="form.comment" class="w-full border-gray-300 rounded-md"></textarea>
            <InputError :message="form.errors.comment" class="mt-2" />
            <PrimaryButton class="mt-2" :disabled="form.processing || !form.comment.trim()">Comment</PrimaryButton>
        </form>
        
        <div class="mt-4">
            <div v-for="comment in comments" :key="comment.id" class="p-2 border-b">
                <p>{{ comment.content }}</p>
                <small class="text-gray-500">- {{ comment.user?.name || 'Anonymous' }}</small>
            </div>
        </div>
    </div>
</template>

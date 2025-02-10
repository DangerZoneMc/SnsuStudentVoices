<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    chirpId: { type: Number, required: true },
    likes: { type: Number, required: true },
    isLiked: { type: Boolean, default: false },
});

const form = useForm({});
const likeCount = ref(props.likes);
const liked = ref(props.isLiked);

// Ensure the state updates when props change (useful on re-render)
watch(() => props.isLiked, (newVal) => {
    liked.value = newVal;
});

const toggleLike = () => {
    form.post(route('chirps.like', { chirp: props.chirpId }), {
        preserveScroll: true,
        onSuccess: (response) => {
            liked.value = response.props.isLiked;
            likeCount.value = response.props.likes;
        },
    });
};
</script>

<template>
    <button @click="toggleLike" class="text-blue-500 hover:underline">
        <span v-if="liked">👎 Unlike</span>
        <span v-else>👍 Like</span>
        {{ likeCount }}
    </button>
</template>

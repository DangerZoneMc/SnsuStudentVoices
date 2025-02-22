<script setup>
import { useForm } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';

const props = defineProps({
    chirpId: { type: Number, required: true },
    likes: { type: Number, required: true },
    isLiked: { type: Boolean, required: true },
});

const form = useForm({});
const likeCount = ref(props.likes);
const liked = ref(props.isLiked);

onMounted(() => {
    liked.value = props.isLiked;
});

watch(() => props.isLiked, (newVal) => {
    liked.value = newVal;
});

watch(() => props.likes, (newVal) => {
    likeCount.value = newVal;
});

const toggleLike = () => {
    // Optimistic update
    liked.value = !liked.value;
    likeCount.value = liked.value ? likeCount.value + 1 : likeCount.value - 1;

    form.post(route('chirps.like', { chirp: props.chirpId }), {
        preserveScroll: true,
        onSuccess: (response) => {
            liked.value = response.props.isLiked;
            likeCount.value = response.props.likes;
        },
        onError: () => {
            // Revert on error
            liked.value = !liked.value;
            likeCount.value = liked.value ? likeCount.value + 1 : likeCount.value - 1;
        }
    });
};
</script>

<template>
    <button 
        @click="toggleLike" 
        class="flex items-center space-x-1 text-sm text-gray-600 hover:text-blue-500 transition"
    >
        <span v-if="liked" class="flex items-center space-x-1">
            <span class="text-blue-500">👍</span>
            <span class="text-blue-500">Liked</span>
        </span>
        <span v-else class="flex items-center space-x-1">
            <span>👍</span>
            <span>Like</span>
        </span>
        <span class="ml-1" :class="{ 'text-blue-500': liked }">{{ likeCount }}</span>
    </button>
</template>

<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Welcome" />

    <div class="min-h-screen bg-gradient">
        <div class="fixed bottom-0 left-0 p-6 sm:p-8 bg-white shadow-lg rounded-tr-xl w-full sm:max-w-md">
            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="Email" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Password" />

                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                    />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="block mt-4">
                    <label class="flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <span class="ms-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Forgot password?
                    </Link>

                    <PrimaryButton 
                        class="px-8 py-2 w-32" 
                        :class="{ 'opacity-25': form.processing }" 
                        :disabled="form.processing"
                    >
                        Log in
                    </PrimaryButton>
                </div>
                
            </form>
        </div>
    </div>
</template>

<style scoped>
.bg-gradient {
    background: linear-gradient(135deg, #1a8d1a 0%, #86ff86 100%);
}

/* Optional: Add some texture or overlay */
.min-h-screen::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.1);
    pointer-events: none;
}
</style>

<script setup>
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import RightSidebar from '@/Components/RightSidebar.vue';
import Sidebar from '@/Components/Sidebar.vue';
import { ref } from 'vue';

defineProps({
    announcements: Array,
    isAdmin: Boolean,
});

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <nav class="nav-container">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <!-- Left side with Logo and Title -->
                    <div class="flex items-center space-x-3">
                        <img 
                            src="https://scontent.fcgy1-1.fna.fbcdn.net/v/t1.15752-9/462540846_1743411143094889_454045276760592640_n.png?_nc_cat=105&ccb=1-7&_nc_sid=0024fc&_nc_eui2=AeE2S8NAbhs1coiSDFjUCk7uyEARBhS41ajIQBEGFLjVqDKF6uU2fPQgryf4giBgrurRbWFK8HWLPwwm83-J6lj0&_nc_ohc=j1kpht2rjsoQ7kNvgExId06&_nc_oc=Adgoa4zFNhIiZ4YOCnpo2kemAte-jk6MEtxkV03Acsnz20bXZz2wX58sf7pCOFiiu1D5PXRBC-qTozYJvcZvgL3N&_nc_ad=z-m&_nc_cid=0&_nc_zt=23&_nc_ht=scontent.fcgy1-1.fna&oh=03_Q7cD1gHozq-XjKnGdPwrrO6NIGFe1jDmrTbiwLMwmvTynxexDg&oe=67E14242" 
                            alt="SNSU Logo" 
                            class="h-12 w-auto"
                        />
                        <div class="text-white">
                            <h1 class="text-xl font-bold">SNSU Student Voices</h1>
                        </div>
                    </div>

                    <!-- Center Navigation Links -->
                    <div class="flex flex-1 justify-center">
                        <div class="hidden space-x-8 sm:flex">
                            <NavLink 
                                :href="route('organizations')" 
                                :active="route().current('organizations')"
                                class="text-white hover:text-gray-200 px-3 py-2 text-sm font-medium"
                            >
                                Organizations
                            </NavLink>
                            <NavLink 
                                :href="route('chirps.index')" 
                                :active="route().current('chirps.index')"
                                class="text-white hover:text-gray-200 px-3 py-2 text-sm font-medium"
                            >
                                Home
                            </NavLink>
                        </div>
                    </div>

                    <!-- Right side Profile -->
                    <div class="hidden sm:flex sm:items-center">
                        <div class="relative">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md items-center">
                                        <img 
                                            :src="$page.props.auth.user.profile_photo_url || '/default-avatar.png'"
                                            alt="User Profile"
                                            class="h-8 w-8 rounded-full object-cover"
                                        />
                                        <button type="button" class="text-white font-medium ml-2">
                                            {{ $page.props.auth.user.name }}
                                            <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="flex items-center sm:hidden">
                        <button @click="showingNavigationDropdown = !showingNavigationDropdown">
                            <svg class="h-6 w-6 text-white" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex">
            <!-- Left Sidebar -->
            <Sidebar class="fixed left-0 top-16 h-screen" />

            <!-- Main Content - Adjusted margin for both sidebars -->
            <div class="flex-1 ml-32 mr-64">
                <div class="min-h-screen bg-gray-100">
                    <!-- Page Heading -->
                    <header class="bg-white shadow" v-if="$slots.header">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            <slot name="header" />
                        </div>
                    </header>

                    <!-- Page Content -->
                    <main class="py-4">
                        <slot />
                    </main>
                </div>
            </div>

            <!-- Right Sidebar -->
            <RightSidebar 
                :announcements="announcements" 
                :is-admin="isAdmin"
            />
        </div>
    </div>
</template>

<style scoped>
.nav-container {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 50;
    background-color: #1a472a;
    color: white;
}

/* Add hover effect for nav links */
.nav-link {
    transition: all 0.3s ease;
}

.nav-link:hover {
    transform: translateY(-1px);
}
</style>

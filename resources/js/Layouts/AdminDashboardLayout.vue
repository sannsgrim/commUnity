<script setup>
import {ref} from "vue";
import {Link} from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import InputIcon from "primevue/inputicon";
import IconField from "primevue/iconfield";
import Popover from "primevue/popover";
import DropdownLink from "@/Components/DropdownLink.vue";

// Reactive state to control the visibility of AdminLoginLayout
const showAccountsLayout = ref(false);

// Function to toggle AdminLoginLayout
const toggleAccountsLayout = () => {
    showAccountsLayout.value = true; // Show AdminLoginLayout
};

const redirect = () => {

}

const showPopover = ref();
const toggle = (event) => {
    showPopover.value.toggle(event);
}

</script>

<template>
    <div class="flex h-screen">
        <!-- Sidebar -->
        <nav class="bg-white w-20 flex flex-col items-center py-6 space-y-6">
            <Link :href="route('admin.dashboard')" >
                <ApplicationLogo class="h-10 w-10 fill-current text-gray-500"/>
            </Link>
            <div class="space-y-10 flex flex-col py-10">

                <Link :href="route('admin.dashboard')" class="flex items-center justify-center"
                      :class="[route().current('admin.dashboard') ? 'text-white bg-violet-400 w-10 h-10 rounded-full' : 'bg-white text-sm font-medium text-gray-500 rounded-md w-10 h-10' ]"
                >
                    <i class="pi pi-home"></i>
                </Link>


                <Link :href="route('admin.view_permission')" class="flex items-center justify-center"
                      :class="[route().current('admin.view_permission') ? 'text-white bg-violet-400 w-10 h-10 rounded-full' : 'bg-white text-sm font-medium text-gray-500 rounded-md w-10 h-10' ]"
                >
                    <i class="pi pi-check-square"></i>
                </Link>


                <Link :href="route('admin.view_user')" class="flex items-center justify-center"
                      :class="[route().current('admin.view_user') ? 'text-white bg-violet-400 w-10 h-10 rounded-full' : 'bg-white text-sm font-medium text-gray-500 rounded-md w-10 h-10' ]"
                >
                    <i class="pi pi-users"></i>
                </Link>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="flex-1 bg-white">
            <!-- Top Navigation Bar -->
            <header class="flex items-center justify-between bg-white px-6 py-4">
                <div>
                    <IconField>
                        <InputIcon>
                            <i class="pi pi-search"/>
                        </InputIcon>
                        <InputText placeholder="Search" size="small"/>
                    </IconField>
                </div>

                <div class="flex items-center space-x-4">
                    <button
                        @click="toggle($event)"
                        class="inline-flex items-center rounded-md border border-transparent bg-white text-sm font-medium text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
                    >
                        <img
                            class="h-10 w-10 rounded-full"
                            :src="'/storage/'+profile_picture"
                            alt="Profile Picture"
                        >
                    </button>

                    <Popover ref="showPopover" class="popover-padding">
                        <div class="flex flex-col gap-4 w-[20rem]">
                            <div>
                                <span class="font-medium block mb-2">You're Account</span>
                                <ul class="flex flex-col">
                                    <DropdownLink
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                    >
                                        Log Out
                                    </DropdownLink>
                                </ul>
                            </div>
                        </div>
                    </Popover>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-6 h-screen rounded-tl-3xl shadow-[inset_0_4px_6px_0_rgba(0,0,0,0.1)] bg-zinc-100">
                <h1 class="text-2xl font-bold"></h1>
                <!-- Add your dashboard content here -->
                <slot/>
            </div>
        </div>
    </div>
</template>




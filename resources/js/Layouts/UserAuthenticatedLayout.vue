<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import {Link, usePage} from '@inertiajs/vue3';
import Popover from 'primevue/popover';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import { ref } from "vue";
import NotificationButton from "@/Components/User/NotificationButton.vue";

const showPopover = ref();
const page = usePage();

const profile_picture = page.props.auth.user.profile_photo_path;


const toggle = (event) => {
    showPopover.value.toggle(event);
}
</script>

<template>
    <div class="flex flex-col justify-between min-h-full">
        <header class="sticky top-0 z-50 shrink-0 border-b border-gray-200 bg-white">
            <div class="flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
                <div class="flex gap-6">
                    <Link :href="route('dashboard')">
                        <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800"/>
                    </Link>

                    <IconField>
                        <InputIcon class="pi pi-search" />
                        <InputText v-model="search" placeholder="Search" style="width: 70%; border-radius: 9999px;"/>
                    </IconField>
                </div>

                <div class="flex items-center justify-center">
                    <div class="relative">

                        <div align="right" class="card flex justify-center gap-6">

                            <NotificationButton />

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
                                                :href="route('profile.show')"
                                            >
                                                Profiles
                                            </DropdownLink>
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
                    </div>
                </div>

            </div>
        </header>

        <div class="flex w-full items-start gap-x-8 px-4 py-10 sm:px-6 lg:px-8">
            <!--Main Component For Welcome-->
            <slot/>
        </div>
    </div>
</template>

<style scoped>
.popover-padding {
    padding-right: 1rem; /* Adjust the padding as needed */
}
</style>

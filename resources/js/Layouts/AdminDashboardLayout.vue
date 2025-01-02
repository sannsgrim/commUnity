<script setup>

import {Link} from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import InputIcon from "primevue/inputicon";
import IconField from "primevue/iconfield";

</script>

<template>
    <div class="flex h-screen">
        <!-- Sidebar -->
        <nav class="bg-white w-20 flex flex-col items-center py-6 space-y-6">
            <Link href="/">
                <ApplicationLogo class="h-10 w-10 fill-current text-gray-500" />
            </Link>
            <div class="space-y-10 flex flex-col py-10">
                <Button icon="pi pi-home"
                        variant="text"
                        rounded
                />
                <Button icon="pi pi-check-square"
                        variant="text"
                        rounded
                />
                <Button icon="pi pi-user-plus"
                        variant="text"
                        rounded
                />
                <Button icon="pi pi-user-minus"
                        variant="text"
                        rounded
                />
            </div>
        </nav>

        <!-- Main Content -->
        <div class="flex-1 bg-white">
            <!-- Top Navigation Bar -->
            <header class="flex items-center justify-between bg-white px-6 py-4">
                <div>
                    <IconField>
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText placeholder="Search" size="small" />
                    </IconField>
                </div>

                <div class="flex items-center space-x-4">
                    <Button icon="pi pi-cog"
                            variant="text"
                            rounded
                    />
                    <img
                        src=""
                        alt="User avatar"
                        class="w-8 h-8 rounded-full"
                    />
                    <span class="text-sm">User Name</span>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-6 h-screen rounded-tl-3xl shadow-[inset_0_4px_6px_0_rgba(0,0,0,0.1)] bg-zinc-100">
                <h1 class="text-2xl font-bold"></h1>
                <!-- Add your dashboard content here -->
            </div>
        </div>
    </div>
</template>




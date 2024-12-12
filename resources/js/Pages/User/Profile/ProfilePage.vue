<template>
    <Head title="Profile Page"/>
    <UserProfileLayout>
        <div>
            <div class="px-2 pt-2">
                <div class="relative">
                    <img
                        class="h-56 w-full object-cover lg:h-64 rounded-t-lg"
                        :src="'/storage/'+profile.backgroundImage" alt="Cover Photo"
                    />
                    <button
                        class="absolute bottom-2 right-5 bg-white text-blue-600 py-2 px-3 rounded-lg gap-2 flex items-center justify-center shadow-lg hover:bg-gray-200"
                        @click="showDialogCover = true"
                    >
                        <i class="pi pi-image text-base"></i>
                        Edit Cover Photo
                    </button>
                </div>
                <Dialog header="Choose Cover Photo" v-model:visible="showDialogCover" modal>
                    <div class="flex flex-col space-y-4 p-4">
                        <button
                            @click="uploadPhoto"
                            class="w-full px-4 py-2 text-white bg-blue-600 rounded-md shadow hover:bg-blue-700"
                        >
                            <i class="pi pi-upload mr-2"></i> Upload Photo
                        </button>
                    </div>
                </Dialog>
            </div>
            <div class="mx-2 py-3 bg-[#F4F4F4]">
                <div class="mx-20 max-w-full -mt-12 sm:-mt-16 sm:flex sm:items-end sm:justify-between sm:space-x-5">
                    <div class="flex">
                        <div class="relative inline-block">
                            <!-- Profile Picture -->
                            <div class="relative">
                                <img
                                    :src="profileImage"
                                    class="w-32 h-32 rounded-full border-4 border-white shadow-md"
                                    alt="Profile Picture"
                                />
                                <button
                                    class="absolute bottom-0 right-0 bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg hover:bg-blue-700"
                                    @click="showDialogProfile = true"
                                >
                                    <i class="pi pi-camera text-base"></i>
                                </button>
                            </div>
                            <!-- Dialog Component -->
                            <Dialog header="Choose Profile Picture" v-model:visible="showDialogProfile" modal>
                                <div class="flex flex-col space-y-4 p-4">
                                    <button
                                        @click="uploadPhoto"
                                        class="w-full px-4 py-2 text-white bg-blue-600 rounded-md shadow hover:bg-blue-700"
                                    >
                                        <i class="pi pi-upload mr-2"></i> Upload Photo
                                    </button>
                                </div>
                            </Dialog>
                        </div>
                    </div>
                    <div class="mt-6 sm:flex sm:min-w-0 sm:flex-1 sm:items-center sm:justify-end sm:space-x-6 sm:pb-1">
                        <div class="mt-6 min-w-0 flex-1 sm:hidden md:block">
                            <h1 class="truncate text-2xl font-bold text-gray-900">{{ profile.name }}</h1>
                            <p class="text-sm text-gray-600">{{ profile.email }}</p>
                        </div>
                        <div class="mt-6 flex flex-col justify-stretch space-y-3 sm:flex-row sm:space-x-4 sm:space-y-0">
                            <Button
                                label="Edit Profile"
                                @click="editProfile"
                            />
                        </div>
                    </div>
                </div>
                <div class="mt-6 hidden min-w-0 flex-1 sm:block md:hidden">
                    <h1 class="truncate text-2xl font-bold text-gray-900">{{ profile.name }}</h1>
                </div>
            </div>
        </div>
    </UserProfileLayout>
</template>


<script setup>
import {Head, router} from '@inertiajs/vue3';
import UserProfileLayout from "@/Layouts/UserProfileLayout.vue";
import {ref} from "vue";
import Dialog from "primevue/dialog";

const props = defineProps({
    user: {
        type: Object,
    },
})

const profile = {
    name: props.user.first_name + ' ' + props.user.last_name,
    email: props.user.email,
    avatar: props.user.profile_photo_path,
    backgroundImage: props.user.cover_photo_path,
}
// Props
const profileImage = ref("/storage/" + profile.avatar);

// Reactive state
const showDialogProfile = ref(false);
const showDialogCover = ref(false);

// Methods
const uploadPhoto = () => {
    console.log("Upload Photo clicked");
    // Implement file upload logic here
};


const editProfile = () => {
    return router.get(route('profile.edit'));
}
</script>

<template>
    <Head title="Profile Page"/>
    <UserProfileLayout>
        <div>
            <div>
                <div class="relative">
                    <div class="relative h-56 lg:h-[300px] w-full">
                        <img
                            class="h-full w-full object-cover"
                            :src="coverImage" alt="Cover Photo"
                        />
                        <div class="absolute top-0 left-0 w-full h-1/2 bg-gradient-to-b from-black opacity-70"></div>
                    </div>

                    <button
                        class="absolute bottom-2 right-5 bg-white text-blue-600 py-2 px-3 rounded-lg gap-2 flex items-center justify-center shadow-lg hover:bg-gray-200"
                        @click="showDialogCover = true"
                    >
                        <i class="pi pi-image text-base"></i>
                        Edit Cover Photo
                    </button>
                </div>
                <Dialog header="Choose Cover Photo" v-model:visible="showDialogCover" modal class="w-1/2 max-w-2xl"
                        :draggable="false">
                    <div class="flex flex-col space-y-6 p-6 items-center bg-white rounded-lg shadow-lg">
                        <div v-if="!CoverImagePreview" class="w-full"
                             @dragover.prevent
                             @drop="handleCoverDrop"
                        >
                            <div class="col-span-full">
                                <div
                                    class="mt-2 flex justify-center rounded-lg border-2 border-dashed border-gray-300 px-6 py-10 bg-gray-50"
                                >
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" viewBox="0 0 24 24"
                                             fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                  d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                        <div class="mt-4 flex text-sm text-gray-600">
                                            <label for="cover-file-upload"
                                                   class="relative cursor-pointer rounded-md bg-white font-semibold text-blue-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-600 focus-within:ring-offset-2 hover:text-blue-500">
                                                <span>Upload a file</span>
                                                <input id="cover-file-upload" name="cover-file-upload" type="file"
                                                       class="sr-only" @change="previewCoverImage">
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-600">PNG, JPG, GIF up to 10MB</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="CoverImagePreview" class="relative inline-block">
                            <div class="relative">
                                <img :src="CoverImagePreview" alt="Image Preview"
                                     class="w-auto h-80 object-cover rounded-lg">
                                <button @click="clearImagePreview"
                                        class="absolute top-0 right-0 bg-red-600/25 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg
                                                                      hover:bg-red-600/70"
                                >
                                    <i class="pi pi-times text-base"></i>
                                </button>
                            </div>
                        </div>

                        <button
                            @click="uploadCoverPhoto"
                            class="w-full px-4 py-2 text-white bg-blue-600 rounded-md shadow hover:bg-blue-700"
                        >
                            <i class="pi pi-upload mr-2"></i> Upload Photo
                        </button>
                    </div>
                </Dialog>

            </div>

            <div class="py-3 bg-[#F4F4F4]">
                <div class="mx-20 max-w-full -mt-12 sm:-mt-16 sm:flex sm:items-end sm:justify-between sm:space-x-5">
                    <div class="flex">
                        <div class="relative inline-block">
                            <!-- Profile Picture -->
                            <div class="relative">
                                <img
                                    :src="profileImage"
                                    class="w-32 h-32 rounded-full border-4 border-white shadow-md object-cover"
                                    alt="Profile Picture"
                                />
                                <button
                                    class="absolute bottom-0 right-0 bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg hover:bg-blue-700"
                                    @click="showDialogProfile = true"
                                >
                                    <i class="pi pi-camera text-base"></i>
                                </button>
                            </div>

                            <!-- Profile Picture Dialog -->
                            <Dialog header="Choose Profile Picture" v-model:visible="showDialogProfile" modal
                                    class="w-1/2 max-w-2xl" :draggable="false">
                                <div class="flex flex-col space-y-6 p-6 items-center bg-white rounded-lg shadow-lg">

                                    <!--Drag-and-Drop Zone-->
                                    <div v-if="!ProfileImagePreview" class="w-full"
                                         @dragover.prevent
                                         @drop="handleDrop"
                                    >
                                        <div class="col-span-full">
                                            <div
                                                class="mt-2 flex justify-center rounded-lg border-2 border-dashed border-gray-300 px-6 py-10 bg-gray-50"
                                            >
                                                <div class="text-center">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" viewBox="0 0 24 24"
                                                         fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd"
                                                              d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z"
                                                              clip-rule="evenodd"/>
                                                    </svg>
                                                    <div class="mt-4 flex text-sm text-gray-600">
                                                        <label for="file-upload"
                                                               class="relative cursor-pointer rounded-md bg-white font-semibold text-blue-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-600 focus-within:ring-offset-2 hover:text-blue-500">
                                                            <span>Upload a file</span>
                                                            <input id="file-upload" name="file-upload" type="file"
                                                                   class="sr-only" @change="previewImage">
                                                        </label>
                                                        <p class="pl-1">or drag and drop</p>
                                                    </div>
                                                    <p class="text-xs text-gray-600">PNG, JPG, GIF up to 10MB</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="ProfileImagePreview" class="relative inline-block">
                                        <div class="relative">
                                            <img :src="ProfileImagePreview" alt="Image Preview"
                                                 class="w-auto h-80 object-cover rounded-lg">
                                            <button @click="clearImageProfilePreview"
                                                    class="absolute top-0 right-0 bg-red-600/25 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg
                                                                      hover:bg-red-600/70"
                                            >
                                                <i class="pi pi-times text-base"></i>
                                            </button>
                                        </div>
                                    </div>


                                    <Button
                                        label="Upload Photo"
                                        icon="pi pi-upload"
                                        class="w-full px-4 py-2 text-white bg-blue-600 rounded-md shadow"
                                        :disabled="ProfileImagePreview === null"
                                        @click="uploadProfilePhoto"
                                    />

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

        <div class="flex flex-col items-center justify-center">
            <div v-for="post in posts.data" :key="post.id" class=" w-1/2">
                <div class="bg-white rounded-lg px-10 pt-7 pb-5 shadow flex flex-col justify-between h-full">
                    <div>
                        <div class="flex items-center gap-2">
                            <img :src="'/storage/'+ post.user_details[0].profile_photo_path" alt="Profile"
                                 class="h-10 w-10 rounded-full">
                            <div>
                                <p class="font-semibold">
                                    {{ post.user_details[0].first_name }} {{ post.user_details[0].last_name }}
                                </p>
                                <p class="text-gray-500 text-sm">
                                    {{ formatDate(post.created_at) }} {{ post.id }}
                                </p>
                            </div>
                        </div>
                        <p class="mt-4 text-justify">
                            {{ post.caption }}
                        </p>

                        <div class="px-5 lg:px-16 mt-4">
                            <Galleria v-model:activeIndex="post.activeIndex"
                                      v-model:visible="post.displayCustom"
                                      :value="post.images.map(image => ({ src: '/storage/' + image.image_path, alt: 'Post Image' }))"
                                      :responsiveOptions="responsiveOptions"
                                      :numVisible="5"
                                      containerStyle="max-width: 850px"
                                      :circular="true"
                                      :fullScreen="true"
                                      :showItemNavigators="true"
                                      :showThumbnails="false">
                                <template #item="slotProps">
                                    <img :src="slotProps.item.src" :alt="slotProps.item.alt"
                                         style="width: 100%; display: block"/>
                                </template>
                            </Galleria>

                            <div v-if="post.images.length === 1" class="flex flex-col">
                                <img :src="'/storage/' + post.images[0].image_path" alt="Post Image"
                                     class="w-full h-[650px] object-cover rounded-lg cursor-pointer"
                                     @click="imageClick(post, 0)"/>
                            </div>
                            <div v-else-if="post.images.length === 2" class="flex flex-col gap-2 w-full">
                                <div v-for="(image, index) in post.images.slice(0, 2)" :key="index">
                                    <img :src="'/storage/' + image.image_path" alt="Post Image"
                                         class="w-full h-[325px] object-cover rounded-lg cursor-pointer"
                                         @click="imageClick(post, index)"/>
                                </div>
                            </div>
                            <div v-else-if="post.images.length === 3" class="flex flex-col gap-2 w-full">
                                <div v-for="(image, index) in post.images.slice(0, 1)" :key="index">
                                    <img :src="'/storage/' + image.image_path" alt="Post Image"
                                         class="w-full h-[325px] object-cover rounded-lg cursor-pointer"
                                         @click="imageClick(post, 0)"/>
                                </div>
                                <div class="flex flex-row gap-2">
                                    <div v-for="(image, index) in post.images.slice(1, 3)" :key="index">
                                        <img :src="'/storage/' + image.image_path" alt="Post Image"
                                             class="w-[590px] h-[325px] object-cover rounded-lg cursor-pointer"
                                             @click="imageClick(post, index + 1)"/>
                                    </div>
                                </div>
                            </div>
                            <div v-else-if="post.images.length === 4" class="flex flex-row gap-2 w-full">
                                <div v-for="(image, index) in post.images.slice(0, 1)" :key="index">
                                    <img :src="'/storage/' + image.image_path" alt="Post Image"
                                         class="w-[590px] h-[664px] object-cover rounded-lg cursor-pointer"
                                         @click="imageClick(post, 0)"/>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <div v-for="(image, index) in post.images.slice(1, 4)" :key="index">
                                        <img :src="'/storage/' + image.image_path" alt="Post Image"
                                             class="w-[590px] h-[216px] object-cover rounded-lg cursor-pointer"
                                             @click="imageClick(post, index + 1)"/>
                                    </div>
                                </div>
                            </div>
                            <div v-else-if="post.images.length === 5" class="flex flex-col gap-2 w-full">
                                <div class="flex flex-row gap-2">
                                    <div v-for="(image, index) in post.images.slice(0, 2)" :key="index">
                                        <img :src="'/storage/' + image.image_path" alt="Post Image"
                                             class="w-[590px] h-[325px] object-cover rounded-lg cursor-pointer"
                                             @click="imageClick(post, index)"/>
                                    </div>
                                </div>
                                <div class="flex flex-row gap-2">
                                    <div v-for="(image, index) in post.images.slice(2, 5)" :key="index">
                                        <img :src="'/storage/' + image.image_path" alt="Post Image"
                                             class="w-[393px] h-[325px] object-cover rounded-lg cursor-pointer"
                                             @click="imageClick(post, index + 2)"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="flex justify-start gap-2 mt-7 w-full">
                        <Button :label="post.user_vote === 'up' ? 'Liked' : 'Like'"
                                :icon="post.user_vote === 'up' ? 'pi pi-thumbs-up-fill' : 'pi pi-thumbs-up'"
                                variant="text"
                                :badge="String(post.up_votes)"
                                @click="handleUpvote(post)"/>
                        <Button :label="post.user_vote === 'down' ? 'Disliked' : 'Dislike'"
                                :icon="post.user_vote === 'down' ? 'pi pi-thumbs-down-fill' : 'pi pi-thumbs-down'"
                                variant="text"
                                :badge="String(post.down_votes)"
                                @click="handleDownvote(post)"/>
                        <Button label="Comment" icon="pi pi-comment" variant="text" :badge="String(post.comments_count)"
                                @click="handleComment(post)"/>
                    </div>

                </div>
                <!-- Comment Dialog -->
                <CommentDialog v-model:isVisible="isCommentDialogOpen" :post="selectedPost" @upvote="handleUpvoteFromDialog"
                               @downvote="handleDownvoteFromDialog"/>


            </div>
        </div>


    </UserProfileLayout>
</template>


<script setup>
import {Head, router, usePage} from '@inertiajs/vue3';
import UserProfileLayout from "@/Layouts/UserProfileLayout.vue";
import {ref} from "vue";
import Dialog from "primevue/dialog";
import {useToast} from "primevue/usetoast";
import axios from "axios";
import moment from "moment";
import CommentDialog from "@/Components/CommentDialog.vue";

const toast = useToast();
const page = usePage();

const props = defineProps({
    user: {
        type: Object,
    },
    posts: {
        type: Object,
    }
})

const profile = {
    name: props.user.first_name + ' ' + props.user.last_name,
    email: props.user.email,
    avatar: props.user.profile_photo_path,
    backgroundImage: props.user.cover_photo_path,
}
// Props
const profileImage = ref("/storage/" + profile.avatar);
const coverImage = ref("/storage/" + profile.backgroundImage);

// Reactive state
const showDialogCover = ref(false);
const CoverImagePreview = ref(null);
const selectedCoverImageFile = ref(null); // Store the selected image file
const showDialogProfile = ref(false);
const ProfileImagePreview = ref(null);
const selectedImageFile = ref(null); // Store the selected image file

// Methods for drag-and-drop functionality
const previewImage = (event) => {
    const file = event.target.files[0];
    if (file) {
        const validTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            console.warn("Invalid file type:", file.type);
            return;
        }
        if (file.size > 10 * 1024 * 1024) { // 10MB in bytes
            console.warn("File size exceeds 10MB:", file.size);
            return;
        }
        console.log(file);
        selectedImageFile.value = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            ProfileImagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const handleDrop = (event) => {
    event.preventDefault();
    console.log("Drop event triggered");
    const file = event.dataTransfer.files[0];
    const message = "Please Try Again";
    if (file) {
        const validTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            toast.add({severity: 'warn', summary: 'Invalid file type', detail: message});
            console.warn("Invalid file type:", file.type);
            return;
        }
        if (file.size > 10 * 1024 * 1024) { // 10MB in bytes
            toast.add({severity: 'warn', summary: 'File size exceeds 10MB', detail: message});
            console.warn("File size exceeds 10MB:", file.size);
            return;
        }
        console.log("File dropped:", file);
        selectedImageFile.value = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            console.log("File read successfully");
            ProfileImagePreview.value = e.target.result;
        };
        reader.onerror = (e) => {
            console.error("File reading error:", e);
        };
        reader.readAsDataURL(file);
    } else {
        console.warn("No file detected in drop event");
    }
};

const previewCoverImage = (event) => {
    const file = event.target.files[0];
    if (file) {
        const validTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            console.warn("Invalid file type:", file.type);
            return;
        }
        if (file.size > 10 * 1024 * 1024) { // 10MB in bytes
            console.warn("File size exceeds 10MB:", file.size);
            return;
        }
        console.log(file);
        selectedCoverImageFile.value = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            CoverImagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const handleCoverDrop = (event) => {
    event.preventDefault();
    console.log("Drop event triggered");
    const file = event.dataTransfer.files[0];
    const message = "Please Try Again";
    if (file) {
        const validTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            toast.add({severity: 'warn', summary: 'Invalid file type', detail: message});
            console.warn("Invalid file type:", file.type);
            return;
        }
        if (file.size > 10 * 1024 * 1024) { // 10MB in bytes
            toast.add({severity: 'warn', summary: 'File size exceeds 10MB', detail: message});
            console.warn("File size exceeds 10MB:", file.size);
            return;
        }
        console.log("File dropped:", file);
        selectedCoverImageFile.value = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            console.log("File read successfully");
            CoverImagePreview.value = e.target.result;
        };
        reader.onerror = (e) => {
            console.error("File reading error:", e);
        };
        reader.readAsDataURL(file);
    } else {
        console.warn("No file detected in drop event");
    }
};

const clearImagePreview = () => {
    CoverImagePreview.value = null;
};

const clearImageProfilePreview = () => {
    ProfileImagePreview.value = null;
};


//Link
const editProfile = () => {
    return router.get(route('profile.edit'));
}

//Axios Request Links
const uploadProfilePhoto = async () => {
    const formData = new FormData();
    formData.append('profile_image', selectedImageFile.value);

    try {
        const response = await axios.post(route('profile.update_image'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        profileImage.value = "/storage/" + response.data.profile_photo_path;
        page.props.auth.user.profile_photo_path = response.data.profile_photo_path;
        showDialogProfile.value = false;
        ProfileImagePreview.value = null;
        toast.add({
            severity: 'success',
            summary: 'Success',
            detail: 'Profile image updated successfully.',
            life: 3000
        });
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to update the profile image.',
            life: 3000
        });
    }
};

const uploadCoverPhoto = async () => {
    const formData = new FormData();
    formData.append('cover_image', selectedCoverImageFile.value);

    try {
        const response = await axios.post(route('profile_cover.update_image'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        coverImage.value = "/storage/" + response.data.cover_photo_path;
        page.props.auth.user.cover_photo_path = response.data.cover_photo_path;
        showDialogCover.value = false;
        CoverImagePreview.value = null;
        toast.add({
            severity: 'success',
            summary: 'Success',
            detail: 'Cover photo updated successfully.',
            life: 3000
        });
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to update the cover photo.',
            life: 3000
        });
    }
};

//post
const formatDate = (date) => {
    const now = moment();
    const postDate = moment(date);

    if (now.diff(postDate, 'days') > 7) {
        return postDate.format('MMMM D [at] h:mm A');
    } else {
        return postDate.fromNow();
    }
};
const imageClick = (post, index) => {
    post.activeIndex = index;
    post.displayCustom = true;
};
const handleUpvote = async (post) => {
    try {
        const response = await axios.post(route('post.up_vote.trigger', {post: post.id}));
        post.up_votes = response.data.up_votes;
        post.down_votes = response.data.down_votes;
        post.user_vote = response.data.user_vote;
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to upvote the post.',
            life: 3000
        });
    }
};
const handleDownvote = async (post) => {
    try {
        const response = await axios.post(route('post.down_vote.trigger', {post: post.id}));
        post.up_votes = response.data.up_votes;
        post.down_votes = response.data.down_votes;
        post.user_vote = response.data.user_vote;
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to downvote the post.',
            life: 3000
        });
    }
};
const openCommentDialog = (post) => {
    selectedPost.value = post;
    isCommentDialogOpen.value = true;
};
const handleUpvoteFromDialog = (post) => {
    handleUpvote(post);
};
const handleDownvoteFromDialog = (post) => {
    handleDownvote(post);
};
const handleComment = (post) => {
    openCommentDialog(post);F
};
const isCommentDialogOpen = ref(false);

const selectedPost = ref(null);
</script>

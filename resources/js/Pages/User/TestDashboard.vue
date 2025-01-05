<script setup>
import { ref } from "vue";
import { useToast } from "primevue/usetoast";
import { useIntersectionObserver } from '@vueuse/core'
import { Head, usePage, useForm } from '@inertiajs/vue3';
import Toast from 'primevue/toast';
import Dialog from 'primevue/dialog';
import Textarea from 'primevue/textarea';
import UserAuthenticatedLayout from "@/Layouts/UserAuthenticatedLayout.vue";
import moment from 'moment';
import axios from 'axios';
import CommentDialog from "@/Components/CommentDialog.vue";

// Reactive state
const page = usePage();
const profile_picture = page.props.auth.user.profile_photo_path;
const username = page.props.auth.user.first_name + ' ' + page.props.auth.user.last_name;
const isDialogOpen = ref(false);
const postText = ref("");
const images = ref([]);
const toast = useToast();
const isCommentDialogOpen = ref(false);
const selectedPost = ref(null);

const removeImage = (index) => {
    images.value.splice(index, 1);
};

// Open and close dialog
const openDialog = () => {
    isDialogOpen.value = true;
};

const closeDialog = () => {
    isDialogOpen.value = false;
};

// Handle image upload
const handleImageUpload = (event) => {
    const files = Array.from(event.target.files);
    const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];

    if (images.value.length + files.length > 5) {
        toast.add({
            severity: 'warn',
            summary: 'Image Upload Limit',
            detail: 'You can only upload up to 5 images.',
            life: 3000
        });
        return;
    }

    files.forEach((file) => {
        if (!allowedTypes.includes(file.type)) {
            toast.add({
                severity: 'warn',
                summary: 'Invalid File Type',
                detail: `The file "${file.name}" is not a valid image type.`,
                life: 3000
            });
            return;
        }

        const fileName = file.name;
        const isDuplicate = images.value.some(image => image.name === fileName);

        if (isDuplicate) {
            toast.add({
                severity: 'warn',
                summary: 'Duplicate File',
                detail: `The file "${fileName}" has already been uploaded.`,
                life: 3000
            });
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            images.value.push({name: fileName, src: e.target.result});
        };
        reader.readAsDataURL(file);
    });

    event.target.value = null;
};

// Submit post
const submitPost = async () => {
    const form = useForm({
        postText: postText.value,
        imagePaths: images.value
    });

    try {
        // Upload images first
        form.post(route('test.post.store'), {
            onSuccess: () => {
                console.log("Post submitted:", form);
                closeDialog();
            },
            onError: (errors) => {
                console.error("Error submitting post:", errors);
            }
        });
    } catch (error) {
        console.error("Error uploading images:", error);
    }
};

const props = defineProps({
    posts: Object
})

props.posts.data.forEach(post => {
    post.activeIndex = 0;
    post.displayCustom = false;
});

const responsiveOptions = ref([
    {
        breakpoint: '1024px',
        numVisible: 5
    },
    {
        breakpoint: '768px',
        numVisible: 3
    },
    {
        breakpoint: '560px',
        numVisible: 1
    }
]);

const imageClick = (post, index) => {
    post.activeIndex = index;
    post.displayCustom = true;
};

const formatDate = (date) => {
    const now = moment();
    const postDate = moment(date);

    if (now.diff(postDate, 'days') > 7) {
        return postDate.format('MMMM D [at] h:mm A');
    } else {
        return postDate.fromNow();
    }
};

const triggerRequest = ref(null);


const {stop} = useIntersectionObserver(triggerRequest, ([{isIntersecting}]) => {

    if (!isIntersecting) {
        return;
    }


    axios.get(`${props.posts.meta.path}?cursor=${props.posts.meta.next_cursor}`).then((response) => {

        props.posts.data = [...props.posts.data, ...response.data.data];

        props.posts.meta = response.data.meta;

        if (!response.data.meta.next_cursor) {
            stop();
        }

    });

});

//Handle Votes & Comments
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

// Open and close comment dialog
const openCommentDialog = (post) => {
    selectedPost.value = post;
    isCommentDialogOpen.value = true;
};

// Handle comment button click
const handleComment = (post) => {
    openCommentDialog(post);
};

// Handle upvote and downvote from CommentDialog
const handleUpvoteFromDialog = (post) => {
    handleUpvote(post);
};
const handleDownvoteFromDialog = (post) => {
    handleDownvote(post);
};
</script>

<template>
    <Head title="Dashboard"/>
    <UserAuthenticatedLayout>
        <Toast/>


        <main class="flex-1 bg-[#F4F4F4] rounded-xl p-10 space-y-8">

            <!-- Main area -->
            <div class="bg-white rounded-lg p-10 shadow">
                <div class="flex flex-row gap-4">
                    <img :src="'/storage/'+profile_picture" alt="Profile" class="h-10 w-10 rounded-full">
                    <!-- Textarea to open the modal -->
                    <Textarea
                        placeholder="What's on your mind?"
                        @click="openDialog"
                        autoResize
                        readonly
                        class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    </Textarea>
                </div>

                <!-- PrimeVue Dialog -->
                <Dialog
                    v-model:visible="isDialogOpen"
                    header="Create post"
                    :modal="true"
                    :position="'center'"
                    :draggable="false"
                    style="width: 700px"
                    :closable="false"
                    class="rounded-lg"
                >
                    <!-- Textarea for Post Content -->
                    <div class="flex flex-col gap-3">n
                        <div class="flex flex-row gap-3 items-center">
                            <img :src="'/storage/'+profile_picture" alt="Profile" class="h-10 w-10 rounded-full">
                            <div>
                                <p class="font-semibold">{{ username }}</p>
                            </div>
                        </div>
                        <Textarea
                            autoResize
                            v-model="postText"
                            rows="3"
                            class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4"
                            placeholder="Write something...">
                        </Textarea>
                    </div>

                    <!-- Custom File Upload -->
                    <div class="border border-gray-300 p-4 rounded mb-4 relative">
                        <input
                            type="file"
                            multiple
                            ref="fileInput"
                            @change="handleImageUpload"
                            accept=".png, .jpeg, .jpg"
                            class="absolute inset-0 opacity-0 cursor-pointer"
                        />
                        <div class="flex items-center justify-center text-gray-600">
                            <span class="pi pi-image mr-2"></span>
                            <span>Add Photos</span>
                        </div>
                    </div>

                    <div class="flex flex-col items-center justify-center">
                        <!-- Image Previews -->
                        <div class="flex flex-wrap gap-2 justify-center items-center ">
                            <template v-if="images.length === 1">
                                <div class="w-[450px] h-[466px] relative">
                                    <img :src="images[0].src" alt="Preview"
                                         class="w-full h-full object-cover rounded shadow"/>
                                    <Button
                                        icon="pi pi-times"
                                        style="top: 8px; right: 8px;"
                                        class="p-button-rounded p-button-secondary p-button-sm !absolute"
                                        @click="removeImage(0)"
                                    />
                                </div>
                            </template>
                            <template v-else-if="images.length === 2">
                                <div class="flex flex-col gap-2 justify-center items-center">

                                    <div class="w-[450px] h-[232px] relative">
                                        <img :src="images[0].src" alt="Preview"
                                             class="w-full h-full object-cover rounded shadow"/>
                                        <Button
                                            icon="pi pi-times"
                                            style="top: 8px; right: 8px;"
                                            class="p-button-rounded p-button-secondary p-button-sm !absolute"
                                            @click="removeImage(0)"
                                        />
                                    </div>

                                    <div class="w-[450px] h-[232px] relative">
                                        <img :src="images[1].src" alt="Preview"
                                             class="w-full h-full object-cover rounded shadow"/>
                                        <Button
                                            icon="pi pi-times"
                                            style="top: 8px; right: 8px;"
                                            class="p-button-rounded p-button-secondary p-button-sm !absolute"
                                            @click="removeImage(1)"
                                        />
                                    </div>

                                </div>
                            </template>
                            <template v-else-if="images.length === 3">
                                <div class="flex flex-col gap-2 w-full h-full justify-center items-center">

                                    <div class="w-[452px] h-[232px] relative">
                                        <img :src="images[0].src" alt="Preview"
                                             class="w-full h-full object-cover rounded shadow"/>
                                        <Button
                                            icon="pi pi-times"
                                            style="top: 8px; right: 8px;"
                                            class="p-button-rounded p-button-secondary p-button-sm !absolute"
                                            @click="removeImage(0)"
                                        />
                                    </div>

                                    <div class="flex h-1/2 gap-2">

                                        <div class="w-[224px] h-[232px] relative">
                                            <img :src="images[1].src" alt="Preview"
                                                 class="w-full h-full object-cover rounded shadow"/>
                                            <Button
                                                icon="pi pi-times"
                                                style="top: 8px; right: 8px;"
                                                class="p-button-rounded p-button-secondary p-button-sm !absolute"
                                                @click="removeImage(1)"
                                            />
                                        </div>

                                        <div class="w-[224px] h-[232px] relative">
                                            <img :src="images[2].src" alt="Preview"
                                                 class="w-full h-full object-cover rounded shadow"/>
                                            <Button
                                                icon="pi pi-times"
                                                style="top: 8px; right: 8px;"
                                                class="p-button-rounded p-button-secondary p-button-sm !absolute"
                                                @click="removeImage(2)"
                                            />
                                        </div>

                                    </div>
                                </div>
                            </template>
                            <template v-else-if="images.length === 4">
                                <div class="flex flex-row w-full h-full gap-2 justify-center items-center">

                                    <div class="w-[299px] h-[478px] relative">
                                        <img :src="images[0].src" alt="Preview"
                                             class="w-full h-full object-cover rounded shadow"/>
                                        <Button
                                            icon="pi pi-times"
                                            style="top: 8px; right: 8px;"
                                            class="p-button-rounded p-button-secondary p-button-sm !absolute"
                                            @click="removeImage(0)"
                                        />
                                    </div>

                                    <div class="flex flex-col gap-2">

                                        <div class="w-[149px] h-[154px] relative">
                                            <img :src="images[1].src" alt="Preview"
                                                 class="w-full h-full object-cover rounded shadow"/>
                                            <Button
                                                icon="pi pi-times"
                                                style="top: 8px; right: 8px;"
                                                class="p-button-rounded p-button-secondary p-button-sm !absolute"
                                                @click="removeImage(1)"
                                            />
                                        </div>

                                        <div class="w-[148px] h-[154px] relative">
                                            <img :src="images[2].src" alt="Preview"
                                                 class="w-full h-full object-cover rounded shadow"/>
                                            <Button
                                                icon="pi pi-times"
                                                style="top: 8px; right: 8px;"
                                                class="p-button-rounded p-button-secondary p-button-sm !absolute"
                                                @click="removeImage(2)"
                                            />
                                        </div>

                                        <div class="w-[149px] h-[154px] relative">
                                            <img :src="images[3].src" alt="Preview"
                                                 class="w-full h-full object-cover rounded shadow"/>
                                            <Button
                                                icon="pi pi-times"
                                                style="top: 8px; right: 8px;"
                                                class="p-button-rounded p-button-secondary p-button-sm !absolute"
                                                @click="removeImage(3)"
                                            />
                                        </div>

                                    </div>
                                </div>
                            </template>
                            <template v-else-if="images.length === 5">
                                <div class="flex flex-col gap-2 justify-center items-center">
                                    <div class="flex flex-row gap-2">

                                        <div class="w-[226px] h-[232px] relative">
                                            <img :src="images[0].src" alt="Preview"
                                                 class="w-full h-full object-cover rounded shadow"/>
                                            <Button
                                                icon="pi pi-times"
                                                style="top: 8px; right: 8px;"
                                                class="p-button-rounded p-button-secondary p-button-sm !absolute"
                                                @click="removeImage(0)"
                                            />
                                        </div>

                                        <div class="w-[226px] h-[232px] relative">
                                            <img :src="images[1].src" alt="Preview"
                                                 class="w-full h-full object-cover rounded shadow"/>
                                            <Button
                                                icon="pi pi-times"
                                                style="top: 8px; right: 8px;"
                                                class="p-button-rounded p-button-secondary p-button-sm !absolute"
                                                @click="removeImage(1)"
                                            />
                                        </div>

                                    </div>
                                    <div class="flex flex-row gap-2">
                                        <div class="w-[149px] h-[154px] relative">
                                            <img :src="images[2].src" alt="Preview"
                                                 class="w-full h-full object-cover rounded shadow"/>
                                            <Button
                                                icon="pi pi-times"
                                                style="top: 8px; right: 8px;"
                                                class="p-button-rounded p-button-secondary p-button-sm !absolute"
                                                @click="removeImage(2)"
                                            />
                                        </div>

                                        <div class="w-[148px] h-[154px] relative">
                                            <img :src="images[3].src" alt="Preview"
                                                 class="w-full h-full object-cover rounded shadow"/>
                                            <Button
                                                icon="pi pi-times"
                                                style="top: 8px; right: 8px;"
                                                class="p-button-rounded p-button-secondary p-button-sm !absolute"
                                                @click="removeImage(3)"
                                            />
                                        </div>

                                        <div class="w-[149px] h-[154px] relative">
                                            <img :src="images[4].src" alt="Preview"
                                                 class="w-full h-full object-cover rounded shadow"/>
                                            <Button
                                                icon="pi pi-times"
                                                style="top: 8px; right: 8px;"
                                                class="p-button-rounded p-button-secondary p-button-sm !absolute"
                                                @click="removeImage(4)"
                                            />
                                        </div>

                                    </div>
                                </div>
                            </template>

                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="mt-4 flex justify-end gap-2 space-x-4">
                        <button
                            @click="closeDialog"
                            class="bg-gray-300 text-gray-800 py-2 px-4 rounded hover:bg-gray-200"
                        >
                            Cancel
                        </button>
                        <button
                            @click="submitPost"
                            class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700"
                        >
                            Post
                        </button>
                    </div>
                </Dialog>
            </div>

            <!-- Posts -->
            <div v-for="post in posts.data" :key="post.id">
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
                                         style="width: 100%; display: block" />
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
            </div>

            <!-- Comment Dialog -->
            <CommentDialog v-model:isVisible="isCommentDialogOpen" :post="selectedPost" @upvote="handleUpvoteFromDialog"
                           @downvote="handleDownvoteFromDialog"/>

            <!-- For Trigger Infinite -->
            <div ref="triggerRequest" class="-translate-y-96"></div>

        </main>
    </UserAuthenticatedLayout>
</template>

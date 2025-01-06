<template>
    <AdminDashboardLayout>

        <div class="flex flex-col items-center justify-center gap-6">
            <div v-for="post in posts.data" :key="post.id" class="w-3/2">
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
                            <Button
                                icon="pi pi-trash"
                                severity="danger"
                                rounded
                                raised
                                class="ml-auto"
                                aria-label="Delete"
                                @click="post.visible = true">
                            </Button>
                        </div>
                        <p class="mt-4 text-justify">
                            {{ post.caption }}
                        </p>

                        <Dialog v-model:visible="post.visible" header="Delete Account" :style="{ width: '25.5rem' }" modal class="backdrop-blur-xl">
                            <span class="text-surface-500 dark:text-surface-400 block mb-8">Are you sure to delete this account?</span>
                            <div class="flex justify-end gap-2">
                                <Button
                                    type="button"
                                    label="Cancel"
                                    severity="secondary"
                                    @click="post.visible = false">
                                </Button>
                                <Button
                                    type="button"
                                    label="Delete"
                                    severity="danger"
                                    @click="post.visible = false">
                                </Button>
                            </div>
                        </Dialog>

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

            </div>

            <!-- Comment Dialog -->
            <CommentDialog v-model:isVisible="isCommentDialogOpen" :post="selectedPost" @upvote="handleUpvoteFromDialog"
                           @downvote="handleDownvoteFromDialog"/>

        </div>
    </AdminDashboardLayout>
</template>

<script setup>
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import CommentDialog from "@/Components/CommentDialog.vue";
import moment from "moment";
import axios from "axios";
import {ref} from "vue";

const props = defineProps({
    posts: {
        type: Object,
    }
})

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
const handleDownvoteFromDialog = (post) => {f
    handleDownvote(post);
};
const handleComment = (post) => {
    openCommentDialog(post);
};
const isCommentDialogOpen = ref(false);

const selectedPost = ref(null);

props.posts.data.forEach(post => {
    post.visible = ref(false);
});
const deletePost = async (post) => {
    post.visible = false;
};
</script>

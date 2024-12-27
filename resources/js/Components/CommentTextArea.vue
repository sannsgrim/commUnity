<template>
    <div class="flex items-start space-x-4">
        <div class="flex-shrink-0">
            <img :src="'/storage/'+ post.user_details[0].profile_photo_path" alt="Profile" class="h-10 w-10 rounded-full">
        </div>
        <div class="min-w-0 flex-1">

            <form @submit.prevent="submitComment" class="relative">
                <div class="overflow-hidden rounded-lg shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-indigo-600">
                    <label for="comment" class="sr-only">Add your comment</label>
                    <Textarea
                        fluid
                        v-model="comment"
                        autoResize rows="1"
                        placeholder="Add your comment..."
                        style="border: none; background-color: transparent; box-shadow: none;"
                    />

                    <!-- Spacer element to match the height of the toolbar -->
                    <div class="py-2" aria-hidden="true">
                        <!-- Matches height of button in toolbar (1px border + 36px content height) -->
                        <div class="py-px">
                            <div class="h-9" />
                        </div>
                    </div>
                </div>

                <div class="absolute inset-x-0 bottom-0 flex justify-between py-2 pl-3 pr-2">
                    <div class="flex items-center space-x-5">
                        <div class="flex items-center">

                        </div>
                        <div class="flex items-center">

                        </div>
                    </div>
                    <div
                        class="flex-shrink-0">
                        <Button
                            rounded
                            type="submit"
                            variant="text"
                            icon="pi pi-send"
                            :disabled="!comment"
                            class="items-center"
                            :class="{ '!cursor-not-allowed': !comment }"
                        />
                    </div>
                </div>
            </form>

        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    post: Object
});

const comment = ref(null);

const submitComment = async () => {
    if (!comment.value) return;

    try {
        await axios.post(route('comments.store'), {
            post_id: props.post.id,
            content: comment.value
        });
        comment.value = '';
    } catch (error) {
        console.error('Error submitting comment:', error);
    }
};

</script>

<style scoped>
:root {
    --p-textarea-border-color: transparent;
    --p-textarea-hover-border-color: transparent;
    --p-textarea-focus-border-color: transparent;
    --p-textarea-invalid-border-color: transparent;
}
</style>

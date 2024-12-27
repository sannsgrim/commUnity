<template>
  <Dialog
      v-model:visible="localVisible"
      :header="post ? post.user_details[0].first_name + '\'s Posts' : ''"
      modal
      :position="'center'"
      :draggable="false"
      style="width: 700px; max-width: 90%; border-radius: 12px; height: 874px;"
      class="rounded-lg shadow-lg">

    <!-- Content Section -->
    <ScrollPanel style="width: 100%; height: 100%">
      <div v-if="post" class="relative">

        <!-- User and Caption Details-->
        <div class="flex items-center gap-2">
          <img :src="'/storage/'+ post.user_details[0].profile_photo_path" alt="Profile" class="h-10 w-10 rounded-full">
          <div>
            <p class="font-semibold">
              {{ post.user_details[0].first_name }} {{ post.user_details[0].last_name }}
            </p>
            <p class="text-gray-500 text-sm">
              {{ formatDate(post.created_at) }} {{ post.id }}
            </p>
          </div>
        </div>
        <p class="mt-4 mb-2 text-justify">
          {{ post.caption }}
        </p>

        <!-- Post Image -->
        <div class="relative">
          <Galleria v-model:activeIndex="post.activeIndex"
                    v-model:visible="post.displayCustom"
                    :value="post.images.map(image => ({ src: '/storage/' + image.image_path, alt: 'Post Image' }))"
                    :responsiveOptions="responsiveOptions"
                    :numVisible="7" :circular="true"
                    :fullScreen="true"
                    :showItemNavigators="true"
                    :showThumbnails="false">
            <template #item="slotProps">
              <img :src="slotProps.item.src" :alt="slotProps.item.alt"
                   class="w-full h-[650px] object-cover rounded-lg"/>
            </template>
          </Galleria>

          <div v-if="post.images.length === 1" class="flex flex-col">
            <img :src="'/storage/' + post.images[0].image_path" alt="Post Image"
                 class="w-full h-[550px] object-cover rounded-lg cursor-pointer" @click="imageClick(post, 0)"/>
          </div>
          <div v-else-if="post.images.length === 2" class="flex flex-col gap-2 w-full">
            <div v-for="(image, index) in post.images.slice(0, 2)" :key="index">
              <img :src="'/storage/' + image.image_path" alt="Post Image"
                   class="w-full h-[275px] object-cover rounded-lg cursor-pointer" @click="imageClick(post, index)"/>
            </div>
          </div>
          <div v-else-if="post.images.length === 3" class="flex flex-col gap-2 w-full">
            <div v-for="(image, index) in post.images.slice(0, 1)" :key="index">
              <img :src="'/storage/' + image.image_path" alt="Post Image"
                   class="w-full h-[275px] object-cover rounded-lg cursor-pointer" @click="imageClick(post, 0)"/>
            </div>
            <div class="flex flex-row gap-2">
              <div v-for="(image, index) in post.images.slice(1, 3)" :key="index">
                <img :src="'/storage/' + image.image_path" alt="Post Image"
                     class="w-[500px] h-[275px] object-cover rounded-lg cursor-pointer"
                     @click="imageClick(post, index + 1)"/>
              </div>
            </div>
          </div>
          <div v-else-if="post.images.length === 4" class="flex flex-row gap-2 w-full">
            <div v-for="(image, index) in post.images.slice(0, 1)" :key="index">
              <img :src="'/storage/' + image.image_path" alt="Post Image"
                   class="w-[500px] h-[550px] object-cover rounded-lg cursor-pointer" @click="imageClick(post, 0)"/>
            </div>
            <div class="flex flex-col gap-2">
              <div v-for="(image, index) in post.images.slice(1, 4)" :key="index">
                <img :src="'/storage/' + image.image_path" alt="Post Image"
                     class="w-[500px] h-[175px] object-cover rounded-lg cursor-pointer"
                     @click="imageClick(post, index + 1)"/>
              </div>
            </div>
          </div>
          <div v-else-if="post.images.length === 5" class="flex flex-col gap-2 w-full">
            <div class="flex flex-row gap-2">
              <div v-for="(image, index) in post.images.slice(0, 2)" :key="index">
                <img :src="'/storage/' + image.image_path" alt="Post Image"
                     class="w-[500px] h-[275px] object-cover rounded-lg cursor-pointer"
                     @click="imageClick(post, index)"/>
              </div>
            </div>
            <div class="flex flex-row gap-2">
              <div v-for="(image, index) in post.images.slice(2, 5)" :key="index">
                <img :src="'/storage/' + image.image_path" alt="Post Image"
                     class="w-[325px] h-[275px] object-cover rounded-lg cursor-pointer"
                     @click="imageClick(post, index + 2)"/>
              </div>
            </div>
          </div>

          <!-- Like and Dislike Buttons -->
          <div class="absolute bottom-4 left-4 flex justify-start gap-4">
            <!-- Like Button -->
            <button
                @click="handleUpvote(post)"
                :class="post.user_vote === 'up' ? 'bg-blue-100 text-blue-700' : 'bg-gray-200 text-gray-700'"
                class="px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:bg-blue-200 transition">
              <i :class="post.user_vote === 'up' ? 'pi pi-thumbs-up-fill' : 'pi pi-thumbs-up'" class="mr-2"></i>
              <span>{{ post.user_vote === 'up' ? 'Liked' : 'Like' }}</span>
              <span class="ml-2 bg-gray-300 text-gray-700 rounded-full px-2 py-0.5 text-xs">{{ post.up_votes }}</span>
            </button>

            <!-- Dislike Button -->
            <button
                @click="handleDownvote(post)"
                :class="post.user_vote === 'down' ? 'bg-red-100 text-red-700' : 'bg-gray-200 text-gray-700'"
                class="px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:bg-red-200 transition">
              <i :class="post.user_vote === 'down' ? 'pi pi-thumbs-down-fill' : 'pi pi-thumbs-down'" class="mr-2"></i>
              <span>{{ post.user_vote === 'down' ? 'Disliked' : 'Dislike' }}</span>
              <span class="ml-2 bg-gray-300 text-gray-700 rounded-full px-2 py-0.5 text-xs">{{ post.down_votes }}</span>
            </button>
          </div>
        </div>

        <!-- Comments Section -->
        <div class="mt-2 mb-[134px] p-4 bg-gray-200 rounded-lg shadow-inner">
          <h3 class="text-lg font-semibold text-gray-800 mb-3">Comments</h3>
          <div v-if="post.comments.length === 0" class="text-sm text-gray-700">
            Post has no comments yet.
          </div>
          <div v-else>

            <div v-for="comment in post.comments" :key="comment.id" class="mb-3">

              <div class="text-sm text-gray-700 flex flex-row gap-3">
                <img :src="'/storage/'+ comment.profile_photo" alt="Profile" class="h-10 w-10 rounded-full">
                <div class="flex-1">
                  <div class="bg-white p-3 rounded-lg inline-block">
                    <p class="font-semibold mb-2">{{ comment.full_name }}</p>
                    <p class="font-light">{{ comment.content }}</p>
                  </div>

                  <div class="flex items-center gap-1 mt-2">
                    <p class="text-gray-500">{{ formatDate(comment.created_at) }}</p>
                    <Button
                        icon="pi pi-thumbs-up"
                        variant="text" rounded
                        :label="String(post.up_votes)"
                    />
                    <Button
                        icon="pi pi-thumbs-down"
                        variant="text" rounded
                        :label="String(post.down_votes)"
                    />
                    <Button
                        icon="pi pi-comment"
                        variant="text" rounded
                    />
                  </div>

                </div>
              </div>

            </div>

          </div>
        </div>
      </div>
    </ScrollPanel>

    <!-- Add Comment Section -->
    <div class="absolute bottom-0 left-0 w-full p-4 bg-white border-t border-gray-200 rounded-b-lg shadow-md z-50">
      <CommentTextArea :post="props.post"/>
    </div>

  </Dialog>
</template>

<script setup>
import {ref, watch} from 'vue';
import Dialog from 'primevue/dialog';
import Galleria from 'primevue/galleria';
import CommentTextArea from "@/Components/CommentTextArea.vue";
import moment from 'moment';

const props = defineProps({
  isVisible: Boolean,
  post: Object
});

const emit = defineEmits(['update:isVisible', 'upvote', 'downvote']);

const localVisible = ref(props.isVisible);

watch(localVisible, (newValue) => {
  emit('update:isVisible', newValue);
});

watch(() => props.isVisible, (newValue) => {
  localVisible.value = newValue;
});

const handleUpvote = (post) => {
  emit('upvote', post);
};

const handleDownvote = (post) => {
  emit('downvote', post);
};

const imageClick = (post, index) => {
  post.activeIndex = index;
  post.displayCustom = true;
};

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

const formatDate = (date) => {
  const postDate = moment(date);
  const now = moment();

  const diffInSeconds = now.diff(postDate, 'seconds');
  const diffInMinutes = now.diff(postDate, 'minutes');
  const diffInHours = now.diff(postDate, 'hours');
  const diffInDays = now.diff(postDate, 'days');
  const diffInWeeks = now.diff(postDate, 'weeks');
  const diffInMonths = now.diff(postDate, 'months');
  const diffInYears = now.diff(postDate, 'years');

  if (diffInSeconds < 60) {
    return `${diffInSeconds}s`;
  } else if (diffInMinutes < 60) {
    return `${diffInMinutes}m`;
  } else if (diffInHours < 24) {
    return `${diffInHours}h`;
  } else if (diffInDays < 7) {
    return `${diffInDays}d`;
  } else if (diffInWeeks < 4) {
    return `${diffInWeeks}w`;
  } else if (diffInMonths < 12) {
    return `${diffInMonths}mo`;
  } else {
    return `${diffInYears}y`;
  }
};

const handleLike = (comment) => {
  // Handle like action
};

const handleDislike = (comment) => {
  // Handle dislike action
};

const handleReply = (comment) => {
  // Handle reply action
};

</script>

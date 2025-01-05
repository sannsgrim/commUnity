<template>
    <div v-if="visible" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75">
        <div class="bg-white p-6 rounded shadow-md">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Confirm Password</h2>
            <input v-model="password" type="password" placeholder="Enter your password" class="mt-1 block w-full" />
            <div class="mt-4 flex justify-end">
                <button @click="cancel" class="mr-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Cancel</button>
                <button @click="confirm" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Confirm</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    visible: Boolean,
});

const emits = defineEmits(['confirm', 'cancel']);

const password = ref('');

const confirm = () => {
    emits('confirm', password.value);
};

const cancel = () => {
    emits('cancel');
};
</script>

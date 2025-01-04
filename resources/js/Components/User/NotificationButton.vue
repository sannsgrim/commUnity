<template>

    <div class="py-2">
        <Button
            rounded
            icon="pi pi-bell"
            severity="secondary"
            @click="toggle($event)"
            class="inline-flex items-center rounded-md border border-transparent bg-white text-sm font-medium text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
        />

        <Popover ref="showPopover">
            <div class="flex flex-col gap-4 w-[20rem]">
                <div>
                    <span class="font-medium block mb-2">You're Notification</span>
                    <ul class="flex flex-col">

                    </ul>
                </div>
            </div>
        </Popover>
    </div>

</template>

<script setup>
import { ref } from "vue";
import Popover from "primevue/popover";
import DropdownLink from "@/Components/DropdownLink.vue";

const showPopover = ref();

const toggle = (event) => {
    showPopover.value.toggle(event);
}
</script>

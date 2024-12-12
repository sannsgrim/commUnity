<script setup>
import { Head } from '@inertiajs/vue3';
import UserAuthenticatedLayout from "@/Layouts/UserAuthenticatedLayout.vue";
import Textarea from 'primevue/textarea';
import Chip from 'primevue/chip';
import IftaLabel from 'primevue/iftalabel';
import Image from 'primevue/image';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
</script>

<template>
    <Head title="Dashboard" />
    <UserAuthenticatedLayout>
        <aside class="sticky top-8 hidden w-1/4 shrink-0 lg:block bg-[#F4F4F4] rounded-xl">
            <!-- Left column area -->
            <div class="pb-12">

            </div>
        </aside>

        <main class="flex-1 bg-[#F4F4F4] rounded-xl p-10">
            <!-- Main area -->
            <div class="pb-12 flex justify-center">
                <IftaLabel>
                    <Textarea id="description" v-model="value" cols="89" placeholder="Write something..." style="resize: none" />
                </IftaLabel>
            </div>

            <div class="pb-13 bg-[#FFFFFF] rounded-xl block p-10">
                <Chip label="Kurt Brylle Ferrer" image=" " /> <br>
                <Textarea v-model="caption" autoResize rows="1" cols="85" style="border: none; padding-top: 20px" />
                <div class="pb-14 flex justify-center py-5">
                    <Image src="https://primefaces.org/cdn/primevue/images/galleria/galleria10.jpg" alt="Image" width="580" style="height: auto " preview />
                    <div class="flex flex-col px-4">
                        <Image src="https://primefaces.org/cdn/primevue/images/galleria/galleria10.jpg" alt="Image" width="550"  preview /> <br>
                        <Image src="https://primefaces.org/cdn/primevue/images/galleria/galleria10.jpg" alt="Image" width="550" preview />
                    </div>
                </div>
                <InputGroup>
                    <InputNumber v-model="number" placeholder="Write a comment..." />
                    <InputGroupAddon><Button label="Submit" /></InputGroupAddon>
                </InputGroup>
            </div>
        </main>
    </UserAuthenticatedLayout>
</template>

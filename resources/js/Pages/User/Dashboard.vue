<script setup>
import {Head} from '@inertiajs/vue3';
import {ref} from 'vue';
import UserAuthenticatedLayout from "@/Layouts/UserAuthenticatedLayout.vue";
import Textarea from 'primevue/textarea';
import IftaLabel from 'primevue/iftalabel';
import Fluid from 'primevue/fluid';


const value = ref('');
</script>

<template>
    <Head title="Dashboard"/>
    <UserAuthenticatedLayout>
        <main class="w-full bg-[#F4F4F4] rounded-xl p-6 space-y-8">
            <!-- Main area -->
            <div class="bg-white p-12 rounded-lg w-full">
                <div class="flex w-full">
                    <Fluid class="w-full">
                        <IftaLabel class="w-full">
                            <Textarea
                                id="description"
                                v-model="value"
                                placeholder="Write something. . ."
                                :autoResize="true"
                                rows="3"
                                cols="30"
                                class="w-full"
                                disabled
                            />
                        </IftaLabel>
                    </Fluid>
                </div>
            </div>
        </main>
    </UserAuthenticatedLayout>
</template>

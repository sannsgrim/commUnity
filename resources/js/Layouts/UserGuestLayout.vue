<script setup>
import {Link, router} from "@inertiajs/vue3";
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const props = defineProps({
   canLogin: {
       type: Boolean,
   },
    canRegister: {
         type: Boolean,
    },
});

const userLogin = () => {
    if (props.canLogin) {
        return router.get(route('login'));
    }
}

const userRegister = () => {
    if (props.canRegister) {
        return router.get(route('register'));
    }
}
</script>

<template>
    <div class="flex flex-col justify-between min-h-full">
        <header class="shrink-0 border-b border-gray-200">
            <div class="flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
                <Link :href="route('welcome')">
                    <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800"/>
                </Link>
                <div class="flex items-center gap-x-8">

                    <Button
                        @click="userLogin"
                        label="Sign in"
                        severity="secondary"
                        icon="pi pi-sign-in"
                        icon-pos="right"
                    />

                    <Button
                        @click="userRegister"
                        label="Get Started"
                        icon="pi pi-arrow-right"
                        icon-pos="right"
                    />

                </div>
            </div>
        </header>

        <div class="flex w-full items-start gap-x-8 px-4 py-10 sm:px-6 lg:px-8">
           <!--Main Component For Welcome-->
            <slot />
        </div>
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import UserGuestLayout from "@/Layouts/UserGuestLayout.vue";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});


</script>

<template>
    <Head title="Welcome" />
    <UserGuestLayout :canLogin="canLogin" :canRegister="canRegister">

        <main>
            <div class="relative flex flex-col-reverse py-16 lg:pt-0 lg:flex-col lg:pb-0">
                <div class="inset-y-0 top-0 right-0 z-0 w-full max-w-xl px-4 mx-auto md:px-0 lg:pr-0 lg:mb-0 lg:mx-0 lg:w-7/12 lg:max-w-full lg:absolute xl:px-0">
                    <svg class="absolute left-0 hidden h-full text-white transform -translate-x-1/2 lg:block" viewBox="0 0 100 100" fill="currentColor" preserveAspectRatio="none slice">
                        <path d="M50 0H100L50 100H0L50 0Z"></path>
                    </svg>
                    <img
                        class="object-cover w-full h-56 rounded shadow-lg lg:rounded-none lg:shadow-none md:h-96 lg:h-full"
                        src="storage/welcome-photo/default.jpg"
                        alt=""
                    />
                </div>
                <div class="relative flex flex-col items-start w-full max-w-xl px-4 mx-auto md:px-0 lg:px-8 lg:max-w-screen-xl">
                    <div class="mb-16 lg:my-40 lg:max-w-lg lg:pr-5">
                        <h2 class="mb-5 font-sans text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl sm:leading-none">
                            Welcome to <span class="inline-block text-violet-600">CommUnity,</span><br/>
                            sparking connections
                        </h2>
                        <p class="pr-5 mb-5 text-base text-gray-700 md:text-lg">
                            CommUnity connects people through posts and meaningful interactions, making sharing and discovering effortless.
                        </p>
                        <div class="flex items-center">
                            <Button
                                label="Get Started"
                                href="/AdminLoginLayout.vue"
                                class="inline-flex items-center justify-center h-12 px-6 mr-6 font-medium tracking-wide text-white transition duration-200 rounded shadow-md bg-violet-500 hover:bg-violet-700 focus:shadow-outline focus:outline-none" raised
                            />
                            <!--<button href="/" aria-label="" class="inline-flex items-center font-semibold text-gray-800 transition-colors duration-200">Learn more</button>-->
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </UserGuestLayout>
</template>

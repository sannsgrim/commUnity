<script setup>

import {useForm} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";

const form = useForm({
    email: '',
    password: '',

});
const submit = () =>{
    if(form.email && form.password){
        form.post(route('admin.login.submit'), {
            onFinish: () => {
                form.reset("password");
            },
        });
    }else{
        if(!form.email){
            form.errors.email = 'Username is required';
        }
        if(!form.password){
            form.errors.password = 'Password is requred';
        }
    }
}

</script>

<template>
    <div class="relative min-h-screen flex">
        <img
            class="object-cover w-full"
            src="/storage/welcome-photo/default2.jpg"
            alt="" />
        <div class="absolute bg-violet-900 bg-opacity-75 w-full h-full">
            <div class="px-4 py-16 md:px-24 lg:px-8 lg:py-20 w-full h-full flex">
                <div class="flex flex-col items-center justify-between xl:flex-row w-full">
                    <div class="w-full max-w-xl mb-12 xl:mb-0 xl:pr-16 xl:w-7/12 p-10">
                        <h2 class="max-w-lg mb-6 font-sans text-3xl font-bold tracking-tight text-white sm:text-4xl sm:leading-none">
                            Welcome to CommUnity Admin Panel!<br class="hidden md:block" />
                        </h2>
                        <p class="max-w-xl mb-4 text-base text-violet-300 md:text-lg">
                            Please log in to access the administrative features and manage your system.
                        </p>
                    </div>
                    <div class="w-full max-w-xl xl:px-8 xl:w-5/12">
                        <div class="bg-white rounded shadow-2xl p-7 sm:p-10">
                            <h3 class="mb-4 text-xl font-semibold sm:text-center sm:mb-6 sm:text-2xl">
                                Login for Admins
                            </h3>
                            <form @submit.prevent="submit">
                                <div class="mb-1 sm:mb-2">
                                    <label for="username" class="inline-block mb-1 font-medium">Username</label>
                                    <input
                                        id="email"
                                        v-model="form.email"
                                        placeholder="Username"
                                        required
                                        type="text"
                                        class="flex-grow w-full h-12 px-4 mb-2 transition duration-200 bg-white border border-gray-300 rounded shadow-sm appearance-none focus:border-deep-purple-accent-400 focus:outline-none focus:shadow-outline"
                                    />
                                    <InputError class="mt-2" :message="form.errors.email"/>
                                </div>
                                <div class="mb-1 sm:mb-2">
                                    <label for="password" class="inline-block mb-1 font-medium">Password</label>
                                    <Password
                                        id="password"
                                        v-model="form.password"
                                        toggleMask
                                        class="h-12 transition duration-200"
                                        placeholder="Password"
                                        :style="{ width: '100%' }"
                                        :inputStyle="{ width: '100%' }"
                                        :feedback="false"
                                    />
                                    <InputError class="mt-2" :message="form.errors.password"/>
                                </div>
                                <div class="mt-4 mb-2 sm:mb-4">
                                    <Button
                                        label="Login"
                                        type="submit"
                                        class="inline-flex items-center justify-center w-full h-12 px-6 font-medium tracking-wide text-white transition duration-200 rounded shadow-md bg-deep-purple-accent-400 hover:bg-deep-purple-accent-700 focus:shadow-outline focus:outline-none"
                                    />
                                </div>
                                <p class="text-xs text-gray-600 sm:text-sm">
                                    Welcome to CommUnity Admins!,  Input your credentials to enter.
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script setup>
import {ref} from 'vue';
import UserLoginLayout from "@/Layouts/UserLoginLayout.vue";
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import InputError from "@/Components/InputError.vue";
import Checkbox from "@/Components/Checkbox.vue";
import FloatLabel from 'primevue/floatlabel';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const loading = ref(false);

const load = () => {
    loading.value = true;
};

const stop_load = () => {
    loading.value = false;
};

const submit = () => {
    if (form.email && form.password) {
        load();
        form.post(route('login'), {
            onFinish: () => {
                form.reset('password');
            },
            onError: () => stop_load(),
        });
    } else {
        // Handle validation error
        if (!form.email) {
            form.errors.email = 'Email is required';
        }
        if (!form.password) {
            form.errors.password = 'Password is required';
        }
        stop_load();
    }
};
</script>

<template>
    <UserLoginLayout>
        <Head title="Log in"/>
        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>
        <div class="mx-auto w-full max-w-sm lg:w-96 pt-6">
            <div>
                <Link :href="route('welcome')">
                    <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800"/>
                </Link>
                <h2 class="mt-8 text-2xl font-bold leading-9 tracking-tight text-gray-900">Sign in to your account</h2>
                <p class="mt-2 text-sm leading-6 text-gray-500">
                    Don't Have an Account Yet?
                    {{ ' ' }}
                    <Link :href="route('register')" class="font-semibold text-indigo-600 hover:text-indigo-500">
                        Get started here
                    </Link>
                </p>
            </div>

            <div class="mt-10">
                <div>
                    <form @submit.prevent="submit" class="space-y-6">

                        <div>
                            <FloatLabel variant="on">
                                <IconField>
                                    <InputIcon class="pi pi-at"/>
                                    <InputText
                                        id="email"
                                        v-model="form.email"
                                        fluid
                                        required
                                        autofocus
                                        autocomplete="username"
                                    />
                                </IconField>
                                <label for="email">Email</label>
                            </FloatLabel>

                            <InputError class="mt-2" :message="form.errors.email"/>
                        </div>

                        <div>
                            <FloatLabel variant="on">
                                <IconField>
                                    <InputIcon class="pi pi-key" style="z-index: 1" />
                                    <Password
                                        id="password"
                                        v-model="form.password"
                                        fluid
                                        required
                                        autofocus
                                        toggleMask
                                        :feedback="false"
                                        autocomplete="current-password"
                                        inputStyle="padding-left: calc((var(--p-form-field-padding-x)* 2) + var(--p-icon-size)); padding-right: var(--p-form-field-padding-x);"
                                    />
                                </IconField>
                                <label for="password">Password</label>
                            </FloatLabel>

                            <InputError class="mt-2" :message="form.errors.password"/>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <label class="flex items-center">
                                    <Checkbox name="remember" v-model:checked="form.remember"/>
                                    <span class="ms-2 text-sm text-gray-600">
                                        Remember me
                                    </span>
                                </label>
                            </div>

                            <div class="text-sm leading-6">
                                <Link
                                    v-if="canResetPassword"
                                    :href="route('password.request')"
                                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                >
                                    Forgot your password?
                                </Link>
                            </div>
                        </div>

                        <div>
                            <Button
                                fluid
                                type="submit"
                                label="Log in"
                                :loading="loading"
                                icon="pi pi-sign-in"
                                :disabled="form.processing"
                                :class="{ 'opacity-25': form.processing }"
                            />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </UserLoginLayout>
</template>

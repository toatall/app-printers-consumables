<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import Logo from '@/Shared/Logo'
import Dropdown from '@/Shared/Dropdown'
import MainMenu from '@/Shared/MainMenu'
import FlashMessages from '@/Shared/FlashMessages'

defineProps({
    auth: Object,
    canGlobal: Object,
})
</script>

<template>
    <div>
        <div id="dropdown" />
        <div class="md:flex md:flex-col">
            <div class="md:flex md:flex-col md:h-screen">
                <div class="md:flex md:flex-shrink-0">
                    <div
                        class="flex items-center justify-between px-6 bg-indigo-900 md:flex-shrink-0 md:justify-center md:w-56">
                        <Link class="mt-1" href="/">
                        <logo class="fill-white" width="120" height="28" />
                        </Link>
                        <dropdown class="md:hidden" placement="bottom-end">
                            <template #default>
                                <svg class="w-6 h-6 fill-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                                </svg>
                            </template>
                            <template #dropdown>
                                <div class="mt-2 px-8 py-4 bg-indigo-800 rounded shadow-lg">
                                    <main-menu />
                                </div>
                            </template>
                        </dropdown>
                    </div>
                    <div
                        class="md:text-md flex items-center justify-between p-4 w-full text-sm bg-white border-b md:px-12 md:py-0">                       
                        <div>
                            <h1 class="ps-3 text-2xl font-extrabold text-gray-800">Система учета картриджей</h1>
                        </div>
                        <dropdown class="mt-1" placement="bottom-end">
                            <template #default>
                                <div class="group flex items-center cursor-pointer select-none">
                                    <div
                                        class="mr-1 font-bold text-gray-700 group-hover:text-indigo-600 focus:text-indigo-600 whitespace-nowrap">
                                        <span class="hidden md:inline" v-if="auth.user.fio">{{ auth.user.fio }}</span>
                                        <span v-else>{{ auth.user.name }}</span>
                                    </div>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </template>
                            <template #dropdown>
                                <div class="mt-2 py-2 text-sm bg-white rounded shadow-xl">
                                    <Link class="block px-6 py-2 hover:text-white hover:bg-indigo-500"
                                        :href="`/users/${auth.user.id}/edit`">Профиль</Link>                                    
                                    <Link class="block px-6 py-2 w-full text-left hover:text-white hover:bg-indigo-500"
                                        href="/logout" method="delete" as="button">Выход</Link>
                                </div>
                            </template>
                        </dropdown>
                    </div>
                </div>
                <div class="md:flex md:flex-grow md:overflow-hidden">
                    <main-menu class="hidden flex-shrink-0 p-12 w-56 bg-indigo-800 overflow-y-auto md:block" />
                    <div class="px-4 py-8 md:flex-1 md:p-12 md:overflow-y-auto" scroll-region>
                        <flash-messages />
                        <slot />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <DynamicDialog />
</template>


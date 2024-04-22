<script setup>
import { Head, useForm } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/Layout'
import TrashedMessage from '@/Shared/TrashedMessage'
import Breadcrumbs from '@/Shared/Breadcrumbs'
import Panel from 'primevue/panel'
import FileUpload from 'primevue/fileupload'
import { Inertia } from '@inertiajs/inertia'
import Checkbox from 'primevue/checkbox'
import { ref, watch, computed } from 'vue'
import Menu from 'primevue/menu'
import ProgressSpinner from 'primevue/progressspinner'
import LoadingButton from '@/Shared/LoadingButton'

const props = defineProps({
    user: Object,
    roles: Object,
    organizations: Object,
    canGlobal: Object,
    rolesLabels: Object,    
})

defineOptions({
    layout: Layout
})

const user = props.user
const userRoles = Array.from(Object.values(props.user.roles), (key) => key.name)
const userOrganizations = Array.from(Object.values(props.user.organizations), (key) => key.code)
const menu = ref(null)
const menuItems = ref([
    { label: 'Удалить', icon: 'pi pi-times', visible: user.deleted_at === null, command: () => destroy() },
    { label: 'Восстановить', icon: 'pi pi-undo', visible: user.deleted_at !== null, command: () => restore() },
])
const toggleMenu = (event) => {
    menu.value.toggle(event)
}

const form = useForm({
    _method: 'put',
    name: user.name,
    email: user.email,
    password: '',
    photo: null,
    selectedRoles: userRoles,
    selectedOrganizations: userOrganizations,    
})


function update() {
    form.post(`/users/${user.id}`, {
        onSuccess: () => {
            form.reset('password', 'photo')
        },
        preserveState: true,
        preserveScroll: true,
    })
}
const destroy = () => {
    if (confirm('Вы уверены, что хотите удалить данного пользователя?')) {
        form.delete(`/users/${user.id}`, {
            onSuccess: () => Inertia.get(`/users/${user.id}/edit`)
        })
    }
}
const restore = () => {
    if (confirm('Вы уверены, что хотите восстановить данного пользователя?')) {
        form.put(`/users/${user.id}/restore`, {
            onSuccess: () => Inertia.get(`/users/${user.id}/edit`)
        })
    }
}

const uploadFile = async(event) => {
    form.photo = event.files[0]
    update()    
}

const isAdmin = computed(() => {    
    return form.selectedRoles.indexOf('admin') >= 0
})
</script>
<template>    

    <div>
        <Head :title="`${user.fio ?? ''} (${user.name})`" />
        
        <Breadcrumbs :home="{ label: 'Главная', url: '/' }" :items="[
            { label: 'Пользователи', url: '/users' },
            { label: form.name }
        ]" />
        
        <trashed-message v-if="user.deleted_at" class="mb-6 text-lg" @restore="restore"> Пользователь был удален.
        </trashed-message>

        <Panel>

            <template #header>
                <h1 class="font-bold text-xl">{{ user.fio }} ({{ user.name }})</h1>
            </template>
            <template #icons v-if="canGlobal.admin">
                <button class="p-panel-icon-header p-link" @click="toggleMenu">
                    <i class="pi pi-cog"></i>
                </button>
                <Menu ref="menu" :model="menuItems" popup />
            </template>
            
            <div class="max-w-2xl">
                
                <div class="mb-5 flex">                   
                    <div class="w-1/3">
                        <img v-if="user.photo" class="block rounded-full" :src="user.photo" />
                    </div>
                    <div class="w-1/3">
                        <FileUpload 
                            mode="basic"
                            type="file"
                            accept="image/*"
                            :multiple="false"
                            name="photo"
                            @uploader="uploadFile"
                            customUpload
                            chooseLabel="Обзор"
                            uploadLabel="Загрузить"
                        >
                        </FileUpload>
                    </div>                        
                </div>

                <div class="flex mb-6">
                    <div class="w-1/3 text-gray-500 font-semibold">Имя</div>
                    <div>{{ user.name }}</div>
                </div>

                <div class="flex mb-6">
                    <div class="w-1/3 text-gray-500 font-semibold">ФИО</div>
                    <div>{{ user.fio }}</div>
                </div>

                <div class="flex mb-6">
                    <div class="w-1/3 text-gray-500 font-semibold">Учетная запись</div>
                    <div>{{ user.email }}</div>
                </div>

                <div class="flex mb-6">
                    <div class="w-1/3 text-gray-500 font-semibold">Домен</div>
                    <div>{{ user.domain }}</div>
                </div>

                <div class="flex mb-6">
                    <div class="w-1/3 text-gray-500 font-semibold">Организация</div>
                    <div>{{ user.org_code }} {{ user.company }}</div>
                </div>

                <div class="flex mb-6">
                    <div class="w-1/3 text-gray-500 font-semibold">Отдел</div>
                    <div>{{ user.department }}</div>
                </div>

                <div class="flex mb-6">
                    <div class="w-1/3 text-gray-500 font-semibold">Должность</div>
                    <div>{{ user.post }}</div>
                </div>

                <div class="flex mb-6">
                    <div class="w-1/3 text-gray-500 font-semibold">Телефон</div>
                    <div>{{ user.telephone }}</div>
                </div>

                <div class="flex mb-6">
                    <div class="w-1/3 text-gray-500 font-semibold">Email</div>
                    <div>{{ user.lotus_mail }}</div>
                </div>

                <ProgressSpinner v-if="form.processing"></ProgressSpinner>
                <template v-else>
                    <div class="flex mb-6">
                        <div class="w-1/3 text-gray-500 font-semibold">Роли</div>
                        <div>
                            <div v-if="canGlobal.admin">                                                       
                                <div v-for="role in roles" :key="role.name" class="flex items-center mt-2">
                                    <template v-if="!(isAdmin && role.name != 'admin')">
                                        <Checkbox v-model="form.selectedRoles" :inputId="role.name" name="roles" :value="role.name" />
                                        <label :for="role.name" class="ml-2 cursor-pointer">
                                            {{ props.rolesLabels[role.name] ?? role.name }}
                                        </label>
                                </template>
                                </div>                            
                            </div>
                            <div v-else>
                                <ul>
                                    <li v-for="role in user.roles" class="mt-2">
                                        {{ props.rolesLabels[role.name] ?? role.name }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="flex mb-6" v-if="!isAdmin">
                        <div class="w-1/3 text-gray-500 font-semibold">Контекст</div>
                        <div>
                            <div v-if="canGlobal.admin">                            
                                <div v-for="organization in organizations" :key="organization.code" class="flex items-center mt-2">
                                    <Checkbox v-model="form.selectedOrganizations" :inputId="organization.code" name="organizations" :value="organization.code" />
                                    <label :for="organization.code" class="ml-2 cursor-pointer">
                                        {{ `${organization.name} (${organization.code})` }}
                                    </label>
                                </div>
                            </div>
                            <div v-else>
                                <ul>
                                    <li v-for="organization in organizations" class="mt-2">
                                        {{ organization.name }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </template>

                <div class="flex mb-6" v-if="form.isDirty">
                    <loading-button :loading="form.processing" class="font-bold" type="button" @click="update">Сохранить</loading-button>
                </div>

            </div>            
        </Panel>
        
    </div>    
</template>
<script setup>
import { inject } from 'vue';

const props = defineProps({    
    items: Array,
})
const moment = inject('moment')
</script>
<template>
    <div class="grid grid-cols-3" :class="$attrs.class">
        <template v-for="item in items" :key="item.value">
            <div class="border-b border-b-gray-200 py-4 font-semibold text-gray-500" :class="$attrs.classLabel">
                <slot name="label">
                    {{ item.label }}
                </slot>
            </div>
            <div class="col-span-2 border-b border-b-gray-200 py-4" :class="$attrs.classValue">
                <slot name="value">
                    
                    <template v-if="item?.format != undefined && item.format == 'date'">
                        <span :class="{ 'text-gray-400': (item.value == undefined) }">
                            <i :class="item.icon" v-if="item?.icon"></i>
                            {{ item.value ? moment(item.value).fromNow() : 'дата не определена' }}
                            <template v-if="item.value">
                                ({{ moment(item.value).format('LLLL') }})
                            </template>
                        </span>
                    </template>
                    
                    <template v-else-if="item?.format != undefined && item.format == 'bool'">
                        {{ item.value ? 'Да' : 'Нет' }}
                    </template>
                    
                    <template v-else>
                        <span :class="{ 'text-gray-400': (item.value == undefined) }">
                            {{ item.value ?? 'не определено' }}
                        </span>
                    </template>
                    
                </slot>
            </div>
        </template>
    </div>
</template>
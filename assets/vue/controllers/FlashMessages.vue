<template>
    <!-- Global notification live region -->
    <div aria-live="assertive" class="pointer-events-none fixed inset-0 flex items-end px-4 py-6 sm:items-start sm:p-6" style="z-index: 1000;">
        <div class="flex w-full flex-col items-center space-y-4 sm:items-end">
            <!-- Flash messages notifications panel -->
            <template v-for="(flashs, type) in flashes">
                <template v-for="(flash, index) in flashs">
                    <transition enter-active-class="transform ease-out duration-300 transition" enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" enter-to-class="translate-y-0 opacity-100 sm:translate-x-0" leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
                        <div :class="'warning' === type ? 'bg-red-50' : 'success' === type ? 'bg-green-50' : 'bg-white'"
                            class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg shadow-lg ring-1 ring-black/5">
                            <div class="p-4">
                                <div class="flex items-start">
                                    <div class="shrink-0">
                                        <CheckCircleIcon class="size-6 text-green-400" aria-hidden="true" v-if="'success' === type" />
                                        <ExclamationCircleIcon class="size-6 text-red-600" aria-hidden="true" v-if="'warning' === type" />
                                        <InformationCircleIcon class="size-6 text-blue-800" aria-hidden="true" v-if="'notice' === type" />
                                    </div>
                                    <div class="ml-3 w-0 flex-1 pt-0.5">
                                        <p :class="'warning' === type ? 'text-red-700' : 'success' === type ? 'text-green-700' : 'notice' === type ? 'text-blue-800' : 'text-gray-900'"
                                           class="text-sm font-medium ">{{ flash }}</p>
                                    </div>
                                    <div class="ml-4 flex shrink-0">
                                        <button type="button" @click="hide(type,index)" class="inline-flex rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                            <span class="sr-only">Close</span>
                                            <XMarkIcon class="size-5" aria-hidden="true" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </transition>
                </template>
            </template>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import {
    CheckCircleIcon,
    ExclamationCircleIcon,
    InformationCircleIcon
} from '@heroicons/vue/24/outline'
import { XMarkIcon } from '@heroicons/vue/20/solid'

const flashes = ref([]);

const props = defineProps({
  flashesJson: String
});

onMounted(() => {
  flashes.value = JSON.parse(props.flashesJson)
})

function hide(type, index) {
  flashes.value[type].splice(index, 1);
}
</script>

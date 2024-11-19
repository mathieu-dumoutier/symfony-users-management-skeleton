<template>
  <button class="dropdown-item user-action" @click="show = true">
    <i class="fa fa-fw fa-mask"></i>
    {{ $t('Utiliser un autre utilisateur') }}
  </button>

  <TransitionRoot as="template" :show="show">
    <Dialog class="relative z-10" @close="show = false">
      <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100"
                       leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity"/>
      </TransitionChild>

      <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <TransitionChild as="template" enter="ease-out duration-300"
                           enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                           enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                           leave-from="opacity-100 translate-y-0 sm:scale-100"
                           leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <DialogPanel
              class="relative transform rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl sm:p-6">
              <div class="sm:flex sm:items-start">
                <div
                  class="mx-auto flex size-12 shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:size-10">
                  <ExclamationTriangleIcon class="size-6 text-red-600" aria-hidden="true"/>
                </div>
                <div class="mt-3 sm:ml-4 sm:!mt-0 sm:text-left">
                  <DialogTitle as="h3" class="text-base font-semibold text-gray-900">
                    {{ $t('Utiliser un autre utilisateur') }}
                  </DialogTitle>
                  <div class="mt-2">
                    <p class="text-sm text-gray-500">
                      {{
                        $t('Attention, cette fonctionnalité est à utiliser avec parcimonie car les actions que vous ferez ne vous seront plus attribuées.')
                      }}
                    </p>
                  </div>
                </div>
              </div>
              <form class="mt-5 sm:flex sm:items-center">
                <div class="w-full sm:max-w-xs">
                  <label for="email" class="sr-only">Email</label>
                  <input type="hidden" v-model="selectedUser" id="email" name="_switch_user" />
                  <Combobox as="div" v-model="selectedUser" @update:modelValue="query = ''">
                    <div class="relative">
                      <ComboboxInput
                        class="w-full rounded-md border-0 bg-white py-1.5 pl-3 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
                        @change="query = $event.target.value" @blur="query = ''"
                        />
                      <ComboboxButton
                        class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
                        <ChevronUpDownIcon class="size-5 text-gray-400" aria-hidden="true"/>
                      </ComboboxButton>

                      <ComboboxOptions v-if="filteredUsers.length > 0"
                                       class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">
                        <ComboboxOption v-for="user in filteredUsers" :key="user.id" :value="user.email" as="template"
                                        v-slot="{ active, selected }">
                          <li
                            :class="['relative cursor-default select-none py-2 pl-3 pr-9', active ? 'bg-indigo-600 text-white outline-none' : 'text-gray-900']">
                            <span :class="['block truncate', selected && 'font-semibold']">
                              {{ user.email }}
                            </span>
                            <span v-if="selected"
                                  :class="['absolute inset-y-0 right-0 flex items-center pr-4', active ? 'text-white' : 'text-indigo-600']">
                              <CheckIcon class="size-5" aria-hidden="true"/>
                            </span>
                          </li>
                        </ComboboxOption>
                      </ComboboxOptions>
                    </div>
                  </Combobox>
                </div>
                <button type="submit"
                        class="mt-4 sm:!mt-0 inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                  {{ $t('Changer d\'utilisateur') }}
                </button>
              </form>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { Dialog, DialogTitle, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue';
import axios from 'axios';
import { ExclamationTriangleIcon } from '@heroicons/vue/24/outline';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import {
  Combobox,
  ComboboxButton,
  ComboboxInput,
  ComboboxOption,
  ComboboxOptions,
} from '@headlessui/vue';

const show = ref(false);
const users = [];

const query = ref('');
const selectedUser = ref(null);
const filteredUsers = computed(() =>
  query.value === ''
    ? users
    : users.filter((user) => {
      return user.email.toLowerCase().includes(query.value.toLowerCase());
    }),
);

onMounted(() => {
  axios.get('/admin/users').then((response) => {
    users.push(...response.data);
  });
});
</script>

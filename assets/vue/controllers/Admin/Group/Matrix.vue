<template>
  <table class="min-w-full divide-y divide-gray-300">
    <thead>
    <tr class="divide-x divide-gray-200">
      <th scope="col" class="sticky top-0 z-10 py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pl-3">Permissions / Groupes</th>
      <th v-for="group in groups" :key="group.id"
        scope="col"
        class="sticky top-0 z-10 px-4 py-3.5 text-left text-sm font-semibold text-gray-900 text-center"
      >
        {{ group.name }}
      </th>
    </tr>
    </thead>
    <tbody class="divide-y divide-gray-200 bg-white">
      <template v-for="(roles, subject) in rolesBySubject">
        <tr class="border-t border-gray-200" v-if="'null' !== subject">
          <th :colspan="groups.length + 1" scope="colgroup" class="bg-gray-50 py-2 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-3">{{ subject }}</th>
        </tr>
        <tr class="divide-x divide-gray-200" v-for="role in roles">
          <td class="whitespace-nowrap py-2 pl-4 pr-4 text-sm font-medium text-gray-900 sm:pl-3">{{ role.name }}</td>
          <td v-for="group in groups" :key="group.id"
            class="whitespace-nowrap py-2 px-4 text-sm text-gray-500 text-center">
            <input
              type="checkbox"
              class="form-checkbox h-5 w-5 text-indigo-600"
              :checked="role.groups.includes(group.id)"
              @change="toggle(role, group, $event)"
            />
          </td>
        </tr>
      </template>
    <!-- More people... -->
    </tbody>
  </table>
</template>

<script setup>
import axios from 'axios';

const props = defineProps({
  groups: Array,
  roles: Array,
});

const rolesBySubject = props.roles.reduce((acc, role) => {
  if (!acc[role.subject]) {
    acc[role.subject] = [];
  }

  acc[role.subject].push(role);

  return acc;
}, {});

function toggle(role, group, $event) {
  axios.post('/admin/group/matrix/toggle?entityId=' + group.id, { role_id: role.id })
    .then(response => {
      if ($event.target.checked !== response.data['checked']) {
        $event.target.checked = response.data['checked'];
      }
    })
    .catch(error => {
      console.error(error);
    });
}
</script>

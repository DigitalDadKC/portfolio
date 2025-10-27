<script setup>
// import ManageUser from '../modals/ManageUser.vue';
import UserDrawer from '../drawers/UserDrawer.vue';

const props = defineProps({
    users: Object,
    roles: Object,
})

</script>

<template>
    <div class="h-full py-2">
        <div class="text-xl font-bold text-center uppercase">Users</div>

        <table class="rounded-lg w-full p-4 ">
            <thead>
                <tr>
                    <th class="text-start p-2">User</th>
                    <th class="text-start">Email</th>
                    <th class="text-start">Roles</th>
                    <th class="text-center">Edit</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(user, index) in props.users" :key="index">
                    <td class="p-2">{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>
                        <div v-for="(role, i) in user.roles" :key="i">
                            {{ role.name }}
                        </div>
                    </td>
                    <td>
                        <!-- <ManageUser :user :roles></ManageUser> -->
                        <UserDrawer :user :roles></UserDrawer>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

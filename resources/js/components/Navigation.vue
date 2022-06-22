<template>
    <div class="w-full flex justify-center items-center py-2 space-x-4 font-semibold shadow-sm">
        <router-link to="/" class="text-gray-700 hover:text-gray-900">Home</router-link>

        <router-link to="/login" class="text-gray-700 hover:text-gray-900" v-if="auth == false">Login</router-link>

        <router-link to="/profile" class="text-gray-700 hover:text-gray-900" v-if="auth">Profile</router-link>
        <router-link to="/myauction" class="text-gray-700 hover:text-gray-900" v-if="auth">My Auction</router-link>

    </div>
</template>

<script>
export default {
    data() {
        return {
            auth: false,
        }
    },
    created() {
        const token = localStorage.getItem('auth-token');
        console.log(token);
        if (token) {
            this.auth = true;
        }
    },

}
</script>

<style>

</style>

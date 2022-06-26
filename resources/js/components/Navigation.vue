<template>

    <!-- <div class="w-full flex justify-center items-center py-2 space-x-4 font-semibold shadow-sm">
        <router-link to="/" class="text-gray-700 hover:text-gray-900">Home</router-link>

        <router-link to="/login" class="text-gray-700 hover:text-gray-900" v-if="auth == false">Login</router-link>

        <router-link to="/profile" class="text-gray-700 hover:text-gray-900" v-if="auth">Profile</router-link>
        <router-link to="/myauction" class="text-gray-700 hover:text-gray-900" v-if="auth">My Auction</router-link>

    </div> -->
    <div class="navbar bg-base-100">
        <div class="navbar-start">
            <div class="dropdown">
                <label tabindex="0" class="btn btn-ghost btn-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /></svg>
                </label>
                <ul tabindex="0" class="menu menu-compact dropdown-content mt-3 p-2 shadow bg-base-100 rounded-box w-52">
                    <li>
                        <router-link to="/" class="text-gray-700 hover:text-gray-900">Home</router-link>
                    </li>
                    <li>
                        <router-link to="/login" class="text-gray-700 hover:text-gray-900" v-if="auth == false">Login</router-link>
                    </li>
                    <li>
                        <router-link to="/profile" class="text-gray-700 hover:text-gray-900" v-if="auth">Profile</router-link>
                    </li>
                    <li>
                        <router-link to="/myauction" class="text-gray-700 hover:text-gray-900" v-if="auth">My Auction</router-link>
                    </li>
                </ul>
            </div>
        </div>
        <div class="navbar-center">
            <a class="btn btn-ghost normal-case text-xl">Welcome to Bidding App!</a>
        </div>
        <div class="navbar-end">
            <button class="btn btn-ghost btn-circle">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </button>
            <button class="btn btn-ghost btn-circle">
            <div class="indicator">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                <span class="badge badge-xs badge-primary indicator-item"></span>
            </div>
            </button>
        </div>
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

<template>
    <div >
        <div class="border-b border-gray-400 mb-2">
            <h1 class="mb-1 font-semibold">Profile</h1>
        </div>

        <div>
            <table width="100%" v-if="profile" v-cloak>
                <tr>
                    <td width="30%">Name</td>
                    <td>{{profile.name}}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{profile.email}}</td>
                </tr>
                <tr>
                    <td>Register At</td>
                    <td>{{profile.created_at}}</td>
                </tr>
            </table>

            <div v-else>
                <div class="text-center">
                    <h1 class="text-gray-500">Loading...</h1>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-400 py-2">
            <button class="bg-blue-500 text-white px-2 py-1 rounded-sm hover:bg-blue-700 focus:outline-none" @click="logout">Logout</button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            auth: false,
            profile: null
        }
    },
    created() {
        const token = localStorage.getItem('auth-token');
        console.log(token);
        if (token) {
            this.auth = true;
        }
    },

    mounted() {
        this.getProfile();
    },

    methods: {
        logout(){
            localStorage.removeItem('auth-token');
            this.auth = false;
            this.$router.push({ name: 'Home' });
            location.reload();
        },

        getProfile(){
            axios.get('/api/user', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('auth-token')
                }
            })
            .then(response => {
                console.log(response.data);
                this.profile = response.data;
            })
            .catch(error => {
                console.log(error.response.data);
            })
        },
    }
}
</script>

<style>
    [v-cloak] {
        opacity: 0;
        transition: all .5s;
    }
</style>

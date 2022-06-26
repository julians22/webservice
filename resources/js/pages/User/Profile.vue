<template>
    <div class="card w-full bg-primary text-primary-content">
        <div class="card-body">
            <div class="border-b mb-2 border-primary-content prose prose-headings:text-primary-content">
                <h2 class="mb-4">Profile</h2>
            </div>

            <div>
                <table width="100%" v-if="profile" class="table table-bordered table-sm" v-cloak>
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
                        <i class="fas fa-spinner fa-2x animate-spin duration-75"></i>
                    </div>
                </div>
            </div>

            <div class="border-t border-primary-content">
                <button class="btn btn-secondary btn-block mt-4" @click="logout">Logout</button>
            </div>
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
            setTimeout(() => {
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
            }, 1000);
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

<template>
    <div>
        <h1 class="font-semibold text-2xl text-center mb-2">Login</h1>
        <div class="flex flex-col space-y-3">
            <input class="w-full block p-2 rounded-sm focus:outline-none border border-gray-300 shadow-sm" type="email" v-model="forms.email" placeholder="Email">
            <input class="w-full block p-2 rounded-sm focus:outline-none border border-gray-300 shadow-sm" type="password" v-model="forms.password" placeholder="Password">

            <button class="w-full bg-blue-500 text-white p-2 rounded-sm hover:bg-blue-700 focus:outline-none" @click="login">Login</button>
        </div>

    </div>
</template>

<script>
export default {
    data(){
        return {
            auth: false,
            forms: {
                email: '',
                password: '',
            },
        }
    },

    created(){
        let auth = false;
        const token = localStorage.getItem('auth-token');
        if (token) {
            auth = true;
        }

        if (auth) {
            this.$router.push({ name: 'Home' });
            alert('You are already logged in');
        }
    },

    methods: {
        login(){
            axios.post('/api/login', this.forms)
                .then(response => {
                    localStorage.setItem('auth-token', response.data.token);
                    this.auth = true;
                    this.$router.push({ name: 'Home' });
                    location.reload();
                })
                .catch(error => {
                    console.log(error.response.data);
                })
        }
    }
}
</script>

<style>

</style>

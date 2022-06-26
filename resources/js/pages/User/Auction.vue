<template>
    <div class="">
        <div class="prose">
            <h1>{{ message }}</h1>
        </div>
        <div v-if="auctions.length > 0" class="mt-6">
            <div class="flex flex-col space-y-4">
                <div class="card bg-secondary text-secondary-content w-full"  v-for="auction in auctions" :key="auction.id">
                    <div class="card-body">
                        <h2 class="text-xl font-sans font-bold">{{ auction.auction.name }}</h2>
                        <p class="text-base mb-3">{{ auction.auction.description }}</p>
                        <p class="text-base font-bold" v-if="auction.amount">Your Bid: $ {{ auction.amount }}</p>

                        <div>
                            <router-link v-if="auth == true" :to="`/auction/${auction.id}`" class="btn btn-block btn-circle mt-4" >Go to auction</router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            message: 'Your Auction Histories!',
            auctions: [],
            auth: false,
        }
    },
    mounted() {
        this.getAuctions();
    },
    created() {
        const token = localStorage.getItem('auth-token');
        console.log(token);
        if (token) {
            this.auth = true;
        }
    },
    methods: {
        getAuctions() {
            axios.get('/api/user/auctions', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('auth-token')
                }
            })
            .then(response => {
                this.auctions = response.data
            })
            .catch(error => {
                console.log(error)
            });
        }
    }
}
</script>

<style>

</style>

<template>
    <div class="container mx-auto">
        <h1 class="text-2xl font-mono mb-4">{{ message }}</h1>
        <div v-if="auctions.length > 0">
            <div class="flex flex-col">
                <div v-for="auction in auctions" :key="auction.id" class="border border-slate-400 p-2 first:rounded-t-lg last:rounded-b-lg">
                    <div class="mb-3">
                        <h2 class="text-xl font-sans font-bold">{{ auction.name }}</h2>
                        <p class="text-base mb-3">{{ auction.thumb_descriptiony }}</p>
                        <p class="text-base font-bold">Starting Price: $ <span class="text-green-800">{{ auction.starting_price }}</span></p>
                        <p class="text-base font-bold" v-if="auction.last_bid">Current Bid: $ {{ auction.last_bid.amount }}</p>
                    </div>

                    <div>
                        <router-link v-if="auth == true" :to="`/auction/${auction.id}`" class="bg-gray-400 rounded-sm text-black px-3 py-1" >Bid</router-link>
                        <router-link v-else to="/login" class="bg-gray-400 rounded-sm text-black px-3 py-1" >Bid</router-link>
                    </div>
                    <!-- <a :href="'/auction/' + auction.id">{{ auction.name }}</a> -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            message: 'Welcome to Bidding App!',
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
            axios.get('/api/auction')
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

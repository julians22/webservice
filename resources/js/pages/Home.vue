<template>
    <div class="">
        <div class="prose">
            <h1>{{ message }}</h1>
        </div>

        <div v-if="auctions.length > 0" class="mt-6">
            <div class="flex flex-col space-y-4">
                <div class="card bg-primary text-primary-content" v-for="auction in auctions" :key="auction.id" >
                    <div class="card-body">
                        <h2 class="card-title">{{ auction.name }}</h2>
                        <p>{{ auction.thumb_description }}</p>
                        <p> $ <span class="badge badge-info">{{ auction.starting_price }}</span></p>
                        <p v-if="auction.last_bid"> Current Bid: $ {{ auction.last_bid.amount }}</p>
                        <div class="card-actions justify-end">
                            <router-link tag="button" class="btn" v-if="auth == true" :to="`/auction/${auction.id}`" >Bid</router-link>
                            <router-link tag="button" class="btn" v-else to="/login" >Bid</router-link>
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
            message: 'Available Auctions',
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

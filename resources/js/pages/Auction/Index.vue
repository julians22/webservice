<template>
    <div class="container mx-auto">

        <!-- back -->
        <div class="flex justify-between items-center mb-4">
            <router-link :to="'/'" class="text-black">
                <svg class="w-6 h-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path></svg>
            </router-link>
        </div>

        <!-- make card tailwind-->
        <div class="rounded bg-green-300 p-4" v-cloak v-if="auction">
            <div class="mb-4">
                <h1 class="text-6xl text-center">{{ auction.name }}</h1>
                <div class="text-base" v-html="auction.description"> </div>
                <img :src="`${auction.small_image_url}`" :alt="auction.name">

                <p class="text-base font-bold">Starting Price: $ <span class="text-green-800">{{ auction.starting_price }}</span></p>
            </div>

            <span class="py-px px-1 text-sm font-medium rounded-full bg-yellow-600">open at: {{ auction.start_date }}</span>
            <span class="py-px px-1 text-sm font-medium rounded-full bg-yellow-600">end at: {{ auction.end_date }}</span>
        </div>

        <div>
            <h4 class="mb-2 underline underline-offset-2 border-green-800 text-lg font-semibold">Bid Now!!</h4>
            <input type="number" v-model="total_bid" class="p-2 rounded border border-green-800">
            <button @click="bid" class="bg-green-800 rounded-sm text-white px-3 py-1">Go!</button>
        </div>

        <div v-cloak v-if="auction && auction.bids.length > 0">
            <h4 class="mb-2 underline underline-offset-2 border-green-800 text-lg font-semibold">Bid History</h4>
            <div class="flex flex-col">
                <div v-for="(bid, index) in auction.bids" :key="bid.id" class="border border-slate-400 p-2 first:rounded-t-lg last:rounded-b-lg">
                    <div class="mb-3">
                        <!-- <h2 class="text-xl font-sans font-bold">{{ bid.user.name }}</h2> -->
                        <!-- <p class="text-base mb-3">{{ bid.user.email }}</p> -->
                        <p class="text-base font-bold">$ <span class="text-green-800">{{ bid.amount }} {{ latestBidText(bid, index) }}</span>
                            <span v-if="index == 0 && timestamp">
                                <vue-countdown-timer
                                    :start-time="now"
                                    :end-time="auction.end_date_formatted"
                                    :interval="1000"
                                    :start-label="'Until start'"
                                    :end-label="'Until end'"
                                    label-position="begin"
                                    :end-text="'Event ended!'"
                                    :day-txt="'days'"
                                    :hour-txt="'hours'"
                                    :minutes-txt="'minutes'"
                                    :seconds-txt="'seconds'">
                                </vue-countdown-timer>
                            </span>
                        </p>
                    </div>
                    <span class="py-px px-1 text-sm font-medium rounded-full bg-yellow-600">bid at: {{ bid.created_at }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    props: ['id'],
    data() {
        return {
            auth: false,
            profile: null,
            total_bid: 0,
            bidError: null,
            bidSuccess: null,
            bidLoading: false,
            auction: null,
            bids: [],
            lastBid: null,
            timestamp: 0,
        }
    },
    created() {
        const token = localStorage.getItem('auth-token');
        console.log(token);
        if (token) {
            this.auth = true;
        }
        this.getAuction();
        this.getProfile();
    },
    computed: {
        now(){
            return new Date().getTime();
        }
    },
    methods: {
        getAuction() {
            axios.get('/api/auction/' + this.id)
                .then(response => {
                    this.auction = response.data;
                    // this.total_bid = response.data.starting_price + 1;

                    let bids = response.data.bids;
                    // sort bids by amount desc
                    bids.sort((a, b) => {
                        return b.amount - a.amount;
                    });

                    // get last bid
                    if (bids.length > 0) {
                        this.lastBid = bids[0];
                    }
                    if (this.lastBid) {
                        this.total_bid = this.lastBid.amount + 1;
                    }else{
                        this.total_bid = response.data.starting_price + 1;
                    }

                    this.bids = bids;

                    this.timestamp = this.auction.now_to_end_date;
                })
                .catch(error => {
                    console.log(error)
                });
        },
        getProfile(){
            axios.get('/api/user', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('auth-token')
                }
            })
            .then(response => {
                this.profile = response.data;
                return response.data;
            })
            .catch(error => {
                console.log(error.response.data);
                return false;
            })

            return false;
        },
        bid(){
            this.bidLoading = true;
            this.bidError = null;
            this.bidSuccess = null;
            axios.post('/api/bid', {
                amount: Number(this.total_bid),
                auction_id: this.id,
            }, {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('auth-token')
                }
            })
            .then(response => {
                console.log(response);
                this.bidLoading = false;
                this.bidSuccess = response.data.message;
                this.total_bid = response.data.amount;
                this.getAuction();
            })
            .catch(error => {
                this.bidLoading = false;
                this.bidError = error.response.data.message;
                alert(this.bidError);
            });
        },
        latestBidText(bid, index){
            let text = '';
            if (index == 0) {
                text += ' (latest bid)';
            }

            let profile = this.profile;

            if (profile) {
                if (profile.id === bid.user_id) {
                    text += ' by (you)';
                }else{
                    text += ' by someone else';
                }
            }else{
                text += ' by someone else';
            }

            return text;

        },
    }
}
</script>

<style>

</style>

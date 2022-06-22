import Login from './pages/Login.vue';
import Home from './pages/Home.vue';
import Profile from './pages/User/Profile.vue';
import Auction from './pages/Auction/Index.vue';
import UserAuction from './pages/User/Auction.vue';

export const routes = [
    { path: '/', component: Home, name: 'Home' },
    { path: '/login', component: Login, name: 'Login' },
    { path: '/profile', component: Profile, name: 'Login' },
    { path: '/auction/:id', component: Auction, name: 'Auction', props: true},
    { path: '/myauction', component: UserAuction, name: 'MyAuction'},
];

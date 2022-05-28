import './bootstrap';
import './favorite';
import './follow';
import '../sass/app.scss';
import '../css/app.css';
import { createApp } from 'vue'

// フォローしている人を取得
import FollowingVue from './Components/Following.vue';

const Following = createApp(FollowingVue) 
Following.mount('#following');

// // フォロワーを取得
import FollowerVue from './Components/Follower.vue';

const Follower = createApp(FollowerVue) 
Follower.mount('#follower')





// Jsファイルをインポート
import './bootstrap';
import './favorite';
import './follow';
import '../sass/app.scss';
import '../css/app.css';
import { createApp } from 'vue';
import App from './App.vue';
import FollowingVue from './Components/Following.vue';
import FollowerVue from './Components/Follower.vue';
import Test from './Components/test.vue';
import Comment from './Components/comment/Comment.vue';
// グローバルコンポーネントを定義 (ルートインスタンスはApp)
createApp(App)
    .component("FollowingVue", FollowingVue)
    .component("FollowerVue", FollowerVue)
    .component("Test", Test)
    .component("Comment", Comment)
    .mount("#app");

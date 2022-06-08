// Jsファイルをインポート
import './bootstrap';
import './favorite';
import './follow';
import '../sass/app.scss';
import '../css/app.css';
import { createApp } from 'vue';
import App from './App.vue';
import SelectBox from './Components/SelectBox.vue';
import FollowBtn from './Components/FollowBtn.vue';
import Comment from './Components/comment/Comment.vue';
// グローバルコンポーネントを定義 (ルートインスタンスはApp)
createApp(App)
    .component("SelectBox", SelectBox)
    .component("FollowBtn", FollowBtn)
    .component("Comment", Comment)
    .mount("#app");

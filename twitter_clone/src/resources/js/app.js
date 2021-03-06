// Jsファイルをインポート
import './bootstrap';
import './favorite';
import './follow';
import '../sass/app.scss';
import '../css/app.css';
import { createApp } from 'vue';
import App from './App.vue';
import SelectBox from './Components/SelectBox.vue';
import ProfileBox from './Components/ProfileBox.vue';
import FollowBtn from './Components/FollowBtn.vue';
import Tweet from './Components/Tweet.vue';
import ProfileTweet from './Components/ProfileTweet.vue';
import FavoriteBtn from './Components/FavoriteBtn.vue';
import Comment from './Components/comment/Comment.vue';
import Paginate from "vuejs-paginate-next";
// グローバルコンポーネントを定義 (ルートインスタンスはApp)
createApp(App)
    .component("SelectBox", SelectBox)
    .component("ProfileBox", ProfileBox)
    .component("FollowBtn", FollowBtn)
    .component("Tweet", Tweet)
    .component("ProfileTweet", ProfileTweet)
    .component("FavoriteBtn", FavoriteBtn)
    .component("Comment", Comment)
    .use(Paginate)
    .mount("#app");

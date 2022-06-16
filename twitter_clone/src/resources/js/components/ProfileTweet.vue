<template>
<div>
    <div v-if="user === loginUser" class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 mb-3">
                <div v-if="isActive">
                    <button type="button" class="btn btn-primary" @click="active">投稿フォームを開く</button>
                </div>
                <div v-else>
                </div>
                <div v-if="isActive">
                </div>
                <!-- 投稿フォーム -->
                <div v-else>
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group row mb-0">
                                <div class="col-md-12 p-3 w-100 d-flex">
                                    <img :src="'../storage/profile_image/' + image " class="rounded-circle" width="50" height="50">
                                    <div class="ml-2 d-flex flex-column">
                                        <p class="mb-0">{{ name }}</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-md-4 col-form-label text-md-right">画像</label>
                                    <div class="col-md-6">
                                        <input @change="selectFile" type="file" accept="image/png, image/jpeg">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <textarea class="form-control  is-invalid" name="text" required autocomplete="text" rows="4" ref="tweetText"></textarea>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-12 text-right">
                                    <p class="mb-4 text-danger">140文字以内</p>
                                    <button type="button" class="btn btn-danger" @click="active">閉じる</button>
                                    <button type="submit" class="btn btn-primary" @click="createTweet" :disabled="isActivePost">
                                        ツイートする
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- タイムライン -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 mb-3">
                <dl v-for=" tweet in  tweets" :key=" tweet.id" >
                    <div class="card">
                        <div class="card-haeder p-3 w-100 d-flex">
                            <div v-if=" tweet.userProfileImage !== null">
                                <img :src="'../storage/profile_image/' +  tweet.userProfileImage " class="rounded-circle" width="50" height="50">
                            </div>
                            <div v-else>
                                <img :src="'../storage/profile_image/noimage.png'" class="rounded-circle" width="50" height="50">
                            </div>
                            <div class="ml-2 d-flex flex-column">
                                <a :href="'/users/' +  tweet.userId "><p class="mb-0">{{  tweet.userName }}</p></a>
                            </div>
                            <div class="d-flex justify-content-end flex-grow-1">
                                <p class="mb-0 text-secondary">{{  tweet.createdAt }}</p>
                            </div>
                        </div>
                        <div v-if=" tweet.image !== null">
                            <img :src="'../storage/image/' +  tweet.image" class="rounded-circle" width="50" height="50">
                        </div>
                        <div class="card-body">
                            {{  tweet.text }}
                        </div>
                        <div class="card-footer py-1 d-flex justify-content-end bg-white">
                            <!-- 投稿者がログインユーザーなら編集、削除表示  -->
                            <div v-if=" tweet.userId === loginUser">
                                <div class="dropdown mr-3 d-flex align-items-center">
                                    <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-fw"></i>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <a :href="'/tweets/' +  tweet.id + '/edit/'" class="dropdown-item">編集</a>
                                        <button type="button" class="dropdown-item del-btn" @click="deleteTweet( tweet.id)">削除</button>
                                    </div>
                                </div>
                            </div>
                            <!-- コメントアイコン -->
                            <div class="mr-3 d-flex align-items-center">
                                <a :href="'/tweets/' +  tweet.id "><i class="far fa-comment fa-fw"></i></a>
                                <p class="mb-0 text-secondary">{{  tweet.commentCount }}</p>
                            </div>
                            <!-- いいね -->
                            <favorite-btn @child="favorite" :tweetId=" tweet.id" :favoriteCount=" tweet.favoriteCount" :initialBoolean=" tweet.alreadyFavorite"></favorite-btn>
                        </div>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    created() {
        this.fetchTweets();
    },
    props: {
        loginUser: {
            required: true
        },
        user: {
            required: true
        },
        name: {
            required: true,
            type: String,
        },
        image: {
            required: true,
            type: String,
        },
    },
    data() {
        return {
            tweets: [],
            conditions: [3],
            isActive: true,
            isActivePost: false,
            selected_file: null
        };
    },
    watch: {
        tweets: function () {
        }
    },
    methods: {
        // タイムライン取得 
        fetchTweets() {
            axios.put("/api/tweets", {
                user_id: this.user,
                conditions: this.conditions
            }).then((res) => {
                this.tweets = res.data;
            }).catch((error) => {
            });
        },

        removeTweet(id) {
            if(confirm('削除してよろしいですか?'))
            axios.delete("/api/deleteTweet/" + id
            ).then((res) => {
                this.timeLines = this.timeLines.filter(item => item.id !== id)
                this.$emit("tweetActive", false);
            }).catch((error) => {
            });
        },

        fileSelect(event) {
            //選択したファイルの情報を取得しプロパティにいれる
            this.selected_file = event.target.files[0];
        },

        // ツイート新規投稿
        createTweet() {
            // フォームデータ成形
            let formData = new FormData();
                //appendでデータを追加(第一引数は任意のキー)
                //他に送信したいデータがある場合にはその分appendする
                formData.append('image', this.selected_file);
                formData.append('text', this.$refs.tweetText.value);
                formData.append('userId', this.user);
            let config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            };
            // 連続クリック制御
            this.isActivePost = true;
            axios.post('/api/tweets',formData,config
            ).then((res) => {
                this.tweets.unshift(res.data)
                this.$emit("tweetActive", true);
                this.isActivePost = false;
            }).catch((error) => {
                alert("テキストを入れてください");
                this.isActivePost = false;
            });
        },
        // ツイート削除
        deleteTweet(id) {
            axios.delete("/api/tweets/" + id
            ).then((res) => {
                this.tweets = this.tweets.filter(item => item.id !== id)
                this.$emit("tweetActive", false);
            }).catch((error) => {
            });
        },
        selectFile(event) {
            //選択したファイルの情報を取得しプロパティにいれる
            this.selected_file = event.target.files[0];
        },

        active() {
            this.isActive = !this.isActive;
        },

        favorite(tweetId) {
            axios.get("/api/favorite", {
                params: {
                    tweet_id: tweetId
                }
            }).then((res) => {
            }).catch((error) => {
            });
        },

    },
};  
</script>

<style scoped>
.input-group-btn {
    margin-left: 20px;
}

.selectBox {
    text-align: center;
}
.card-body {
    white-space: pre-wrap;
}
</style>

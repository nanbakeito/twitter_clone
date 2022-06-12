<template>
<div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 mb-3">
                <!-- 絞り込み -->
                <div class="selectBox">
                    <section>
                        <input
                            class="check"
                            id="follow"
                            type="checkbox"
                            value= 0
                            v-model="checkList"
                        >
                        <label for="follow">フォロー</label>
                        <input
                            class="check"
                            id="follower"
                            type="checkbox"
                            value= 1
                            v-model="checkList"
                        >
                        <label for="follower">フォロワー</label>
                        <input
                            class="check"
                            id="all"
                            type="checkbox"
                            value= 2
                            v-model="checkList"
                        >
                        <label for="all">全員</label>
                        <input
                            class="check"
                            id="oneself"
                            type="checkbox"
                            value= 3
                            v-model="checkList"
                        >
                        <label for="oneself">{{name}}</label>
                        <span class="input-group-btn">
                            <button class="submit-btn" type="button" @click="sortTimeLine" >絞り込み</button> 
                        </span>
                    </section>
                </div>
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
                                        <input @change="fileSelect" type="file" accept="image/png, image/jpeg">
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
                                    <button type="submit" class="btn btn-primary" @click="postTweet">
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
                <dl v-for="timeLine in timeLines" :key="timeLine.id" >
                    <div class="card">
                        <div class="card-haeder p-3 w-100 d-flex">
                            <img :src="'../storage/profile_image/' + timeLine.userProfileImage " class="rounded-circle" width="50" height="50">
                            <div class="ml-2 d-flex flex-column">
                                <a :href="'/users/show/' + timeLine.userId "><p class="mb-0">{{ timeLine.userName }}</p></a>
                            </div>
                            <div class="d-flex justify-content-end flex-grow-1">
                                <p class="mb-0 text-secondary">{{ timeLine.createdAt }}</p>
                            </div>
                        </div>
                        <img :src="'../storage/image/' + timeLine.image ">
                        <div class="card-body">
                            {{ timeLine.text }}
                        </div>
                        <div class="card-footer py-1 d-flex justify-content-end bg-white">
                            <!-- 投稿者がログインユーザーなら編集、削除表示  -->
                            <div v-if="timeLine.userId === user">
                                <div class="dropdown mr-3 d-flex align-items-center">
                                    <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-fw"></i>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <a :href="'/tweets/' + timeLine.id + '/edit/'" class="dropdown-item">編集</a>
                                        <button type="button" class="dropdown-item del-btn" @click="removeTweet(timeLine.id)">削除</button>
                                    </div>
                                </div>
                            </div>
                            <!-- コメントアイコン -->
                            <div class="mr-3 d-flex align-items-center">
                                <a :href="'/tweets/' + timeLine.id "><i class="far fa-comment fa-fw"></i></a>
                                <p class="mb-0 text-secondary">{{ timeLine.commentCount }}</p>
                            </div>
                            <!-- いいね -->
                            <favorite-btn @child="favorite" :tweetId="timeLine.id" :favoriteCount="timeLine.favoriteCount" :initialBoolean="timeLine.alreadyFavorite"></favorite-btn>
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
        this.fetchTimeLine();
    },
    props: {
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
            timeLines: [],
            checkList: [],
            isActive: true,
            selected_file: null
        };
    },
    watch: {
        timeLines: function () {
        }
    },
    methods: {
        fetchTimeLine() {
            axios.get("/api/fetchTimeLine", {
                params: {
                    user_id: this.user,
                }
            }).then((res) => {
                this.timeLines = res.data;
            }).catch((error) => {
            });
        },

        removeTweet(id) {
            axios.delete("/api/deleteTweet/" + id
            ).then((res) => {
                this.timeLines = this.timeLines.filter(item => item.id !== id)
            }).catch((error) => {
            });
        },

        fileSelect(event) {
            //選択したファイルの情報を取得しプロパティにいれる
            this.selected_file = event.target.files[0];
        },

        postTweet() {
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
            axios.post('/api/postTweet',formData,config
            ).then((res) => {
                this.timeLines.unshift(res.data)
            }).catch((error) => {
                alert("テキストを入れてください");
            });
        },

        sortTimeLine() {
            axios.get("/api/sortTimeLine", {
                params: {
                    user_id: this.user,
                    checkList: this.checkList
                }
            }).then((res) => {
                this.timeLines = res.data
            }).catch((error) => {
            });
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
</style>

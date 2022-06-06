<template>
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
                    <img :src="'../storage/image/' + timeLine.image " >
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
                                    <a :href="'/users/' + timeLine.id + '/edit/'" class="dropdown-item">編集</a>
                                    <button type="button" class="dropdown-item del-btn" v-on:click="remove(timeLine.id)">削除</button>
                                </div>
                            </div>
                        </div>
                        <!-- コメントアイコン -->
                        <div class="mr-3 d-flex align-items-center">
                            <a :href="'/tweets/' + timeLine.id "><i class="far fa-comment fa-fw"></i></a>
                            <p class="mb-0 text-secondary">{{ timeLine.commentCount }}</p>
                        </div>
                        <!-- いいね -->
                        <favorite-btn :login_user_id= "user" :tweet_id="timeLine.id" :favorite_count="timeLine.favoriteCount" :favorite_judge="timeLine.favoriteJudge"></favorite-btn>
                    </div>
                </div>
            </dl>
        </div>
    </div>
</div>

</template>

<script>
export default {
    created() {
        this.fetchTimeLines();
    },
    props: {
        user: {
            required: true
        },
    },
    data() {
        return {
            timeLines: [],
        };
    },
    watch: {
        timeLines: function () {
        }
    },
    methods: {
        fetchTimeLines: function () {
            axios.get("/api/fetchTimeLines", {
                params: {
                    user_id: this.user,
                }
            }).then((res) => {
                this.timeLines = res.data;
            }).catch((error) => {
                console.log(error);
            });
        },

        remove: function (id) {
            axios.delete("/api/deleteTweet/" + id).then((res) => {
                this.fetchTimeLines();
            }).catch((error) => {
            });
        },


        pos: function () {
            axios.post("/api/postComment", {
                text: this.$refs.commentText.value,
                user_id: this.user,
                tweet_id: this.tweet,
            }).then((res) => {
                this.get();
            }).catch((error) => {
                alert("テキストを入れてください");
            });
        },
    },
};  
</script>

<style scoped>

</style>


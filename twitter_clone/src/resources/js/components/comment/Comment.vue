<template>
    <div>
        <li class="list-group-item">
            <div class="py-3">
                <div class="col-md-12"> 
                    <div class="form-group row mb-0">
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="コメント    140文字以内" ref="commentText">
                            <span class="input-group-btn">
                                <button class="submit-btn" type="button" v-on:click="post" >送信</button> 
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <dl v-for="comment in comments" :key="comment.id" >
            <li class="list-group-item">
                <div class="py-3 w-100 d-flex">
                    <img :src="'../storage/profile_image/' + comment.userProfileImage " class="rounded-circle" width="50" height="50">
                    <div class="ml-2 d-flex flex-column">
                        <a :href="'/users/' + comment.userId "><p class="mb-0">{{ comment.userName }}</p></a>
                    </div>
                    <div class="d-flex justify-content-end flex-grow-1">
                        <p class="mb-0 text-secondary">{{ comment.createdAt }}</p>
                    </div>
                </div>
                <div class="py-3">
                    {{ comment.text }}
                </div>
                <div class="py-3">
                    <button class="delete-btn" type="button" v-on:click="remove(comment.id)" >削除</button>
                </div>
            </li>
        </dl>
    </div>
</template>

<script>
export default {
    created() {
        this.get();
    },
    props: {
        user: {
            required: true
        },
        tweet: {
            required: true
        },
    },
    data() {
        return {
            comments: [],
        };
    },
    watch: {
        comments: function () {
        }
    },
    methods: {
        get: function () {
            axios.get("/api/getComment", {
                params: {
                    user_id: this.user,
                    tweet_id: this.tweet,
                }
            }).then((res) => {
                this.comments = res.data.reverse();
                console.log(res.length);
            });
        },
        post: function () {
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
        remove: function (id) {
            axios.delete("/api/deleteComment/" + id).then((res) => {
                this.get();
            }).catch((error) => {
            });
        },
    },
};  
</script>

<style scoped>

</style>

<template>
    <div>
        <li class="list-group-item">
            <div class="py-3">
                <div class="col-md-12"> 
                    <div class="form-group row mb-0">
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="コメント    140文字以内" ref="commentText">
                            <span class="input-group-btn">
                                <button class="submit-btn" type="button" :disabled="isActive" @click="createComments" >送信</button> 
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
                <div v-if="user === tweetUser || user === comment.userId" class="py-3">
                    <button class="delete-btn" type="button" @click="removeComments(comment.id)" >削除</button>
                </div>
            </li>
        </dl>
    </div>
</template>

<script>
export default {
    created() {
        this.fetchComments();
    },
    props: {
        user: {
            required: true
        },
        tweetUser: {
            required: true
        },
        tweet: {
            required: true
        },
    },
    data() {
        return {
            comments: [],
            isActive: false,
        };
    },
    watch: {
        comments: function () {
        }
    },
    methods: {
        // コメント取得
        fetchComments() {
            axios.get("/api/comments", {
                params: {
                    tweet_id: this.tweet,
                }
            }).then((res) => {
                this.comments = res.data.reverse();
            });
        },
        // コメント新規投稿
        createComments() {
            this.isActive = true;
            axios.post("/api/comments", {
                text: this.$refs.commentText.value,
                user_id: this.user,
                tweet_id: this.tweet,
            }).then((res) => {
                this.get();
                this.isActive = false;
            }).catch((error) => {
                alert("テキストを入れてください");
            });
        },
        // コメント削除
        removeComments(id) {
            axios.delete("/api/comments/" + id).then((res) => {
                this.fetchComments();
            }).catch((error) => {
            });
        },
    },
};  
</script>

<style scoped>

</style>

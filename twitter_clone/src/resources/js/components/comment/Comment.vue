<template>
    <div>
        <li class="list-group-item">
            <div class="py-3">
                <div class="col-md-12"> 
                    <div class="form-group row mb-0">
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="コメント    140文字以内" ref="commentText">
                            <span class="input-group-btn">
                                <button class="submit-btn" type="button" :disabled="isActive" @click="createComment" >送信</button> 
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <dl v-for="comment in getCommentsEachPage()" :key="comment.id" >
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
                    <button class="delete-btn" type="button" @click="removeComment(comment.id)" >削除</button>
                </div>
            </li>
        </dl>
        <paginate
            :v-model="currentPage"
            :page-count="getPaginateCount()"
            :prev-text="'<'"
            :next-text="'>'"
            :click-handler="paginateClickCallback"
            :container-class="'pagination justify-content-center'"
        ></paginate>
    </div>
</template>

<script>
import Paginate from 'vuejs-paginate-next';
export default {
    components: {
        paginate: Paginate,
    },
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
            currentPage: 1,
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
        createComment() {
            this.isActive = true;
            axios.post("/api/comments", {
                text: this.$refs.commentText.value,
                user_id: this.user,
                tweet_id: this.tweet,
            }).then((res) => {
                this.fetchComments();
                this.isActive = false;
            }).catch((error) => {
                alert("テキストを入れてください");
                this.isActive = false;
            });
        },
        // コメント削除
        removeComment(id) {
            axios.delete("/api/comments/" + id).then((res) => {
                this.fetchComments();
            }).catch((error) => {
            });
        },
        // 以下pagination関係　
        // ページ総数取得
        getPaginateCount() {
            return Math.ceil(this.comments.length / 5);
        },
        // ページごとのtweets取得
        getCommentsEachPage() {
            let start = (this.currentPage - 1) * 5;
            let end = this.currentPage * 5;
            return this.comments.slice(start, end);
        },
        paginateClickCallback(pageNum) {
            this.currentPage = Number(pageNum);
        },
    },
};  
</script>

<style scoped>

</style>

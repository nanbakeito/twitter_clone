<template>
<div class="selectBox">
    <section>
        <input
            id="follow"
            type="checkbox"
            value= 0
            v-model="conditions"
        >
        <label for="follow">フォロー</label>
        <input
            id="follower"
            type="checkbox"
            value= 1
            v-model="conditions"
        >
        <label for="follower">フォロワー</label>
        <input
            id="all"
            type="checkbox"
            value= 2
            v-model="conditions"
        >
        <label for="all">全員</label>
    </section>
    <span class="input-group-btn">
        <button class="submit-btn" type="button" @click="fetchSortedUsers" >絞り込み</button> 
    </span>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <dl v-for="user in users" :key="user.id" >
                    <div class="card">
                        <div class="card-haeder p-3 w-100 d-flex">
                            <div v-if="user.userProfileImage !== null">
                                <img :src="'../storage/profile_image/' + user.userProfileImage " class="rounded-circle" width="50" height="50">
                            </div>
                            <div v-else>
                                <img :src="'../storage/profile_image/noimage.png'" class="rounded-circle" width="50" height="50">
                            </div>
                            <div class="ml-2 d-flex flex-column">
                                <a :href="'/users/' + user.id "><p class="mb-0">{{ user.userName }}</p></a>
                            </div>
                            <div v-if="user.isFollowed">
                                <span class="mt-2 px-1 bg-secondary text-light">フォローされています</span>
                            </div>
                            <div class="d-flex justify-content-end flex-grow-1">
                                <follow-btn @child="follow" :initialBoolean="user.followingJudgement" :userId="user.id" ></follow-btn>
                            </div>
                        </div>
                    </div>
                </dl>
            </div>
        </div>
    </div>
    <paginate
        :page-count="getPaginateCount"
        :prev-text="'<'"
        :next-text="'>'"
        :click-handler="paginateClickCallback"
        :container-class="'pagination justify-content-center'"
        :page-class="'page-item'"
        :page-link-class="'page-link'"
        :prev-class="'page-item'"
        :prev-link-class="'page-link'"
        :next-class="'page-item'"
        :next-link-class="'page-link'"
        :first-last-button="true"
        :first-button-text="'<<'"
        :last-button-text="'>>'"    >
    </paginate>
</div>
</template>

<script>
import Paginate from 'vuejs-paginate-next';
export default {
    // pagintion 
    components: {
        paginate: Paginate,
    },
    data(){
        return {
            currentPage: 1,
            perPage: 10,
        }
    },

    methods: {
        clickCallback (pageNum) {
            console.log(pageNum)
        }
    },
    //
    created (){
        this.fetchUsers();
    },
    props: {
        user: {
            required: true
        },
    },
    data(){
        return {
            conditions: [],
            users: [],
        }
    },
    watch: {
        users: function () {
        }
    },
    methods: {
        // ユーザー一覧取得
        fetchUsers() {
            axios.get("/api/users", {
                params: {
                    user_id: this.user,
                }
            }).then((res) => {
                this.users = res.data
            }).catch((error) => {
            });
        },
        // ユーザー一覧絞り込み
        fetchSortedUsers() {
            axios.put("/api/users", {
                userId: this.user,
                conditions: this.conditions
            }).then((res) => {
                this.users = res.data
            }).catch((error) => {
            });
        },

        follow(userId) {
            axios.get("/api/follow", {
                params: {
                    loginUserId: this.user,
                    userId: userId
                }
            }).then((res) => {
            }).catch((error) => {
            });
        },
    },
};  
</script>

<style scoped>
.selectBox {
    text-align: center;
}
</style>

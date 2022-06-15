<template>
<div class="selectBox">
    <section>
        <input
            id="follow"
            type="checkbox"
            value= 0
            v-model="checkList"
        >
        <label for="follow">フォロー</label>
        <input
            id="follower"
            type="checkbox"
            value= 1
            v-model="checkList"
        >
        <label for="follower">フォロワー</label>
        <input
            id="all"
            type="checkbox"
            value= 2
            v-model="checkList"
        >
        <label for="all">全員</label>
    </section>
    <span class="input-group-btn">
        <button class="submit-btn" type="button" @click="sortUserTimeLines" >絞り込み</button> 
    </span>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <dl v-for="userTimeLine in userTimeLines" :key="userTimeLine.id" >
                    <div class="card">
                        <div class="card-haeder p-3 w-100 d-flex">
                            <div v-if="userTimeLine.userProfileImage !== null">
                                <img :src="'../storage/profile_image/' + userTimeLine.userProfileImage " class="rounded-circle" width="50" height="50">
                            </div>
                            <div v-else>
                                <img :src="'../storage/profile_image/noimage.png'" class="rounded-circle" width="50" height="50">
                            </div>
                            <div class="ml-2 d-flex flex-column">
                                <a :href="'/users/' + userTimeLine.id "><p class="mb-0">{{ userTimeLine.userName }}</p></a>
                            </div>
                            <div v-if="userTimeLine.isFollowed">
                                <span class="mt-2 px-1 bg-secondary text-light">フォローされています</span>
                            </div>
                            <div class="d-flex justify-content-end flex-grow-1">
                                <follow-btn @child="follow" :initialBoolean="userTimeLine.followingJudgement" :userId="userTimeLine.id" ></follow-btn>
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
        this.fetchUserTimeLines();
    },
    props: {
        user: {
            required: true
        },
    },
    data(){
        return {
            checkList: [],
            userTimeLines: [],
        }
    },
    watch: {
        userTimeLines: function () {
        }
    },
    methods: {

        list() {
            console.log(this.checkList);
        },

        fetchUserTimeLines() {
            axios.get("/api/fetchUserTimeLines", {
                params: {
                    user_id: this.user,
                }
            }).then((res) => {
                this.userTimeLines = res.data,
                console.log(this.userTimeLines)
            }).catch((error) => {
            });
        },

        sortUserTimeLines() {
            axios.get("/api/sortUserTimeLines", {
                params: {
                    userId: this.user,
                    checkList: this.checkList
                }
            }).then((res) => {
                this.userTimeLines = res.data
            }).catch((error) => {
            });
        },

        follow(userId) {
            console.log();
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

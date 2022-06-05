<template>
<div>
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
        <button class="submit-btn" type="button" v-on:click="narrowDownUserTimeLinesByRequest" >絞り込み</button> 
    </span>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <dl v-for="userTimeLine in userTimeLines" :key="userTimeLine.id" >
                    <div class="card">
                        <div class="card-haeder p-3 w-100 d-flex">
                            <img :src="'../storage/profile_image/' + userTimeLine.userProfileImage " class="rounded-circle" width="50" height="50">
                            <div class="ml-2 d-flex flex-column">
                                <a :href="'/users/' + userTimeLine.id "><p class="mb-0">{{ userTimeLine.userName }}</p></a>
                            </div>
                            <div class="d-flex justify-content-end flex-grow-1">
                                <follow-btn :login_user_id= "user" :user_id="userTimeLine.id" :following_judgement="userTimeLine.followingJudgement" ></follow-btn>
                            </div>
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

        list: function () {
            console.log(this.checkList);
        },

        fetchUserTimeLines: function () {
            axios.get("/api/fetchUserTimeLines", {
                params: {
                    user_id: this.user,
                }
            }).then((res) => {
                this.userTimeLines = res.data,
                console.log(userTimeLines)
            }).catch((error) => {
            });
        },

        narrowDownUserTimeLinesByRequest: function() {
            axios.get("/api/narrowDownUserTimeLinesByRequest", {
                params: {
                    user_id: this.user,
                    checkList: this.checkList
                }
            }).then((res) => {
                this.userTimeLines = res.data,
                console.log(userTimeLines)
            }).catch((error) => {
            });
        }
    },
};  

</script>

<style scoped>
    p {
        margin: 10px;
    }
    .positive {
        color: blue;
    }
    .negative {
        color: red;
    }
</style>
<template>
<div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 mb-3">
                <div class="card">
                    <div class="d-inline-flex">
                        <div class="p-3 d-flex flex-column">
                            <div v-if="image !== null">
                                <img :src="'../storage/profile_image/' + image " class="rounded-circle" width="50" height="50">
                            </div>
                            <div v-else>
                                <img :src="'../storage/profile_image/noimage.png'" class="rounded-circle" width="50" height="50">
                            </div>
                            <div class="mt-3 d-flex flex-column">
                                <h4 class="mb-0 font-weight-bold">{{ name }}</h4>
                            </div>
                        </div>
                        <div class="p-3 d-flex flex-column justify-content-between">
                            <div class="d-flex">
                                <div>
                                    <div v-if="user === loginUser">
                                        <a :href="'/users/' + user + '/edit'"  class="btn btn-primary">プロフィールを編集する</a>                      
                                    </div>
                                    <div v-else class="d-flex justify-content-end flex-grow-1">
                                        <follow-btn @child="followAction" :initialBoolean="isFollowing" :userId="user" ></follow-btn>
                                    </div>
                                    <div v-if="isFollowed">
                                        <span class="mt-2 px-1 bg-secondary text-light">フォローされています</span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <div class="p-2 d-flex flex-column align-items-center">
                                    <p class="font-weight-bold">ツイート数</p>
                                    <span v-if="user === loginUser">{{ tweetCountData }}</span>
                                    <span v-else>{{ tweetCount }}</span>
                                </div>
                                <div class="p-2 d-flex flex-column align-items-center">
                                    <p class="font-weight-bold">フォロー数</p>
                                    <span>{{ followCount }}</span>
                                </div>
                                <div class="p-2 d-flex flex-column align-items-center">
                                    <p class="font-weight-bold">フォロワー数</p>
                                    <span class="followerCount">{{ followerCountData }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-if="user === loginUser">
        <profile-tweet @tweetActive="fluctuationTweet" :user= "loginUser" :name= "loginUserName" :image= "loginUserImage" :loginUser= "user"></profile-tweet>
    </div>
    <div v-else>
        <profile-tweet :user= "user" :name= "name" :image= "image" :loginUser= "loginUser" ></profile-tweet>
    </div>
</div>
</template>

<script>
export default {
    props: {
        loginUser: {
            required: true
        },
        loginUserName: {
            required: true
        },
        loginUserImage: {
            required: true
        },
        user: {
            required: true
        },
        name: {
            required: true
        },
        image: {
            required: true
        },
        isFollowed: {
            required: true
        },
        isFollowing: {
            required: true
        },
        tweetCount: {
            required: true
        },
        followCount: {
            required: true
        },
        followerCount: {
            required: true
        },
    },
    data(){
        return {
            followerCountData: this.followerCount,
            tweetCountData: this.tweetCount
        }
    },
    watch: {
        followerCountData: function () {
        },
        tweetCountData: function () {
        }
    },
    methods: {
        // 子component(profile-tweet)で起こったイベント(投稿or削除)に応じてツイート数を操作
        fluctuationTweet(boolean){
            if (boolean) {
                this.tweetCountData += 1
            } else {
                this.tweetCountData -= 1
            }
        },
        // フォロー、アンフォロー
        followAction(userId) {
            axios.get("/api/follow", {
                params: {
                    loginUserId: this.loginUser,
                    userId: userId
                }
            }).then((res) => {
                if (res.data) {
                    this.followerCountData += 1
                } else {
                    this.followerCountData -= 1
                }
            }).catch((error) => {
            });
        },
    },
};  
</script>

<style scoped>

</style>

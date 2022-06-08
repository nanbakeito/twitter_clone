<template>
<div v-if="isFollowedByUser">
    <button type="button" class="btn btn-danger" @click="follow" >フォロー解除</button>
</div>
<div v-else>
    <button type="button" class="btn btn-primary"  @click="follow">フォローする</button>                    
</div>
</template>

<script>
export default {
    props: {
        loginUserId: {
            required: true
        },
        userId: {
            required: true
        },
        followingJudgement: {
            required: true
        },
    },
    data() {
        return {
            isFollowedByUser : this.followingJudgement
        };
    },
    methods: {
        follow() {
            axios.get("/api/follow", {
                params: {
                    loginUserId: this.loginUserId,
                    userId: this.userId
                }
            }).then((res) => {
                this.isFollowedByUser = res.data;
            }).catch((error) => {
            });
        },
    },
};  
</script>

<style scoped>

</style>

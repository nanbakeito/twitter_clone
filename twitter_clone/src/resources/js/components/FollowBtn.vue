<template>
<div v-if="judge">
    <button type="button" class="btn btn-danger" v-on:click="follow" >フォロー解除</button>
</div>
<div v-else>
    <button type="button" class="btn btn-primary"  v-on:click="follow">フォローする</button>                    
</div>
</template>

<script>
export default {
    props: {
        login_user_id: {
            required: true
        },
        user_id: {
            required: true
        },
        following_judgement: {
            required: true
        },
    },
    data() {
        return {
            judge : this.following_judgement
        };
    },
    watch: {
            judge: function () {
        }
    },
    methods: {
        follow: function () {
            axios.get("/api/follow", {
                params: {
                    login_user_id: this.login_user_id,
                    user_id: this.user_id
                }
            }).then((res) => {
                this.judge = res.data;
            }).catch((error) => {
            });
        },
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

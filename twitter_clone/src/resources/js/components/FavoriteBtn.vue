<template>
<div v-if="judge">
    <span class="favorites">
    <i class="fas fa-solid fa-thumbs-up favoriteToggle favorite" v-on:click="favorite"></i>
    <span class="favoriteCounter">{{count}}</span>
    </span>
</div>
<div v-else>
    <span class="favorites">
        <i class="fas fa-solid fa-thumbs-up favoriteToggle" v-on:click="favorite"></i>
        <span class="favoriteCounter">{{count}}</span>
    </span>
</div>
</template>

<script>
export default {
    props: {
        login_user_id: {
            required: true
        },
        tweet_id: {
            required: true
        },
        favorite_count: {
            required: true
        },
        favorite_judge: {
            required: true
        },
    },
    data() {
        return {
            judge : this.favorite_judge,
            count : this.favorite_count
        };
    },
    watch: {
        judge: function () {
        },
        count: function () {
        },

    },
    methods: {
        favorite: function () {
            axios.get("/api/favorite", {
                params: {
                    login_user_id: this.login_user_id,
                    tweet_id: this.tweet_id
                }
            }).then((res) => {
                this.judge = res.data.judge
                this.count = res.data.tweetFavoritesCount
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
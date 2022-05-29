<template>
<div>
    <div>
        {{tweet.text}}
    </div>
    <div>
        <follower-vue></follower-vue>
    </div>
    <button type="submit" class="btn btn-primary">
        返信する
    </button>
</div>

</template>
<script>

export default {
    props: {
        tweet: {
            required: true
        },
    },
    created() {
        console.log("a")
    }
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
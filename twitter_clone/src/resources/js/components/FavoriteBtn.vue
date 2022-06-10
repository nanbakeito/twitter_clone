<template>
<div v-if="isClicked">
    <span class="favorites">
    <i class="fas fa-solid fa-thumbs-up favoriteToggle favorite" @click="child"></i>
    <span class="favoriteCounter">{{count}}</span>
    </span>
</div>
<div v-else>
    <span class="favorites">
        <i class="fas fa-solid fa-thumbs-up favoriteToggle" @click="child"></i>
        <span class="favoriteCounter">{{count}}</span>
    </span>
</div>
</template>

<script>
export default {
    props: {
        initialBoolean: {
            required: true
        },
        tweetId: {
            required: true
        },
        favoriteCount: {
            required: true
        },
    },
    data() {
        return {
            isClicked: this.initialBoolean,
            count: this.favoriteCount
        };
    },
    watch: {
        count: function () {
        },
    },
    methods: {
        child() {
            this.isClicked=!this.isClicked;
            if(this.isClicked === true) {
                this.count += 1
            } else {
                this.count -= 1
            }
            this.$emit('child', this.tweetId);
        },
    },
};  
</script>

<style scoped>
    
</style>

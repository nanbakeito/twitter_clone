<template>
<div v-if="isClicked">
    <button type="button" class="btn btn-danger" @click="child" >フォロー解除</button>
</div>
<div v-else>
    <button type="button" class="btn btn-primary"  @click="child">フォローする</button>                    
</div>
</template>

<script>
export default {
    props: {
        initialBoolean: {
            required: true
        },
        userId: {
            required: true
        },
    },
    data(){
        return {
            isClicked: this.initialBoolean,
        }
    },
    methods: {
        child() {
            this.isClicked=!this.isClicked,
            this.$emit('child', this.userId)
        },
    },
};  
</script>

<style scoped>

</style>

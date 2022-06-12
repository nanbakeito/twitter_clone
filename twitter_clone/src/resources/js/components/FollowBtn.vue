<template>
<div v-if="isClicked">
    <button type="button" class="btn btn-danger" :disabled="isActive" @click="child" >フォロー解除</button>
</div>
<div v-else>
    <button type="button" class="btn btn-primary" :disabled="isActive" @click="child">フォローする</button>                    
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
            isActive: false,
        }
    },
    methods: {
        child() {
            this.isActive = true;
            this.isClicked=!this.isClicked,
            this.$emit('child', this.userId);
            this.isActive = false;
        },
    },
};  
</script>

<style scoped>

</style>

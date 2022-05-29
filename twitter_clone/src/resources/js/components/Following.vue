<template>
    <div class="">
        azzzz
    </div>
</template>


<script>
export default {
    created() {
        this.hello();
    },
    methods: {
        async hello() {
            const res = await axios.get("/api/following");
            console.log(res);
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
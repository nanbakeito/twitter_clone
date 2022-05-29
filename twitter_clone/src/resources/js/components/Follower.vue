<template>

</template>


<script>
export default {
    created() {
        console.log("aaassa");
    },
    data(){
        return {
            name: 'hogxxxxxxe'
        }
    },
    created() {
        this.hello();
    },
    methods: {
        async hello() {
            const res = await axios.post("/api/commentPost");
            this.res
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
<template>
<div>aaaa</div>
</template>


<script>
export default {
    props: {
        user: {
            required: true
        },
    },
    data(){
        return {
    
        }
    },
    created() {
        this.get();
    },
    methods: {
        get: function () {
            axios.get("/api/fetchFollow", {
                params: {
                    user_id: this.user,
                }
            }).then((res) => {
                console.log(res);
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
<template lang="html">
  <div class="chat-message" :id="mess.id" v-on="{ mouseenter: mouseEnter, mouseleave: mouseLeave}">
    <p >{{ mess.message }}</p>
        <small>{{ mess.user.name }}</small>
        <button class="btn btn-dange delete" @click="deleteMessage">X</button>
  </div>
  
</template>

<script>
export default {
    props: ['mess'],
    methods: {
        mouseEnter() {
            if(parseInt($('#user_id').text()) == 1 || parseInt($('#user_id').text()) == parseInt(this.mess.user.id) ){
                $('#'+this.$el.id).find('.delete').css("display","block");
            }
        },
        mouseLeave() {
            $('#'+this.$el.id).find('.delete').css("display","none");
        },
        deleteMessage() {
            this.$parent.$emit('message-deleted', {
                id: this.$el.id
            });
        }
    }
}
</script>

<style lang="css">
.chat-message {
    position:relative;
    padding: 1rem;
}

.chat-message > p {
    margin-bottom: .5rem;
}
.delete{
    position : absolute;
    right : 3rem;
    bottom : 2rem;
    display : none;
}
</style>

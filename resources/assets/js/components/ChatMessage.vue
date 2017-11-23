<template lang="html">
  <div class="chat-message" v-show="check() === true">
    <p >{{ mess.message }}</p>
        <small>{{ mess.user.name }}</small>
  </div>
</template>

<script>
export default {
    props: ['mess'],
    methods: {
        check() {
            var room_id = $('#room_id').text();

            if(this.mess.room_id == room_id) return true;

            return false;
        }
    }
}
</script>

<style lang="css">
.chat-message {
    padding: 1rem;
}

.chat-message > p {
    margin-bottom: .5rem;
}
</style>

<template lang="html">
  <div class="chat-log">
        <!--<div>{{ test }}</div>-->
      <chat-message v-for="message in messages" :mess="message" :key="message.id"></chat-message>

      <div class="empty" v-show="count() === 0">
          Nothing here yet!
      </div>


  </div>
</template>

<script>
export default {
    props: ['messages'],
    methods: {
        count() {
            var room_id = $('#room_id').text();
            var n = 0;

            this.messages.forEach(function(m) {
                if(m.room_id == room_id) n++;
            })

            return n;
        }
    }
}
</script>

<style lang="css">
.chat-log .chat-message:nth-child(even) {
    background-color: #ccc;
}

.empty {
    padding: 1rem;
    text-align: center;
}
</style>

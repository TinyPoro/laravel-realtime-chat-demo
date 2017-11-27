<template lang="html">
  <div class="chat-pager">
     <ul class="pagination" v-show="num_page !== 0">
        <li @click="previousPage">
            <a href="#">
              <i class="fa fa-angle-left" aria-hidden="true"></i>
            </a>  
        </li>
        <li><a id="num_page" href="#">{{num_page}}</a></li>
        <li @click="nextPage">
            <a href="#">
              <i class="fa fa-angle-right" aria-hidden="true"></i>
            </a>
        </li>
     </ul>
     
     <div id="max_page">{{num_page}}</div>
  </div>
</template>

<script>
export default {
    props: ['num_page'],
    methods: {
        previousPage() {
            this.$emit('pagePrevious', {
                
            });
        },
        nextPage() {
            this.$emit('pageNext', {
                
            });
        }
    }
}
</script>

<style lang="css">
#max_page{
    display: none;
}
</style>

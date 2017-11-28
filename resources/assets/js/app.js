
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));
Vue.component('chat-message', require('./components/ChatMessage.vue'));
Vue.component('chat-log', require('./components/ChatLog.vue'));
Vue.component('chat-composer', require('./components/ChatComposer.vue'));
Vue.component('chat-pager', require('./components/ChatPager.vue'));

const app = new Vue({
    el: '#app',
    data: {
        messages: [],
        usersInRoom: []
    },
    methods: {
        addMessage(message) {
            var cur_num_page = $('#num_page').text();
            var max_page = $('#max_page').text();
            
            // Persist to the database etc
            axios.post('/messages/' + message.room_id, message).then(response => {
                // Add to existing messages
                if(cur_num_page == max_page) {
                    if(this.messages.length < 3){
                        message.id = response.data.id;
                        this.messages.push(message);
                    }
                    else $('#max_page').text(++max_page);
                }
                
                
                $('html,body').animate({ scrollTop: 9999 }, 'slow');
            });
            
        },
        removeMessage(message) {
            var cur_num_page = $('#num_page').text();
            var max_page = $('#max_page').text();
            var room_id = $('#room_id').text();
            
            var id = message.id;
            
            
            //delete mess in dtb
            axios.get('/messages/' + room_id).then(response => {
                if(response.data.length==1) $('#max_page').text(--max_page);
                axios.post('/deletemsg/' + id, message).then(response => {
                    axios.get('/messages/' + room_id + "/" + cur_num_page).then(response => {
                        this.messages = response.data;
                        $('html,body').animate({ scrollTop: 9999 }, 'slow');
                    });
                });
            });
        },
        goToPrevious(){
            var cur_num_page = $('#num_page').text();
            var max_page = $('#max_page').text();
            var room_id = $('#room_id').text();
            
            if(cur_num_page > 1) $('#num_page').text(--cur_num_page);
            else alert("Trang 1 rồi chuyển cái gì nữa");
            
            
            axios.get('/messages/' + room_id + "/" + cur_num_page).then(response => {
                this.messages = response.data;
                $('html,body').animate({ scrollTop: 9999 }, 'slow');
            });
        },
        goToNext(){
            var cur_num_page = $('#num_page').text();
            var max_page = $('#max_page').text();
            var room_id = $('#room_id').text();
            
            if(cur_num_page < max_page) $('#num_page').text(++cur_num_page);
            else alert("Phòng này có " + max_page + " trang thui má ui!");
            
            axios.get('/messages/' + room_id + "/" + cur_num_page).then(response => {
                this.messages = response.data;
                $('html,body').animate({ scrollTop: 9999 }, 'slow');
            });
        }
    },
    created() {
        var room_id = $('#room_id').text();
        
        axios.get('/messages/' + room_id).then(response => {
                this.messages = response.data;
                $('html,body').animate({ scrollTop: 9999 }, 'slow');
        });
        
        Echo.join('App.Room.' + room_id)
            .here((users) => {
                this.usersInRoom = users;
            })
            .joining((user) => {
                this.usersInRoom.push(user);
            })
            .leaving((user) => {
                this.usersInRoom = this.usersInRoom.filter(u => u != user)
            })
            .listen('MessagePosted', (e) => {
                var cur_num_page = $('#num_page').text();
                var max_page = $('#max_page').text();
                if(cur_num_page == max_page) {
                    if(this.messages.length < 3){
                        this.messages.push({
                            message: e.message.message,
                            user: e.user,
                            room_id: e.message.room_id
                        });
                    }
                    else $('#max_page').text(++max_page);
                }
                
                $('html,body').animate({ scrollTop: 9999 }, 'slow');
            })
            .listen('DeleteMessage', (e) => {
                var cur_num_page = $('#num_page').text();
                var max_page = $('#max_page').text();
                var room_id = $('#room_id').text();
                
                //delete mess in dtb
                axios.get('/messages/' + room_id + "/" + max_page).then(response => {
                    if(response.data.length==0) $('#max_page').text(--max_page);
                    axios.get('/messages/' + room_id + "/" + cur_num_page).then(response => {
                        this.messages = response.data;
                        $('html,body').animate({ scrollTop: 9999 }, 'slow');
                    });
                });
            });
    }
});

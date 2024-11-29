<template>
  <div id="chat-room" class="container-fluid mt-3">
    <div class="d-flex flex-column align-items-stretch flex-shrink-0 bg-white">
      <div class="d-flex align-items-center flex-shrink-0 p-3 link-dark text-decoration-none border-bottom">
        <!--input class="fs-5 fw-semibold"  v-model="username"/-->
      </div>
      <div class="list-group list-group-flush border-bottom scrollarea">
        <div class="list-group-item list-group-item-action py-3 lh-tight"
             v-for="(message,idx) in messages" :key="idx">
          <div class="d-flex w-100 align-items-center justify-content-between">
            <strong class="mb-1">{{ message.username }}</strong>
          </div>
          <div class="col-10 mb-1 small">{{ message.message }}</div> 
        </div>
      </div>
      <form @submit.prevent="addMessage">
        <div class="d-flex align-items-center flex-shrink-0 p-3 link-dark text-decoration-none border-bottom">
          <input type="hidden" name="_token" value="csrf">
          <input class="form-control" type="text" placeholder="Type a message" v-model="message"/>
        </div>
      </form>
    </div>
    
  </div>
</template>

<script>
import {ref, onMounted, watch} from 'vue';
import Pusher from 'pusher-js';

export default {
  // data(): {
  //   value: self.form
  // }
  props: {
    message: {
      type: String,
      default: '',
      required: false
    },
    messages: {
      default: () => [],
      type: Array,
      required: true,
      value: () => []
    },
    username: {
      default: '',
      type: String,
      required: true,
      value: null
    },
    userid: {
      default: null,
      type: String,
      required: true,
      value: null
    },
    discussionid: {
      default: null,
      type: String,
      required: true,
      value: null
    },
    csrf: {
      default: null,
      type: String,
      required: true
    }
  },
  // model: {
  //       prop: 'message',
  //       //event: 'messageChange'
  // },
  mounted() {
   // console.log('mounted values =  ', this.username, this.userid, this.discussionid);   
   // console.log('mounted = ', this.messages);
      Pusher.logToConsole = true;
      this.pusher = new Pusher('3ab3bfe03e996fdbb26e', { //process.env.MIX_PUSHER_APP_KEY instead of 3abxxxxxx
              authEndpoint: 'https://mmteacherplatform.net/api/add-message',
              cluster: 'ap1' // ap1 process.env.PUSHER_APP_CLUSTER
      });
      this.pusher.connection.bind('state_change', function(states) {
        console.log('state.current=%s', states.current);
      });
      this.channel = this.pusher.subscribe('chat_'+this.discussionid);  
  },
  watch: {
      // message(first,second) {
      //   console.log("message changed from ", first, second );
      // }
  },
  methods: {
    async addMessage() { 
      //console.log('before calling post api ', this.username, this.message, this.userid);
      
      var self = this;
      // this.$emit('MessageSent', {
      //               username: this.username,
      //               message: this.message
      // });
      // window.Echo.channel('chat_'+self.discussionid).listen('message_'+self.discussionid, (e)=> {
      //     console.log('from echo ', e);
      //   });
      
      await fetch( 'https://mmteacherplatform.net/api/add-message', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
          username: self.username,
          message: self.message,
          discussion_id: self.discussionid,
          user_id: self.userid
        })
      })
      .then(response => response.json())
      .then(data => {
            // console.log("response = ", data);  
        
        // self.channel.bind('chat', data => { console.log("channel bind chat ", data);
        //       //messages.value.push(data);
        // }); 
        self.channel.bind('message_'+self.discussionid, data => { console.log("channel bind message ", data);
              //self.message = '';
              self.messages.push(data); 
        });
        // self.channel.bind('pusher:subscription_succeeded', data => {
        //     console.log("channel bind subscription ", data);
        // });
        // self.channel.bind("App\\Events\\Message", function(data) {
        //     console.log(data);
        // });
        
            self.message = '';
            // self.messages.push( data );    
            // console.log("final messages array", self.messages); 
             
      });

     
    }
  }
}
</script>

<style>
.scrollarea {
  min-height: 400px;
}
</style>

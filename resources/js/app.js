import './bootstrap';
import { createApp } from 'vue' 
import ChatBox from './components/ChatBox.vue' 

createApp({}) 
    .component('chat-box', ChatBox)
    .mount('#app')

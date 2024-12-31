<template>
    <div class="chat-box">
        <div ref="messagesBox" class="chat-messages">
            <div v-for="(zinas, index) in messages" :key="index" :class="['chat-message', { 'own-message': zinas.sutitaja_id === sender.id }]">
                {{ zinas.zinas_saturs }}
                <span class="timestamp">{{ formatTimestamp(zinas.created_at) }}</span>
              </div>
            </div>
            <div class="chat-input">
              <input v-model="newMessage" @keyup.enter="sendMessage" placeholder="Type a message..." />
              <button @click="sendMessage">Send</button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { nextTick, onMounted, ref, watch } from 'vue';
export default {
    props: {
        receiver: {
            type: Object,
            required: true,
        },
        sender: {
            type: Object,
            required: true,
        },
    },
    setup(props) {
        const messages = ref([]);
        const newMessage = ref("");
        const messagesBox = ref(null);
        const scrollToBottom = () => {
            messagesBox.value.scrollTop = messagesBox.value.scrollHeight;
        };
        watch(
            messages,
            () => {
                nextTick(() => {
                    scrollToBottom();
                });
            },
            { deep: true }
        );
        const sendMessage = () => {
            if (newMessage.value.trim() !== "") {
                axios
                    .post(`/messages/${props.receiver.id}`, {
                        zinas: newMessage.value,
                    })
                    .then((response) => {
                        messages.value.push(response.data);
                        newMessage.value = ""; 
                    });
            }
        };
        const formatTimestamp = (timestamp) => {
            const date = new Date(timestamp);
            return `${date.getHours()}:${date.getMinutes()}`;
        };
        onMounted(() => {
            axios.get(`/messages/${props.receiver.id}`).then((response) => {
                messages.value = response.data;
            });
            Echo.private('chat')
                .listen("MessageSent", (response) => {
                    if (response.zinas) {
                        const messageExists = messages.value.some(msg => msg.id === response.zinas.id);
                        if (!messageExists) {
                            if ((response.zinas.sutitaja_id === props.sender.id && response.zinas.sanemeja_id === props.receiver.id) || 
                                (response.zinas.sutitaja_id === props.receiver.id && response.zinas.sanemeja_id === props.sender.id)) {
                                messages.value.push(response.zinas);
                            }
                        }
                    }
            });
        });
        return {
            messages,
            newMessage,
            messagesBox,
            sendMessage,
            formatTimestamp,
        };
    },
};
</script>

<style scoped>
.chat-box {
    display: flex;
    flex-direction: column;
    border: 1px solid #ccc;
    padding: 10px;
    margin: 0 auto;
    height: 500px;
}
.chat-messages {
    display: flex;
    flex: 1;
    flex-direction: column; 
    align-items: flex-start; 
    overflow-y: auto;
    padding: 15px;
}
.chat-input {
    display: flex;
    align-items: center;
    border-top: 10px;
}
.chat-input input {
    flex: 1;
    padding: 10px;
    margin-right: 5px;
}
.chat-input button {
    padding: 10px;
    border: 1px solid #0a0a0a;
}
.chat-message {
    display: inline-block;
    padding: 10px;
    margin: 5px 0;
    border-radius: 15px;
    word-wrap: break-word; 
}
.chat-message.own-message {
    align-self: flex-end;
    background-color: #d1e7ff;
    color: #0056b3;
    text-align: right;
}
.chat-message:not(.own-message) {
    align-self: flex-start;
    background-color: #e1e1e1;
    color: #333;
}
.timestamp {
    font-size: 0.8rem;
    color: #aaa;
    margin-left: 10px;
  }
</style>
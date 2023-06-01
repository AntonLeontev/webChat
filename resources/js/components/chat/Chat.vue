<template>
  <div class="row justify-content-center h-100">
    <div class="d-none d-md-block col-md-4 col-xl-3 chat">
      <div class="card mb-sm-3 mb-md-0 contacts_card">
        <div class="card-header">
          <div class="input-group">
            <input
              class="form-control search"
              name=""
              type="text"
              placeholder="Search..."
            />
            <div class="input-group-prepend">
              <span class="input-group-text search_btn h-100"
                ><i class="fas fa-search"></i
              ></span>
            </div>
          </div>
        </div>
        <div class="card-body contacts_body">
          <ul class="contacts">
            <template v-for="(chat, key) in chats" v-key="key">
              <contact
                :chat="chat"
                :selectedChatId="selectedChat?.id"
                @chat-activated="activateChat"
              ></contact>
            </template>
          </ul>
        </div>
      </div>
    </div>

    <div class="col-md-8 col-xl-6 chat">
      <div class="card">
        <div class="card-header msg_head">
          <div class="d-flex bd-highlight" v-if="selectedChat">
            <div class="img_cont">
              <img class="rounded-circle user_img" :src="selectedChat.small_chat_photo" />
              <span class="online_icon"></span>
            </div>
            <div class="user_info">
              <span>{{ selectedChat.first_name }}</span>
              <p>@{{ selectedChat.username }}</p>
            </div>

            <!-- <div class="video_cam">
              <span><i class="fas fa-video"></i></span>
              <span><i class="fas fa-phone"></i></span>
            </div> -->
          </div>
          <span id="action_menu_btn"><i class="fas fa-ellipsis-v"></i></span>
          <div class="action_menu">
            <ul>
              <li><i class="fas fa-user-circle"></i> View profile</li>
              <li><i class="fas fa-users"></i> Add to close friends</li>
              <li><i class="fas fa-plus"></i> Add to group</li>
              <li><i class="fas fa-ban"></i> Block</li>
            </ul>
          </div>
        </div>
        <div class="card-body msg_card_body">
          <template v-for="(message, key) in messages" v-key="key">
            <message :message="message"></message>
          </template>
        </div>
        <div class="card-footer">
          <div class="input-group">
            <!-- <div class="input-group-append">
              <span class="input-group-text attach_btn h-100"
                ><i class="fas fa-paperclip"></i
              ></span>
            </div> -->
            <textarea
              class="form-control type_msg"
              name=""
              placeholder="Type your message..."
            ></textarea>
            <div class="input-group-append">
              <span class="input-group-text send_btn h-100"
                ><i class="fas fa-location-arrow"></i
              ></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Contact from "./Contact.vue";
import Message from "./Message.vue";
export default {
  created() {
    this.getChats();
  },
  data() {
    return {
      chats: null,
      selectedChat: null,
      messages: null,
    };
  },
  props: {},
  methods: {
    getChats() {
      axios.get("/chats").then((response) => {
        this.chats = response.data.items;
      });
    },
    activateChat(chat) {
      this.selectedChat = chat;

      axios.get(`/chats/${chat.id}/messages`).then((response) => {
        this.messages = response.data.items;
      });
    },
  },
  components: { Contact, Message },
};
</script>

<style lang="scss" scoped></style>

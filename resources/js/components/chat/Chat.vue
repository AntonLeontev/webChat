<template>
  <div class="row justify-content-center h-100">
    <div class="d-none d-md-block col-md-4 col-xl-3 chat" ref="contacts">
      <div class="card mb-sm-3 mb-md-0 contacts_card">
        <!-- <div class="card-header">
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
        </div> -->
        <div class="card-body contacts_body">
          <ul class="contacts">
            <template v-for="(chat, key) in chats" v-key="key">
              <contact
                :chat="chat"
                :selectedChatId="selectedChat?.id"
                :botId="botId"
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
              <p>Сообщений: {{ messagesCount }} Показано: {{ messages?.length }}</p>
            </div>

            <!-- <div class="video_cam">
              <span><i class="fas fa-video"></i></span>
              <span><i class="fas fa-phone"></i></span>
            </div> -->
          </div>
          <div class="d-flex justify-content-center bd-highlight" v-else>
            Выберите чат
          </div>
          <span
            id="action_menu_btn"
            class="d-md-none"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#chatsSide"
            aria-controls="offcanvasExample"
          >
            <i class="fas fa-ellipsis-v"></i>
          </span>
          <!-- <div class="action_menu">
            <ul>
              <li><i class="fas fa-user-circle"></i> View profile</li>
              <li><i class="fas fa-users"></i> Add to close friends</li>
              <li><i class="fas fa-plus"></i> Add to group</li>
              <li><i class="fas fa-ban"></i> Block</li>
            </ul>
          </div> -->
        </div>
        <div class="card-body msg_card_body" @scrollend="loadMessagesPortion" ref="board">
          <template v-for="(message, key) in messages" v-key="key">
            <message
              :message="message"
              :chatImage="selectedChat.small_chat_photo"
              :botId="botId"
              @show-image="showImage"
            ></message>
          </template>
        </div>
        <div class="card-footer">
          <div class="input-group" @keydown.enter.prevent="pushMessage">
            <!-- <div class="input-group-append">
              <span class="input-group-text attach_btn h-100"
                ><i class="fas fa-paperclip"></i
              ></span>
            </div> -->
            <textarea
              class="form-control type_msg"
              name="text"
              placeholder="Введите сообщение..."
              ref="messageText"
              @keydown.ctrl.enter.stop="addNewLine"
              @keydown.shift.enter.stop.prevent="addNewLine"
            ></textarea>
            <div class="input-group-append">
              <span class="input-group-text send_btn h-100" @click="pushMessage">
                <i class="fas fa-location-arrow"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="chatsSide">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title text-white" id="offcanvasExampleLabel">Выберите чат</h5>
      <button
        type="button"
        class="btn-close"
        data-bs-dismiss="offcanvas"
        aria-label="Close"
      ></button>
    </div>
    <div class="offcanvas-body">
      <ul class="contacts">
        <template v-for="(chat, key) in chats" v-key="key">
          <contact
            :chat="chat"
            :selectedChatId="selectedChat?.id"
            :botId="botId"
            @chat-activated="activateChat"
          ></contact>
        </template>
      </ul>
    </div>
  </div>
  <image-modal
    :imageUrl="imageUrl"
    @modal-interface="(interf) => (this.modal = interf)"
  />
</template>

<script>
import axios from "axios";
import Contact from "./Contact.vue";
import Message from "./Message.vue";
import ImageModal from "./ImageModal.vue";
import { DateTime } from "luxon";
import { nextTick } from "vue";
export default {
  created() {
    this.getChats(0);
  },
  mounted() {
    setTimeout(this.getChats, 5000);
    setTimeout(this.updateMessages, 2000);

    if (window.innerWidth < 768) {
      this.mobChatsMenu = new bootstrap.Offcanvas("#chatsSide");
      this.mobChatsMenu.show();
    }
  },
  data() {
    return {
      chats: null,
      selectedChat: null,
      messagesCount: null,
      messages: null,
      botId: import.meta.env.VITE_TELEGRAM_ID,
      modal: null,
      imageUrl: null,
      mobChatsMenu: null,
    };
  },
  props: {},
  methods: {
    getChats(delay = 5000) {
      axios
        .get("/chats", { timeout: 8000 })
        .then((response) => {
          this.chats = response.data.items;

          if (delay > 0) setTimeout(this.getChats, delay);
        })
        .catch((response) => {
          alert("Ошибка обновления данных. Перезагрузите страницу");
        });
    },
    activateChat(chat) {
      if (chat.id === this.selectedChat?.id) return;
      this.selectedChat = chat;
      this.messages = null;
      this.messagesCount = 0;
      if (this.mobChatsMenu) this.mobChatsMenu.hide();
      this.updateMessages(0);
      this.markAsRead(chat);
    },
    updateMessages(delay = 2000) {
      if (this.selectedChat === null) {
        if (delay > 0) setTimeout(this.updateMessages, delay);
        return;
      }

      axios
        .get(`/chats/${this.selectedChat.id}/messages`, { timeout: 8000 })
        .then(async (response) => {
          if (delay > 0) setTimeout(this.updateMessages, delay);

          let firstLoad = false;
          if (this.messages === null) {
            firstLoad = true;
          }

          if (this.selectedChat.id !== response.data.data.chat_id) return;
          if (this.messagesCount >= response.data.meta.total) return;

          if (this.messages === null) {
            this.messages = response.data.data.items.reverse();
          } else {
            response.data.data.items.reverse().forEach((message) => {
              if (message.from == this.botId) return;

              let notPresent = this.messages.every((oldMessage) => {
                return message.id !== oldMessage.id;
              });

              if (notPresent) {
                this.messages.push(message);
              }
            });
          }

          this.messagesCount = response.data.meta.total;
          await nextTick();
          if (firstLoad) {
            this.$refs.board.scrollTop = this.$refs.board.scrollHeight;
            return;
          }
          this.scrollDown("smooth");
        })
        .catch((response) => {
          alert("Ошибка обновления сообщений. Перезагрузите страницу");
        });
    },
    loadMessagesPortion() {
      if (this.selectedChat === null) return;
      if (this.messagesCount === this.messages?.length) return;
      if (this.$refs.board.scrollTop > 5) return;

      let scrollBottom = this.$refs.board.scrollHeight - this.$refs.board.scrollTop;

      axios
        .get(`/chats/${this.selectedChat.id}/messages/${this.messages.length}`, {
          timeout: 4000,
        })
        .then(async (response) => {
          this.messages = [...response.data.items.reverse(), ...this.messages];
          await nextTick();
          this.$refs.board.scrollTop = this.$refs.board.scrollHeight - scrollBottom;
        })
        .catch(() => {
          alert("Не удалось загрузить ранние сообщения");
        });
    },
    pushMessage() {
      if (this.selectedChat === null) return;

      let text = this.$refs.messageText.value.trim();

      if (text === "") return;

      this.showMessage(text);
      this.sendMessage(text);
    },
    async showMessage(text) {
      this.$refs.messageText.value = "";

      this.messages.push({
        from: this.botId,
        text: text,
        created_at: DateTime.now().toISO(),
        chat_id: this.selectedChat.id,
      });

      await nextTick();
      this.scrollDown("smooth");
    },
    sendMessage(text) {
      axios
        .post(`/chats/${this.selectedChat.id}/messages`, {
          text: text,
        })
        .then((response) => {
          this.messagesCount++;
        })
        .catch(() => alert("Ошибка при отправке"));
    },
    markAsRead(chat) {
      axios.put(`/chats/${chat.id}`, {
        is_unread: false,
      });
    },
    showImage(image) {
      this.imageUrl = image;
      this.modal.show();
    },
    scrollDown(behavior = "instant") {
      let board = this.$refs.board;

      if (board === null) return;

      let messages = board.querySelectorAll(".message");
      let last = messages[messages.length - 1];
      last.scrollIntoView({ behavior: behavior });
    },
    addNewLine() {
      this.$refs.messageText.value += "\n";
    },
  },
  components: { Contact, Message, ImageModal },
};
</script>

<style lang="scss" scoped></style>

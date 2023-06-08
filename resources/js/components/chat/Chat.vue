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
        <div
          class="card-body contacts_body"
          @scroll="loadContactsPortion"
          ref="contactsList"
        >
          <ul class="contacts">
            <template v-for="(chat, key) in chats" v-key="key">
              <contact
                :chat="chat"
                :selectedChatId="selectedChat?.id"
                :botId="botId"
                :user="user"
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
              <p>Сообщений: {{ messagesCount }} Показано: {{ totalMessages }}</p>
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
        <div class="card-body msg_card_body" @scroll="loadMessagesPortion" ref="board">
          <template v-for="(message, key) in readMessages" v-key="key">
            <message
              :message="message"
              :chatImage="selectedChat.small_chat_photo"
              :botId="botId"
              :user="user"
              @show-image="showImage"
            ></message>
          </template>
          <div class="w-100 text-center py-3" v-show="unreadMessages.length">
            <span class="badge text-bg-dark">Новые сообщения</span>
          </div>
          <template v-for="(message, key) in unreadMessages" v-key="key">
            <message
              :message="message"
              :chatImage="selectedChat.small_chat_photo"
              :botId="botId"
              :user="user"
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
    setTimeout(this.markChatAsRead, 2000);

    if (window.innerWidth < 768) {
      this.mobChatsMenu = new bootstrap.Offcanvas("#chatsSide");
      this.mobChatsMenu.show();
    }
  },
  data() {
    return {
      chats: [],
      selectedChat: null,
      messagesCount: null,
      readMessages: [],
      unreadMessages: [],
      botId: import.meta.env.VITE_TELEGRAM_ID,
      modal: null,
      imageUrl: null,
      mobChatsMenu: null,
    };
  },
  props: {
    user: Object,
  },
  methods: {
    getChats(delay = 5000) {
      axios
        .get("/chats", { timeout: 8000 })
        .then((response) => {
          this.chats = [
            ...response.data.items,
            ...this.chats?.slice(response.data.items.length),
          ];

          if (delay > 0) setTimeout(this.getChats, delay);
        })
        .catch((error) => {
          alert(
            "Ошибка обновления чатов. Перезагрузите страницу. Ошибка: " + error.message
          );
        });
    },
    activateChat(chat) {
      if (chat.id === this.selectedChat?.id) return;
      if (this.selectedChat !== null) {
        this.markChatAsRead(0);
      }
      this.selectedChat = chat;
      this.readMessages = [];
      this.unreadMessages = [];
      this.messagesCount = 0;
      if (this.mobChatsMenu) this.mobChatsMenu.hide();
      this.updateMessages(0);
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

          let firstLoad = this.totalMessages === 0;

          if (this.selectedChat.id !== response.data.data.chat_id) return;
          if (this.messagesCount >= response.data.meta.total) return;

          this.sortMessages(response.data.data.items.reverse(), firstLoad);

          this.messagesCount = response.data.meta.total;
          await nextTick();
          if (firstLoad) {
            this.$refs.board.scrollTop = this.$refs.board.scrollHeight;
            return;
          }
          this.scrollDown("smooth");
        })
        .catch((error) => {
          alert(
            "Ошибка обновления сообщений. Перезагрузите страницу. Ошибка: " +
              error.message
          );
        });
    },
    sortMessages(messages, firstLoad = false) {
      if (firstLoad) {
        messages.forEach((message) => {
          message.is_unread
            ? this.unreadMessages.push(message)
            : this.readMessages.push(message);
        });
        return;
      }

      messages.forEach((message) => {
        if (message.user?.id == this.user.id) return;

        let notPresent =
          this.readMessages.every((oldMessage) => {
            return message.id !== oldMessage.id;
          }) &&
          this.unreadMessages.every((oldMessage) => {
            return message.id !== oldMessage.id;
          });

        if (notPresent) {
          this.unreadMessages.push(message);
        }
      });
    },
    loadMessagesPortion() {
      if (this.selectedChat === null) return;
      if (this.messagesCount === this.totalMessages) return;
      if (this.$refs.board.scrollTop > 0) return;

      let scrollBottom = this.$refs.board.scrollHeight - this.$refs.board.scrollTop;

      axios
        .get(`/chats/${this.selectedChat.id}/messages/${this.totalMessages}`, {
          timeout: 4000,
        })
        .then(async (response) => {
          let messages = response.data.items.reverse();

          if (messages[0].is_unread) {
            if (this.unreadMessages.some((message) => message.id === messages[0].id)) {
              return;
            }

            this.unreadMessages = [...messages, ...this.unreadMessages];
          } else if (messages[0].is_unread === false && this.readMessages.length > 0) {
            if (this.readMessages.some((message) => message.id === messages[0].id)) {
              return;
            }

            this.readMessages = [...messages, ...this.readMessages];
          } else {
            let read = [],
              unread = [];

            messages.forEach((message) => {
              message.is_unread ? unread.push(message) : read.push(message);
            });

            if (read.length > 0) {
              this.readMessages = [...read, ...this.readMessages];
            }

            if (unread.length > 0) {
              this.unreadMessages = [...read, ...this.unreadMessages];
            }
          }

          await nextTick();
          this.$refs.board.scrollTop = this.$refs.board.scrollHeight - scrollBottom;
        })
        .catch((error) => {
          alert("Не удалось загрузить ранние сообщения. " + error.message);
        });
    },
    loadContactsPortion() {
      if (
        this.$refs.contactsList.scrollTop + this.$refs.contactsList.offsetHeight <
        this.$refs.contactsList.scrollHeight
      )
        return;

      axios.get(`/chats/${this.chats.length}`, { timeout: 4000 }).then((response) => {
        this.chats = [...this.chats, ...response.data.items];
      });
    },
    pushMessage() {
      if (this.selectedChat === null) return;

      let text = this.$refs.messageText.value.trim();

      if (text === "") return;

      this.markChatAsRead(0);
      this.moveToRead();
      this.showMessage(text);
      this.sendMessage(text);
    },
    async showMessage(text) {
      this.$refs.messageText.value = "";

      this.readMessages.push({
        from: this.botId,
        text: text,
        created_at: DateTime.now().toISO(),
        chat_id: this.selectedChat.id,
        user: this.user,
      });

      await nextTick();
      this.scrollDown("smooth");
    },
    moveToRead() {
      this.readMessages = [...this.readMessages, ...this.unreadMessages];
      this.unreadMessages = [];
    },
    sendMessage(text) {
      axios
        .post(`/chats/${this.selectedChat.id}/messages`, {
          text: text,
        })
        .then((response) => {
          this.messagesCount++;
        })
        .catch((error) => alert("Ошибка при отправке. " + error.message));
    },
    markChatAsRead(delay = 2000) {
      if (this.selectedChat !== null) {
        axios.put(`/chats/${this.selectedChat.id}/messages/mark-read`);
      }

      if (delay > 0) {
        setTimeout(this.markChatAsRead, delay);
      }
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
  computed: {
    totalMessages() {
      return this.readMessages.length + this.unreadMessages.length;
    },
  },
  components: { Contact, Message, ImageModal },
};
</script>

<style lang="scss" scoped></style>

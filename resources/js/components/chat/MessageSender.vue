<template>
  <div class="input-group position-relative" @keydown.enter.prevent="pushMessage">
    <div class="input-group-append">
      <div
        class="input-group-text attach_btn h-100"
        @click.stop="attachMenuShow = !attachMenuShow"
      >
        <i class="fas fa-paperclip" v-show="!uploading"></i>
        <div class="spinner-border spinner-border-sm" role="status" v-show="uploading">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
    </div>
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
    <ul
      class="attach-menu"
      :class="{ 'attach-menu_active': attachMenuShow }"
      v-outside="hideAttachMenu"
      @click="focusInput"
      @input="sendFile"
    >
      <li class="attach-menu__item">
        <form
          :action="`chats/${this.chatId}/messages/image`"
          method="post"
          class="visually-hidden"
        >
          <input type="file" name="image" />
        </form>
        <i class="fas fa-image"></i>
      </li>
      <li class="attach-menu__item">
        <form
          :action="`chats/${this.chatId}/messages/document`"
          method="post"
          class="visually-hidden"
        >
          <input type="file" name="document" />
        </form>
        <i class="far fa-file"></i>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  created() {},
  data() {
    return {
      attachMenuShow: false,
      uploading: false,
    };
  },
  props: {
    chatId: { type: Number, default: null },
  },
  methods: {
    pushMessage() {
      if (this.chatId === null) return;
      let text = this.$refs.messageText.value.trim();
      if (text === "") return;

      this.$refs.messageText.value = "";

      this.sendMessage(text);
    },
    hideAttachMenu() {
      this.attachMenuShow = false;
    },
    addNewLine() {
      this.$refs.messageText.value += "\n";
    },
    focusInput(event) {
      let target = event.target.closest("li");

      if (target === null) return;

      event.target.closest("li").querySelector("input").click();
    },
    async sendFile(event) {
      if (this.chatId === null) return;
      let form = event.target.closest("form");
      let data = new FormData(form);

      this.uploading = true;

      await axios
        .post(form.action, data)
        .then((response) => this.$emit("message-send", response.data))
        .catch((error) => {
          this.$emit("message-error", error.response.data.message);
        });

      this.uploading = false;
    },
    sendMessage(text) {
      axios
        .post(`/chats/${this.chatId}/messages`, {
          text: text,
        })
        .then((response) => {
          this.$emit("message-send", response.data);
        })
        .catch((error) => this.$emit("message-error", error.message));
    },
  },
};
</script>

<style lang="scss" scoped></style>

<template>
  <!-- Исходящее -->
  <div class="d-flex justify-content-end mb-4 message" v-if="isSend">
    <div class="position-relative w-100 d-flex flex-column align-items-end">
      <div class="msg_cotainer msg_cotainer_send">
        <div class="p-1" v-if="message.text">
          {{ message.text }}
        </div>
      </div>
      <span class="msg_time_send">{{ formatDate(message.created_at) }}</span>
    </div>
    <div class="img_cont_msg">
      <img class="rounded-circle user_img_msg" src="/storage/images/avatar.jpg" />
    </div>
  </div>

  <!-- Входящее -->
  <div class="d-flex justify-content-start mb-4 message" v-else>
    <div class="img_cont_msg">
      <img class="rounded-circle user_img_msg" :src="chatImage" />
    </div>
    <div class="position-relative w-100 d-flex flex-column align-items-start">
      <div class="msg_cotainer msg_cotainer_receive">
        <div
          class="msg__photo-container"
          v-if="message.photo"
          @click="$emit('show-image', message.photo)"
        >
          <img class="" :src="'/telegram/files/' + message.photo" alt="Photo" />
        </div>
        <div class="msg__file-container" v-if="message.document">
          <a
            class="document-name"
            :href="'/telegram/files/' + message.document"
            :download="message.document_name"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="30"
              height="30"
              fill="currentColor"
              class="bi bi-file-earmark-fill"
              viewBox="0 0 16 16"
            >
              <path
                d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm5.5 1.5v2a1 1 0 0 0 1 1h2l-3-3z"
              />
            </svg>
            {{ message.document_name }}
          </a>
        </div>
        <div class="p-1" v-if="message.text">
          {{ message.text }}
        </div>
      </div>
      <div class="msg_time">{{ formatDate(message.created_at) }}</div>
    </div>
  </div>
</template>

<script>
import { DateTime } from "luxon";
export default {
  name: "Message",
  mounted() {},
  data() {
    return {};
  },
  props: {
    message: Object,
    chatImage: String,
    botId: String,
  },
  methods: {
    formatDate(dateString) {
      let dt = DateTime.fromISO(dateString);
      if (dt.toFormat("yyyy LLL dd") === DateTime.now().toFormat("yyyy LLL dd")) {
        return dt.toLocaleString(DateTime.TIME_SIMPLE);
      }

      return (
        dt.toLocaleString(DateTime.TIME_SIMPLE) +
        ", " +
        dt.toLocaleString(DateTime.DATE_FULL)
      );
    },
  },
  computed: {
    isSend() {
      return this.botId == this.message.from;
    },
  },
};
</script>

<style lang="scss" scoped></style>

<template>
  <li
    :class="{ active: chat.id === selectedChatId }"
    @click="() => $emit('chat-activated', chat)"
  >
    <div class="d-flex bd-highlight">
      <div class="img_cont">
        <img class="rounded-circle user_img" :src="chat.small_chat_photo" />
        <span class="unread_icon" v-if="chat.is_unread"></span>
      </div>
      <div class="user_info">
        <span class="d-block text-truncate">{{ chat.first_name }}</span>
        <p class="mb-1 text-truncate">
          {{ lastMessageFrom() }}
          {{ chat.last_message_text ?? "Файл" }}
        </p>
        <p class="text-truncate">{{ formatDate(chat.last_message) }}</p>
      </div>
    </div>
  </li>
</template>

<script>
import { DateTime } from "luxon";
export default {
  created() {},
  data() {
    return {};
  },
  props: {
    chat: Object,
    selectedChatId: Number,
    botId: String,
    user: Object,
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
    lastMessageFrom() {
      if (this.botId !== this.chat.last_message_from) return;

      if (this.chat.last_message_user_id === this.user?.id) return "Вы: ";

      return `${this.chat.last_message_user_name}: `;
    },
  },
};
</script>

<style lang="scss" scoped></style>

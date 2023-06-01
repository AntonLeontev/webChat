<template>
  <li
    :class="{ active: chat.id === selectedChatId }"
    @click="() => $emit('chat-activated', chat)"
  >
    <div class="d-flex bd-highlight">
      <div class="img_cont">
        <img class="rounded-circle user_img" :src="chat.small_chat_photo" />
        <span class="unread_icon"></span>
      </div>
      <div class="user_info">
        <span class="d-block text-truncate">{{ chat.first_name }}</span>
        <p>@{{ chat.username }}</p>
      </div>
    </div>
  </li>
</template>

<script>
export default {
  created() {},
  data() {
    return {};
  },
  props: {
    chat: Object,
    selectedChatId: Number,
  },
  methods: {},
};
</script>

<style lang="scss" scoped></style>

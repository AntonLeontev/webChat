<template>
  <div class="d-flex justify-content-end mb-4 message" v-if="botId == message.from">
    <div class="position-relative">
      <div class="msg_cotainer msg_cotainer_send">
        {{ message.text }}
      </div>
      <span class="msg_time_send">{{ formatDate(message.created_at) }}</span>
    </div>
    <div class="img_cont_msg">
      <img class="rounded-circle user_img_msg" src="" />
    </div>
  </div>

  <div class="d-flex justify-content-start mb-4 message" v-else>
    <div class="img_cont_msg">
      <img class="rounded-circle user_img_msg" :src="chatImage" />
    </div>
    <div class="position-relative w-100">
      <div class="msg_cotainer msg_cotainer_receive">
        {{ message.text }}
      </div>
      <div class="msg_time">{{ formatDate(message.created_at) }}</div>
    </div>
  </div>
</template>

<script>
import { DateTime } from "luxon";
export default {
  name: "Message",
  mounted() {
    console.log(this.botId, this.message.from);
  },
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
};
</script>

<style lang="scss" scoped></style>

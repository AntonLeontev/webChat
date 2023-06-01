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
            <contact></contact>
          </ul>
        </div>
      </div>
    </div>

    <div class="col-md-8 col-xl-6 chat">
      <div class="card">
        <div class="card-header msg_head">
          <div class="d-flex bd-highlight">
            <div class="img_cont">
              <img
                class="rounded-circle user_img"
                src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg"
              />
              <span class="online_icon"></span>
            </div>
            <div class="user_info">
              <span>Chat with Khalid</span>
              <p>1767 Messages</p>
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
          <!-- <message></message> -->
          <div class="d-flex justify-content-start mb-4">
            <div class="img_cont_msg">
              <img
                class="rounded-circle user_img_msg"
                src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg"
              />
            </div>
            <div class="msg_cotainer">
              На площадке запрещен обмен контактами. Поэтому передаю тут. Напишите мне в
              телеграм https://t.me/totsamiyparen. Либо WhatsApp +7912-651-04-64 Все
              обсудим
              <span class="msg_time">8:40 AM, Today</span>
            </div>
          </div>
          <!-- <div class="d-flex justify-content-end mb-4">
            <div class="msg_cotainer_send">
              Hi Khalid i am good tnx how about you?
              <span class="msg_time_send">8:55 AM, Today</span>
            </div>
            <div class="img_cont_msg">
              <img class="rounded-circle user_img_msg" src="" />
            </div>
          </div>
          <div class="d-flex justify-content-start mb-4">
            <div class="img_cont_msg">
              <img
                class="rounded-circle user_img_msg"
                src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg"
              />
            </div>
            <div class="msg_cotainer">
              I am good too, thank you for your chat template
              <span class="msg_time">9:00 AM, Today</span>
            </div>
          </div>
          <div class="d-flex justify-content-end mb-4">
            <div class="msg_cotainer_send">
              You are welcome Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Architecto rem delectus optio non officiis nemo, omnis eum, placeat iusto
              ipsum, tempora dolorem atque neque soluta error id mollitia voluptatum
              harum.
              <span class="msg_time_send">9:05 AM, Today</span>
            </div>
            <div class="img_cont_msg">
              <img class="rounded-circle user_img_msg" src="" /> 
            </div>
          </div>
          <div class="d-flex justify-content-start mb-4">
            <div class="img_cont_msg">
              <img
                class="rounded-circle user_img_msg"
                src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg"
              />
            </div>
            <div class="msg_cotainer">
              I am looking for your next templates
              <span class="msg_time">9:07 AM, Today</span>
            </div>
          </div>
          <div class="d-flex justify-content-end mb-4">
            <div class="msg_cotainer_send">
              Ok, thank you have a good day
              <span class="msg_time_send">9:10 AM, Today</span>
            </div>
            <div class="img_cont_msg">
              <img class="rounded-circle user_img_msg" src="" />
            </div>
          </div>
          <div class="d-flex justify-content-start mb-4">
            <div class="img_cont_msg">
              <img
                class="rounded-circle user_img_msg"
                src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg"
              />
            </div>
            <div class="msg_cotainer">
              Bye, see you
              <span class="msg_time">9:12 AM, Today</span>
            </div>
          </div> -->
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
  created() {},
  data() {
    return {};
  },
  props: {},
  methods: {},
  components: { Contact, Message },
};
</script>

<style lang="scss" scoped></style>

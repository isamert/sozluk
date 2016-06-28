<div id="message_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">mesaj yolla</h4>
      </div>
      <div class="modal-body">
          <form class="contact" name="message" id="form_message">
          <div class="control-group">
              <label class="control-label"  for="message_content">mesaj</label>
              <div class="controls">
                  <textarea class="form-control" rows="5" id="message_content" name="message_content"></textarea>
              </div>
          </div>
          <input id="member_id_receiver" type="hidden" name="member_id_receiver" value="" />
          <input type="hidden" name="send_message" />
        </form>
      </div>
      <div class="modal-footer">
        <input class="btn btn-success" type="submit" value="sal!" id="send_message" name="send_message">
        <a href="#" class="btn" data-dismiss="modal">neyse</a>
      </div>
    </div>

  </div>
</div>
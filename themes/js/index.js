$(document).ready(function(){
    $.decodeURI = function(content) {
        return decodeURIComponent(content).replace(/\+/g, " ");
    }
    $.showWarning = function(title, content, alert_type = 'info') {
      var html =  '<div class="alert alert-dismissible alert-' + alert_type + '">' +
                  '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                  '<strong>' + title + '</strong> ' + content +
                  '</div>';
      $("#notification_area").html(html);
    }
    
    $.urlParam = function(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results==null){
           return null;
        }
        else{
           return results[1] || 0;
        }
    }
    
    function pageLoad(args) {
        if($.urlParam("warning") === "yes") {
            $.showWarning($.decodeURI($.urlParam("title")), $.decodeURI($.urlParam("content")), $.decodeURI($.urlParam("type")));
        }
    }
    
    function startEvents() {
        $(".vote").off();
        $(".show_reply").off();
        $(".show_reply").off();
        $(".set-category").off();
          
        $(".vote").on("click", function(){
            var $vote = $(this);
            var $entry_id = $(this).attr("entry_id");
            var $entry_vote = $(this).attr("entry_vote");
            $.post("entry.php",
            {
                entry_vote: $entry_vote,
                entry_id: $entry_id,
            },
            function(data, status){
                if (data.trim() === "true")
                  $vote.html(parseInt($vote.html()) + 1);
            });
        });
          
        $(".show_reply").on("click", function(){
            $(this).hide(); // FIXME: show "cevapları gizle"
            var $entry_id = $(this).attr("entry_id");
            $.get("entry.php", {operation:"get_replies", entry_id:$entry_id}, function(data, status) {
                $("#reply_"+ $entry_id).append(data);
                startEvents();
              });
        });
          
        $(".set-category").on("click", function(){
            $("#selected_cat_id").val($(this).attr("cat_id"));
            $("#cat_finder").val($(this).html());
        });
    }
    
    $(".bb").click(function(){
        var textArea = $('#txt_entry_content');
        var len = textArea.val().length;
        
        var start = textArea[0].selectionStart;
        var end = textArea[0].selectionEnd;
        
        var openTag = $(this).attr("openTag");
        var closeTag = $(this).attr("closeTag");
        
        if (start === end) {
            var caretPos = textArea[0].selectionStart;
            textArea.val(textArea.val().substring(0, caretPos) + openTag + " " + closeTag + textArea.val().substring(caretPos));  
        }
        else {
            var selectedText = textArea.val().substring(start, end);
            var replacement = openTag + selectedText + closeTag;
            textArea.val(textArea.val().substring(0, start) + replacement + textArea.val().substring(end, len));
        } 
    });
    
    $(".btn-send-message").click(function(){
      $("#member_id_receiver").attr("value", $(this).attr("member_id"));
    });
    
    $("#send_message").click(function(){
        $.ajax({
            type: "POST",
            url: "member.php",
            data: $('#form_message').serialize(),
            success: function(result){
              if(result.trim() === "true") { //TODO: show message about that sent
                $("#message_modal").modal('hide');
                $.showWarning("yaşasın!", "mesajınız yollandı!", "success");
              }
              else {
                $("#message_modal").modal('hide');
                $.showWarning("sıkıntı var", "mesaj yollanırken bazı sıkıntılar oldu ve mesajınız gönderilemedi.", "danger");
              }
            },
            error: function(){
                alert("failure");
            }
        });
    });
    
    $(".theme-select").click(function(){
      $.get("functions.php", {set_theme:$(this).html() + ".css"}, function(data, status) {
          location.reload();
        });
    });
    
    var timeout;
    $('#cat_finder').on('input', function () {
        clearTimeout(timeout);
        $('#found_categories').html('<strong>aranıyor...</strong>');
            $("#selected_cat_id").val("");
            var self = this;
            timeout = setTimeout(function () {
                $.get("category.php", {operation:"search", query:$(self).val()}, function(data, status) {
                   $('#found_categories').html(data);
                   startEvents();
                });
        }, 1000);
    });
    
    $('#query').typeahead({
      name : 'query',
      remote: {
        url : 'topic.php?q=%QUERY'
      }
    });
    $('.tt-query').css('background-color','#fff');
    
    pageLoad();
    startEvents();
});
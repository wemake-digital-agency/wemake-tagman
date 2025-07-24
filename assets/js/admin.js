jQuery(function($){

    if(typeof(wmtm_update_param)==="undefined"){
        return false;
    }

    // Detect unsaved changes

    var wmtm_ajax_queue = 0;

    $(window).bind("beforeunload", function(event){
        if(wmtm_ajax_queue > 0){
            return wmtm_language.unsaved_changes;
        }
    });

    // Submit form

    var hide_result_tm = 0;

    $(document).on("submit", ".wmtm-form", function(event){

        var $form = $(this),
            $result = $form.find(".wmtm-result");

        // Show loading spiner

        $form.addClass("wmtm-loading");

        // Hide messages

        window.clearTimeout(hide_result_tm);

        $result.removeClass("wmtm-show wmtm-error");

        // Check errors

        var $errors = $form.find(".wmtm-form-field.wmtm-error");

        if($errors.length){

            // Tab focus

            $(".wmtm-settings-tabs-item[href='#" + $errors.closest(".wmtm-settings-tabs-content").attr("id") + "']").trigger("click");

            // Stop sending

            $form.removeClass("wmtm-loading");
            event.preventDefault();

            return false;

        }

        // Set ajax queue

        wmtm_ajax_queue++;

        // Ajax submit

        $form.ajaxSubmit({
            type: "POST",
            dataType: "json",
            success: function(data){

                // Show message

                var message = "";

                if(data.success){
                    message = wmtm_language.success;
                }

                $result.addClass("wmtm-show");
                $result.html(message);

                // Hide message

                hide_result_tm = window.setTimeout(function(){
                    $result.removeClass("wmtm-show wmtm-error");
                }, 5000);

                // Hide loading spinner

                $form.removeClass("wmtm-loading");

                // Unset ajax queue

                wmtm_ajax_queue--;

            }, error: function(){
                // Show alert
                alert(wmtm_language.request_error);
                // Unset ajax queue
                wmtm_ajax_queue--;
            }
        });

        event.preventDefault();

    });

    // Disable enter submit

    $(document).on("keypress", ".wmtm-form", function(e){
        var code = e.keyCode || e.which;
        if(code===13){
            e.preventDefault();
            return false;
        }
    });

    // Set field for submit

    $(document).on("click", ".wmtm-form-submit", function(){

        var $this = $(this),
            $tab = $this.closest(".wmtm-settings-tabs-content"),
            $switcher = $tab.find(".wmtm-switcher"),
            $switcher_chk = $switcher.find("input[type='checkbox']"),
            $input = $tab.find(".wmtm-form-field.last input"),
            tab_checked = false;

        $(".wmtm-form input[name='submit_fl']").val($(this).attr("data-submit"));

        if($this.hasClass("disabled")) {
            return false;
        }

        if($input.wmtm_trim_val()){
            tab_checked = true;
        }

        // Switch tab

        if(tab_checked!==$switcher_chk.prop("checked")){
            $switcher.addClass("dont-change-bt");
            $switcher.find(".wmtm-form-label").trigger("mousedown");
            $switcher_chk.prop("checked", tab_checked).trigger("change");
            $switcher.removeClass("dont-change-bt");
        }

        // Change button text

        window.setTimeout(function(){
            if($input.wmtm_trim_val()){
                $this.text(wmtm_language.submit_text1);
            }else{
                $this.text(wmtm_language.submit_text2);
            }
            $this.addClass("disabled");
        }, 500);

    });

    // Detect input changes

    $(document).on("keyup change", ".wmtm-form-field.last input", function(){

        var $this = $(this),
            $button = $this.next("button"),
            $switcher_chk = $this.closest(".wmtm-settings-tabs-content").find(".wmtm-switcher input[type='checkbox']");

        // Change button text

        if(!$switcher_chk.prop("checked")){
            if($this.wmtm_trim_val()){
                $button.text(wmtm_language.submit_text2);
            }else{
                $button.text(wmtm_language.submit_text1);
            }
        }else{
            $button.text(wmtm_language.submit_text1);
        }

        // Change button status

        $button.removeClass("disabled");

    });

    // WMT settings - tabs

    $(document).on("click", ".wmtm-settings-tabs-item", function(){

        var $this = $(this);

        $(".wmtm-settings-tabs-item.active").removeClass("active");
        $this.addClass("active");

        $(".wmtm-settings-tabs-content").hide();
        $(".wmtm-settings-tabs-content" + $this.attr("href")).show();

        return false;

    });

    window.setTimeout(function(){
        $(".wmtm-settings-tabs-item:first").trigger("click");
    });

    // Switchers / checkboxes

    $(document).on("change", ".wmtm-switcher input[type='checkbox']", function(){

        var $this = $(this),
            $parent = $this.closest(".wmtm-switcher"),
            $label = $parent.find(".wmtm-form-label");

        if($this.prop("checked")){
            $label.text($parent.attr("data-text-on"));
            $parent.addClass("checked");
        }else{
            $label.text($parent.attr("data-text-off"));
            $parent.removeClass("checked");
        }

    });

    // Enable tab with AJAX

    $(document).on("mousedown", ".wmtm-switcher .wmtm-form-label", function(){

        var $this = $(this),
            $switcher = $this.closest(".wmtm-switcher"),
            $switcher_chk = $switcher.find("input[type='checkbox']"),
            $button = $this.closest(".wmtm-settings-tabs-content").find(".wmtm-form-submit");

        if(!$switcher.hasClass("dont-change-bt")){
            if($switcher_chk.prop("checked")){
                $button.text(wmtm_language.submit_text2);
            }else{
                $button.text(wmtm_language.submit_text1);
            }
        }

        $.ajax({
            type: "GET",
            dataType: "json",
            context: document.body,
            url: $(".wmtm-form").attr("data-enable-tab-action") + "&option_name=" + $switcher_chk.attr("name") + "&enabled=" + ($switcher_chk.prop("checked") ? 0 : 1),
        });

    });

    $(".wmtm-switcher input[type='checkbox']").trigger("change").addClass("ready");

    // Check plugin update

    if(typeof(wmtm_update_param)!=="undefined" && !wmtm_update_param.checked){
        $.ajax({
            url: wmtm_update_param.action_check,
            context: document.body
        });
    }

    // Run plugin update

    $.fn.showUpdateError = function(){

        var $this = $(this),

        $update_message = $this.find(".update-message");
        $update_message.attr("class", "update-message notice inline notice-warning notice-alt notice-error")
            .html("<p class='notice-text update-text'>" + wmtm_language.update_error + "</p>");

        $this.removeClass("updating-message");

    };

    $(document).on("click", ".wmtm-update-link", function(){

        var $plugin_update_row = $(this).parents(".plugin-update-tr"),
            $notice = $plugin_update_row.find(".notice");

        $plugin_update_row.addClass("updating-message");

        $.ajax({
            type: "POST",
            dataType: "json",
            url: wmtm_update_param.action_run,
            context: document.body
        }).done(function(data){
            if(typeof(data)!=="undefined" && typeof(data.error)!=="undefined" && data.error===0) {
                $plugin_update_row.removeClass("updating-message").addClass("updated");
                $notice.removeClass("notice-warning").addClass("updated-message notice-success");
            }else{
                $plugin_update_row.showUpdateError();
            }
        }).fail(function(){
            $plugin_update_row.showUpdateError();
        });

        return false;

    });

    // Functions

    $.fn.wmtm_trim_val = function(){
        var s = $(this).val();
        if(s===""){
            return false;
        }
        return s.replace(/ +/g, "");
    };

});
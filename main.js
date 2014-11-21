$(document).ready(function() {
    var js_editor = ace.edit($('#js-editor')[0]);
    js_editor.setTheme("ace/theme/twilight");
    js_editor.getSession().setMode("ace/mode/javascript");
    //js_editor.on("change", save);

    var css_editor = ace.edit($('#css-editor')[0]);
    css_editor.setTheme("ace/theme/twilight");
    css_editor.getSession().setMode("ace/mode/css");
    //css_editor.on("change", save);

    var html_editor = ace.edit($('#html-editor')[0]);
    html_editor.setTheme("ace/theme/twilight");
    html_editor.getSession().setMode("ace/mode/html");
    //html_editor.on("change", save);

    var lastSave = 0;
    var project    = $('#project').val();

    function showResult() {
        $('#result iframe').attr('src', "get_page.php?project=" + project);
        $('iframe').load(function() {
            $('#result #titlebar').html($('iframe')[0].contentDocument.title);
        });
    }

    function sync() {
        var javascript = js_editor.getValue();
        var css        = css_editor.getValue();
        var html       = html_editor.getValue();
        data = {};
        data.project    = project;
        data.json       = "true";
        $.get('get_page.php', data).then(function(result) {
            var saved = JSON.parse(result);
            if(saved.project != project) {
                console.log("Something went wrong (%s != %s)", saved.project, project);
                console.log(saved);
            }
            var changed = false;
            if(saved.html != html) {
                html_editor.setValue(saved.html);
                changed = true;
            }
            if(saved.css != css) {
                css_editor.setValue(saved.css);
                changed = true;
            }
            if(saved.javascript != javascript) {
                js_editor.setValue(saved.javascript);
                changed = true;
            }
            showResult();
        });
    }

    function save() {
        data = {};
        data.javascript = js_editor.getValue();
        data.css        = css_editor.getValue();
        data.html       = html_editor.getValue();
        data.project    = project;
        $.post('save.php', data).then(function(result) {
            // Successful save
            showResult();
        });
    }

    sync();
    showResult();
    $('#save').click(save);
    $('#sync').click(sync);
});


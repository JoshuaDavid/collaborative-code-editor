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
    
    $('#height').val($('#iframe-container').height());
    $('#width').val($('#iframe-container').width());
    $('#resize').click(function() {
        setIFrameSize($('#width').val(), $('#height').val());
    });

    var lastSave = 0;
    var project    = $('#project').val();

    function showResult() {
        $('#result iframe').attr('src', "get_page.php?project=" + project);
        $('iframe').load(function() {
            // Make the result stuff global
            window.resultWindow = $('#result iframe')[0].contentWindow;
            window.resultDocument = $('#result iframe')[0].contentDocument;
            // Make sure the title changes as appropriate
            var lastTitle = "";
            setInterval(function showTitle() { 
                if(resultDocument.title != lastTitle) {
                    $('#result #titlebar').html(resultDocument.title);
                    lastTitle = resultDocument.title;
                }
            }, 100);
            var $firebug = $("<script/>");
            $firebug.appendTo(resultDocument.head);
            $firebug.attr("src", "firebug-lite/firebug-lite.js");
            function initFirebug() {
                if(resultWindow.firebug) {
                    resultWindow.firebug.init();
                    //resultWindow.firebug.win.minimize();
                    $(resultDocument).find('#Firebug, #FirebugIFrame').appendTo("#console-inner");
                    setConsoleSize();

                } else {
                    setTimeout(initFirebug, 100);
                }
            }
            initFirebug();
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

    sync()
    // setTimeout(save, 1500);
    showResult();
    $('#save').click(save);
    $('#sync').click(sync);


});

function setConsoleSize() {
    /*
    $('#Firebug, #FirebugIFrame').css({
        "left": "auto",
        "position": "relative !important",
    });
    */
    resultWindow.firebug.win.setHeight($('#console-inner').height() * 0.9);
    $('#Firebug').width($("#console-inner").width());
    $('#Firebug').height($("#console-inner").height());
    $('#FirebugIFrame').width($("#console-inner").width());
    $('#FirebugIFrame').height($("#console-inner").height());
}

function setIFrameSize(width, height) {
    var viewHeight = $('#iframe-container').height();
    var viewWidth  = $('#iframe-container').width();

    $iframe = $('#result iframe');

    // Aspect Ratio
    ar = width / height;
    viewAR = viewWidth / viewHeight;
    if(viewHeight >= height && viewWidth >= width) {
        // No zoom is required -- we can directly scale the iframe
        setIFrameScale(1.0);
    } else if(ar > viewAR) {
        // Width is the limiting factor
        var scale = viewWidth / width;
        setIFrameScale(scale);
    } else {
        // Height is the limiting factor.
        var scale = viewHeight / height;
        setIFrameScale(scale);
    }
    $iframe.height(height);
    $iframe.width(width);
    setTimeout(setConsoleSize, 100);
}
function setIFrameScale(scaleFactor) {
    $('#result iframe').css({
        '-webkit-transform': 'scale(' + scaleFactor + ')',
        '-webkit-transform-origin': '0 0',
        '-moz-transform': 'scale(' + scaleFactor + ')',
        '-moz-transform-origin': '0 0',
        '-o-transform': 'scale(' + scaleFactor + ')',
        '-o-transform-origin': '0 0',
    });
}



<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <ol class="indicator"></ol>
</div>

<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
<tr class="template-upload">
    <td>
        <div class="col-xs-12 col-sm-9 col-md-10 col-lg-10">
            <div class="row">
                {%=file.name%}  
                <span class="size text-muted">Processing...</span>
                
                <strong class="error text-danger"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span></strong>
                <span class="text-success"></span>
            </div>
        </div>

        <div class="col-xs-12 col-sm-3 col-md-1 col-lg-1 pull-right">
            <div class="row">
                {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary btn-sm start pull-right" disabled>
                    Start
                </button>
                {% } %}
                {% if (!i) { %}
                <button class="btn btn-default btn-sm cancel pull-right">
                    Cancel
                </button>
                {% } %}
            </div>
        </div>

    </td>
</tr>
{% } %}
</script>

<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
<tr class="template-download">
    <td>
        <div class="col-xs-12 {% if (!file.error) { %}  col-sm-7 col-md-7 col-lg-7 {% } else { %}  col-sm-11 col-md-11 col-lg-11 {% } %}">
            <div class="row">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}"  {% if (file.isImage) { %} data-gallery {% } else { %} target="_blank" {% }  %}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
                <span class="text-muted">{%=o.formatFileSize(file.size)%}</span>
                {% if (file.error) { %}
                <br>
                <span class="label label-danger">Error</span> <strong class="text-danger">{%=file.error%}</strong>
                {% }else{ %}
                <i class="text-success glyphicon glyphicon-ok"></i>
                {% }  %}
            </div>
        </div>
        {% if (!file.error) { %}
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <div class="form-group visible-xs"></div>
            <input data-provide="datepicker" data-date-autoclose="true" data-date-format="dd-mm-yyyy" data-date-disable-touch-keyboard="true" data-date-today-highlight="true" class="input-sm grd-white form-control" placeholder="Due Date" title="" autocomplete="off" id="dueDate_{%=file.filename%}" name="SpendForm[listDueDates][{%=file.filename%}]" type="text" data-original-title="Due Date">
            <div class="form-group visible-xs"></div>
        </div>
        {% } %}
    
        <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1 pull-right">
            <div class="row">
                {% if (file.deleteUrl) { %}
                <button title="Delete" href="javascript:;" data-loading-text="Deleting..." class="btn btn-default btn-sm delete pull-right" data-type="GET" data-url="{%=file.deleteUrl%}">
                    <i class="glyphicon glyphicon-remove"></i>
                </button>
                {% } else { %}
                <button class="btn btn-default cancel btn-xs pull-right">
                    Cancel
                </button>
                {% } %}
                <input type="hidden" value="{%=file.filename%}" name="SpendForm[listAttachments][]" id="SpendForm_listAttachments">
            </div>
        </div>

    </td>
</tr>
{% } %}
</script>
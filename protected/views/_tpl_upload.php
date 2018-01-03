
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

    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 pull-right">
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
        {% if (file.error) { %}
        <div class="col-xs-12 col-sm-11 col-md-11 col-lg-11">
            <div class="row">
                <span>{%=file.name%}</span>
                <span class="text-muted">{%=o.formatFileSize(file.size)%}</span>
                <br>
                <span class="label label-danger">Error</span> <span class="text-danger">{%=file.error%}</span>
            </div>
        </div>
        {% }else{ %}  
        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <div class="row">                
                <input value="{%=file.name%}" placeholder="File Name" max="200" name="MessageActivityForm[listAttachments][{%=file.hashname%}][file_label]" id="MessageActivityForm_file_label" class="form-control smallfont" type="text" maxlength="100">
                <input type="hidden" name="MessageActivityForm[listAttachments][{%=file.hashname%}][file_source]" id="MessageActivityForm_file_source" value="1">
            </div>
        </div>
        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
            <div class="row">
                <?php echo CHtml::dropDownList('MessageActivityForm[listAttachments][{%=file.hashname%}][doc_type]', '', CMap::mergeArray(array('' => '-- Select Type --'), Document::itemAlias('Type')),array('class'=>'form-control smallfont','placeholder'=>'Type')); ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
            <div class="row">
                <input placeholder="Document Code" max="30" name="MessageActivityForm[listAttachments][{%=file.hashname%}][doc_code]" id="MessageActivityForm_doc_code" class="form-control smallfont" type="text" maxlength="30">
            </div>
        </div>
        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
            <div class="row">
                <input data-provide="datepicker" data-date-autoclose="true" data-date-format="dd-mm-yyyy" data-date-disable-touch-keyboard="true" data-date-today-highlight="true" class="input-sm grd-white form-control" rel="tooltip" placeholder="Document Date" title="" autocomplete="off" id="dueDate_{%=file.filename%}" name="MessageActivityForm[listAttachments][{%=file.hashname%}][doc_date]" type="text" data-original-title="Due Date">
            </div>
        </div>
        {% } %}
        <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1 pull-right">
            <div class="row">
            {% if (file.deleteUrl) { %}
                <button title="Delete" href="javascript:;" data-loading-text="Deleting..." class="btn btn-default btn-sm delete pull-right" data-type="GET" data-url="{%=file.deleteUrl%}">
                    <i class="glyphicon glyphicon-remove"></i>
                </button>
                {% } else { %}
                <button class="btn btn-default cancel btn-sm pull-right">
                    Cancel
                </button>
                {% } %}
            </div>
        </div>
    </td>
</tr>
{% } %}
</script>
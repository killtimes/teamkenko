<?php

if ($this->showForm) {
    echo CHtml::beginForm($this->url, 'post', $this->htmlOptions);
}
?>

<?php

if ($this->hasModel()) :
    echo CHtml::activeFileField($this->model, $this->attribute, $htmlOptions) . "\n";
else :
    echo CHtml::fileField('files', '', $htmlOptions) . "\n";
endif;

//if ($htmlOptions['multiple']) {
//    echo CHtml::fileField('files[]', '', $htmlOptions);
//} else {
//    echo CHtml::fileField('files', '', $htmlOptions);
//}
?>

<?php

if ($this->showForm) {
    echo CHtml::endForm();
}?>
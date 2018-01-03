<?php

class ReportModule extends CWebModule {

    public function init() {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            'process.models.*',
            'process.components.*',
            'shop.models.Shop',
            'supplier.models.Supplier',
            'template.models.Task',
            'template.models.ProcessTemplate',
            'template.models.TaskProcessTemplate',
            'template.models.TemplateSchedule',
            'template.models.TaskGroup',
            'alert.models.*'
        ));
    }

}

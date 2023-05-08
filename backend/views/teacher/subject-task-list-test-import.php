<?php

use common\models\FormImportQuestion;
use common\models\system\classifier\TrainingType;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\widgets\Pjax;
use yii\widgets\DetailView;
use common\models\system\classifier\Language;

/**
 * @var $this  \backend\components\View
 * @var $model FormImportQuestion
 * @var $task \common\models\curriculum\ESubjectTask
 * @var $subject \common\models\curriculum\ECurriculumSubject
 */
$this->title = __('Import Questions');
$label = "{$task->subject->name} ({$task->trainingType->name} | {$subject->semester->name} | {$group_labels})";
$url = $url1 = ['teacher/subject-task-list',
    'curriculum' => $task->_curriculum,
    'semester' => $task->_semester,
    'subject' => $task->_subject,
    'training_type' => $task->_training_type,
    'education_lang' => $task->_language
];
$url1['questions'] = 1;
$url1['code'] = $task->id;

$this->params['breadcrumbs'][] = ['url' => ['teacher/subject-tasks'], 'label' => __('Subject Tasks')];
$this->params['breadcrumbs'][] = ['url' => $url, 'label' => $label];
$this->params['breadcrumbs'][] = ['url' => $url1, 'label' => $task->name];
$this->params['breadcrumbs'][] = $this->title;

$items = $model->normalizedContent();
$js = <<<JS
$('#import-form').on('change', function(event){
    if ($('#editor').val().length > 0) {
        $.pjax.submit(event, '#question-form');
    }
}).on('blur', function(event){
}).on('focus', function(){
});

$('#sidebar').theiaStickySidebar({
    additionalMarginTop: 0
});
$("#editor").css("min-height",$(window).height()+"px");
$('#q-file').on('change', function (e) {
    var file = e.target.files[0];
    var reader = new FileReader()
    reader.onload = function (ev) { 
        $('#editor').val(ev.target.result);
        $('#import-form').change();
    }
    reader.readAsText(file);
})
JS;
?>
<?php Pjax::begin(
    ['id' => 'question-form', 'timeout' => false, 'options' => ['data-pjax' => false], 'enablePushState' => false]
);
$this->registerJs($js);
?>

    <div class="row">
        <?php $form = ActiveForm::begin(
            [
                'id' => 'import-form',
                'options' => [
                    'enctype' => 'multipart/form-data',
                    'data-pjax' => true,
                ],
            ]
        ); ?>
        <div class="col col-md-6" id="sidebar">
            <div class="box box-primary ">
                <div class="box-header">
                    <?= Html::submitButton(
                        __('Preview') . ' <i class="fa fa-arrow-right"></i>',
                        [
                            'id' => 'sBtn',
                            'class' => 'btn btn-primary btn-flat pull-right',
                        ]
                    ) ?>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'content')
                                ->textarea(
                                    [
                                        'rows' => 20,
                                        'id' => 'editor',
                                        'placeholder' => __('Question 1
====
Variant 1
====
Variant 2
====
#Variant 3 correct
====
Variant 4
++++
Question 2')
                                    ]
                                )
                                ->label(false) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col col-md-6" id="right_content">
            <?php if (count($items)): ?>
                <div class="box box-primary ">
                    <div class="box-header">
                        <h3 class="box-title">
                            <?= __('Test savollari') ?>
                        </h3>
                    </div>
                    <div class="box-body ">
                        <div class="test-variants">
                            <?php foreach ($items as $k => $item): ?>
                                <p class="bold">
                                    <?= $k + 1 . '. ' . $item['q'] ?>
                                </p>
                                <?php foreach ($item['vars'] as $c => $val): ?>
                                    <?php
                                    $correct = in_array(
                                        $c,
                                        $item['correct'],
                                        true
                                    );
                                    ?>
                                    <div class="p <?= $correct ? 'bg-correct' : '' ?>">
                                        <?= $c . ') ' . $val ?>
                                    </div>
                                <?php endforeach; ?>
                                <br>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="box-footer text-right">
                        <button type="submit" class="btn btn-primary btn-flat" name="import" value="1">
                            <i class="fa fa-check"></i> <?= __('Save') ?>
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

<?php Pjax::end() ?>
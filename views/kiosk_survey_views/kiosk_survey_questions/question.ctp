<?php echo $this->Html->script('kiosk_survey_questions/question.js', array('inline' => false)) ?>
<div class="self-sign-survey-wrapper">
    <h1><?php echo $question['question'] ?></h1>
    <div id="errorMessage"></div>
    <?php echo $this->Session->flash(); ?>
    
    <?php echo $this->Form->create('KioskSurveyQuestions', array('action' => 'question')) ?>
    <?php echo $this->Form->hidden('question_id', array('value' => $question['id'])); ?>
    <?php echo $this->Form->hidden('question_number', array('value' => $this->params['question_number'])); ?>
    <?php echo $this->Form->hidden('survey_id', array('value' => $this->params['survey_id'])); ?>
    <?php echo $this->Form->input('answer') ?>
    <div class="button-row">
        <?php if ($question['type'] === 'yesno'): ?>
            <a href="#" class="self-sign-survey-button" data-value="yes">Yes</a>
            <a href="#" class="self-sign-survey-button" data-value="no">No</a>
        <?php elseif ($question['type'] === 'truefalse'): ?>
            <a href="#" class="self-sign-survey-button" data-value="true">True</a>
            <a href="#" class="self-sign-survey-button" data-value="false">False</a>
        <?php elseif ($question['type'] === 'text'): ?>
             <script language="Javascript">
                 function set_value() {
                     var my_answer = document.forms["KioskSurveyQuestionsQuestionForm"]["my_answer"].value;
                     var d = document.getElementById("my_result");
                     d.setAttribute('data-value', my_answer);
                 }
             </script>        
             <input type="text" name="my_answer" size="50"> <a id="my_result" href="#" onclick="set_value()" class="self-sign-survey-button" data-value="">NEXT</a>
        <?php else: ?>
            <?php foreach ($question['options'] as $key => $value): ?>
            <a href="#" class="self-sign-survey-button" data-value="<?php echo trim($value) ?>"><?php echo $value ?></a>
            <?php endforeach ?>
        <?php endif ?>
    </div>

    <div class="question-number"><?php echo "Question {$questionNumber} of {$totalCount}" ?></div>
    
    <div class="survey-nav button-row">
    	<a href="/kiosk/survey/cancel" class="cancel-button">Cancel</a>
    </div>

    <?php echo $this->Form->end('Next') ?>
    
	<div class="clear"></div>
</div>

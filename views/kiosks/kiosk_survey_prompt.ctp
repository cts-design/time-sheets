<style>
.self-sign-kiosk-link
{
	margin:10px;
}
</style>
<div id="surveyPrompt" class="self-sign-wrapper" style="text-align:center">
	<?php if($kiosk_survey_setting == 'prompt'): ?>
		<h1>Optional Self Sign Survey</h1>
		<!-- TODO: Allow for customization of kiosk survey prompt -->
		<p class="center" style="text-align:center">Would you like to take this moment to fill out our survey?</p>

		<a class="self-sign-kiosk-link" href="/kiosk/survey/<?= $kiosk['KioskSurvey'][0]['id'] ?>">
			Yes
		</a>

		<a class="self-sign-kiosk-link" href="/kiosk/kiosks/self_sign_service_selection">
			No
		</a>
	<?php else: ?>

		<h1>Self Sign Survey</h1>
		<!-- TODO: Allow for customization of kiosk survey prompt -->
		<p class="center" style="text-align:center">Before continuing you will have to complete a survey</p>

		<a class="self-sign-kiosk-link" href="/kiosk/survey/<?= $kiosk['KioskSurvey'][0]['id'] ?>">
			Continue
		</a>

	<?php endif ?>

</div>
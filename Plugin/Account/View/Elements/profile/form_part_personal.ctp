<?php
	$documentTypeOptions = $this->requestAction('/account/profiles/getDocumentTypes');
?>
<fieldset>
    <legend><?php print __('Personal Information'); ?></legend>

<?php

	print $this->BForm->input('Profile.name', array('label' => __('Full Name')));

	print $this->BForm->input(
		'Profile.document_type_id', 
		array('type' => 'select', 'label' => __('Document'), 'empty' => null, 'options' => $documentTypeOptions, 'class' => 'span2')
	);

	print $this->BForm->input(
		'Profile.main_doc',
		array('label' => __('Document Number')),
		array('tip' => __('Do not use punctuation. Only numbers and letters are allowed.'))
	);

    // --------------- Gender --------------------
    $genderOptions = array(
        'M' => __('Male'),
        'F' => __('Female')
    );
	print $this->BForm->input(
		'Profile.gender', 
		array('type' => 'select', 'label' => __('Gender'), 'empty' => null, 'options' => $genderOptions, 'class' => 'span2')
	);

    // --------------- Gender --------------------
	print $this->BForm->input('Profile.company', array('label' => __('Institution/Company')));
	print $this->BForm->input('Profile.exibition_name', array('label' => __('Exibition Name')));

    ?>
</fieldset> <!-- Personal Information -->
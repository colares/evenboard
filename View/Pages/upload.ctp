<h2>File Upload</h2>
<?php
	echo $this->Form->create('Media', array('action' => 'upload', 'type' => 'file'));
	echo $this->Form->file('file', array('label' => __('File')));
	echo $this->Form->submit(__('Upload', true));
	echo $this->Form->end();
?>

<div class="paper form">
    <h2><?php print __('Submit Paper'); ?></h2>
	<?php 
	
		print $this->element('Paper.paper/info_rules');
		print $this->element('Paper.paper/warning_process_add_edit');

		// echo $this->element('paper/warning_document');
		// echo $this->element('paper/warning_format');
		// echo $this->element('paper/warning_process');

    	echo $this->BForm->create('Paper', 'form-horizontal', array('type' => 'file'));
		//echo $this->BForm->requiredTip();
		echo $this->Form->hidden('Paper.user_id', array('value' => $this->Session->read('Auth.User.id')));

		echo $this->BForm->input('Paper.paper_type_id', array('type' => 'select', 'label' => __('Paper Type'), 'empty' => false, 'options' => $paperTypes));

		// RESEARCH LINE
		// required
		echo $this->BForm->input('PaperResearchLine.0.research_line_id', array('type' => 'select', 'label' => __('Research Line'), 'empty' => false, 'options' => $researchLines));

		// PAPER NAME
		// required
	    print $this->BForm->input('Paper.title', array('label' => __('Title'), 'class' => 'span8'), array('tip' =>  __('Max length allowed: 120 characters (ignoring blanks)')));

		// PAPER ABSTRACT
		// required
		// echo $this->BForm->input('Paper.abstract', array('type' => 'textarea', 'label' => __('Abstract'), 'class' => 'span10', 'rows' => 10), array('tip' =>  __('Max length allowed: 1492 characters (ignoring blanks)')));


		// Get max upload file
		$maxUpload = (int)(ini_get('upload_max_filesize'));
		$maxPost = (int)(ini_get('post_max_size'));
		$memoryLimit = (int)(ini_get('memory_limit'));
		$uploadMb = min($maxUpload, $maxPost, $memoryLimit);
		$allowedMimes = implode(', ', array_keys(Configure::read('PaperAllowedMimes')));

		// PAPER FILE
		print $this->BForm->input(
			'Paper.submittedfile',
			array(
				'type' => 'file',
				'label' => __('Summary File')
			),
			__('Allowed file formats: %s. Maximum allowed file size: %d mb.', $allowedMimes, $uploadMb)
		);

		// AUTHOR
		$authorName = $this->Form->input("Paper.author_name", array(
            'class' => "span3",
            'type' => 'text',
            'maxlength' => 150,
            'error' => false,
			'div' => false,
			'label' => false,
			'value' => $profile['Profile']['name'],
			'disabled' => true
        ));

        $mainDoc = $this->Form->input("Paper.author_main_doc", array(
            'class' => "span2",
            'type' => 'text',
            'maxlength' => 15,
            'error' => false,
			'div' => false,
			'label' => false,
			'value' => $profile['Profile']['main_doc'],
			 'after' => '<span class="help-inline">' . __('CPF or Passport') . '</span>',
			 'disabled' => true
        ));

        // todo put it on helper
        $after = '';
        if ($this->Form->isFieldError("Paper.author_name") || $this->Form->isFieldError("Paper.author_main_doc")){
            $divClass .= ' error';
            
            if($this->Form->isFieldError("Paper.author_main_doc"))
                $after .= $this->Form->error("Paper.author_main_doc");
            
            if($this->Form->isFieldError("Paper.author_main_doc"))
                $after .= $this->Form->error("Paper.author_main_doc");
        }

        print $this->BForm->inputMix(
            $authorName . '&nbsp;&nbsp;&nbsp; <span style="font-size:120%">' . __('Document') . '</span> ' . $mainDoc,
            array(
                'label' => __('Author'),
                'required' => isset($item['required']),
				'error' => !empty($after),
				'errorMsg' => !empty($after) ? $after : null,
            ),
            array('tip' => __('Author name and author main document are the same from registered user.'))
        );

        // CO-AUTHORS
		for($i=1;$i<=7;$i++){
			$coAuthorName = $this->Form->input("Paper.co_author_name_$i", array(
	            'class' => "span3",
	            'type' => 'text',
	            'maxlength' => 150,
	            'error' => false,
				'div' => false,
				'label' => false
	        ));

	        $mainDoc = $this->Form->input("Paper.co_author_main_doc_$i", array(
	            'class' => "span2",
	            'type' => 'text',
	            'maxlength' => 15,
	            'error' => false,
				'div' => false,
				'label' => false,
				 'after' => '<span class="help-inline">' . __('CPF or Passport') . '</span>'
	        ));

	  
	  		// todo put it on helper
	        $after = '';
	        if ($this->Form->isFieldError("Paper.co_author_name_$i") || $this->Form->isFieldError("Paper.co_author_main_doc_$i")){
	            if($this->Form->isFieldError("Paper.co_author_name_$i"))
	                $after .= $this->Form->error("Paper.co_author_name_$i");
	            
	            if($this->Form->isFieldError("Paper.co_author_main_doc_$i"))
	                $after .= $this->Form->error("Paper.co_author_main_doc_$i");
	        }

	        print $this->BForm->inputMix(
	            $coAuthorName . '&nbsp;&nbsp;&nbsp; <span style="font-size:120%">' . __('Document') . '</span> ' . $mainDoc, // . ' - ' . $phone , 
	            array(
	                'label' => __('Co-Author') . " $i",
	                'required' => isset($item['required']),
					'error' => !empty($after),
					'errorMsg' => !empty($after) ? $after : null
	            ),
	            array('tip' => !empty($after) ? $after : null)
	        );

		} // for co-authors
		
		echo $this->BForm->end(
			__('Submit Paper'),
			__('Cancel'),
			null,
			null,
			array(
				'tip' => 
					__('After submitting this work, this will be available to be modified up to %s, %s.',
						date('d/m/Y', Configure::read('PaperSubmissionLimitDate') ),
						date('H:i', Configure::read('PaperSubmissionLimitDate') )
					)
			) 
		);
	?>
</div>